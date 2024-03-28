<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.miniorange.com
 * @since      1.0.0
 *
 * @package    Miniorange_Oauth_20_Server
 * @subpackage Miniorange_Oauth_20_Server/public
 */

/**
 * This file is used to markup the public-facing aspects of the plugin.
 */

require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . DIRECTORY_SEPARATOR . 'errorlogs' . DIRECTORY_SEPARATOR . 'class-mo-oauth-server-debug.php';
require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'helper' . DIRECTORY_SEPARATOR . 'constants' . DIRECTORY_SEPARATOR . 'class-miniorange-oauth-20-server-oauth-constants.php';

require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . DIRECTORY_SEPARATOR . 'endpoints/utils.php';
require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . DIRECTORY_SEPARATOR . 'endpoints/discovery.php';
require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . DIRECTORY_SEPARATOR . 'endpoints/jwt-keys.php';

use OAuth2\Server as OAuth2Server;
use OAuth2\Storage\MoPdo;
use OAuth2\OpenID\GrantType\AuthorizationCode;
use OAuth2\GrantType\UserCredentials;
use OAuth2\GrantType\RefreshToken;
use OAuth2\Response;
use OAuth2\Request;


/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Miniorange_Oauth_20_Server
 * @subpackage Miniorange_Oauth_20_Server/public
 * @author     miniOrange <info@xecurify.com>
 */
class Miniorange_Oauth_20_Server_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Miniorange_Oauth_20_Server_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Miniorange_Oauth_20_Server_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/miniorange-oauth-20-server-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Miniorange_Oauth_20_Server_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Miniorange_Oauth_20_Server_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/miniorange-oauth-20-server-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * This function is used to register the endpoints for the plugin.
	 * It is called from the init hook.
	 */
	public function mo_oauth_server_register_endpoints() {

		$default_routes = $this->mo_oauth_server_get_default_routes();
		$new_routes     = apply_filters( 'mo_oauth_server_define_routes', $default_routes );
		if ( ! empty( $new_routes ) ) {
			$default_routes = array_merge( $new_routes, $default_routes );
		}
		foreach ( $default_routes as $route => $args ) {
			register_rest_route( 'moserver', $route, $args );
		}
		$well_knowns = $this->mo_get_well_known_routes();
		foreach ( $well_knowns as $route => $args ) {
			register_rest_route( 'moserver', '(?P<client_id>\w+)/.well-known/' . $route, $args );
		}
	}

	/**
	 * This function is used to authorize the client.
	 */
	public function mo_oauth_server_authorize() {

		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/helper/class-miniorange-oauth-20-server-utils.php';
		$mo_utils                  = new Miniorange_Oauth_20_Server_Utils();
		$home_url_plus_rest_prefix = $mo_utils->get_home_url_with_permalink_structure();

		global $moos_home_url_plus_rest_prefix;
		$moos_home_url_plus_rest_prefix = $home_url_plus_rest_prefix;

		$protocol = isset( $_SERVER['HTTPS'] ) && 'on' === $_SERVER['HTTPS'] ? 'https://' : 'http://';

		$authorize_url = str_replace( ':/', '://', trim( preg_replace( '/\/+/', '/', $moos_home_url_plus_rest_prefix . '/moserver/authorize' ), '/' ) );

		if ( isset( $_SERVER['REQUEST_URI'] ) && isset( $_SERVER['HTTP_HOST'] ) ) {
			$request_path = wp_parse_url( esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
			$request_url  = $protocol . sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) . $request_path['path'];
		}

		// returns empty string in case of plain permalink structure.
		$permalink_structure = get_option( 'permalink_structure' );

		$validate_url_for_plain_permalinks = false;

		if ( ! $permalink_structure && isset( $request_path['query'] ) ) {

			parse_str( $request_path['query'], $url_part );

			if ( isset( $url_part['rest_route'] ) ) {

				if ( ( strpos( $url_part['rest_route'], '/moserver/authorize' ) !== false ||
				strpos( $url_part['rest_route'], '%2Fmoserver%2Fauthorize' ) !== false ) ) {
					$validate_url_for_plain_permalinks = true;
				}
			}
		}

		if ( ! ( strcmp( $request_url, $authorize_url ) ) || $validate_url_for_plain_permalinks ) {
			if ( isset( $_SERVER['REQUEST_METHOD'] ) ) {
				if ( 'POST' === sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) ) {
					$this->mo_oauth_server_validate_authorize_consent();
					exit();
				}
			}

			$request  = Request::createFromGlobals();
			$response = new Response();
			$server   = $this->mo_oauth_server_init();

			if ( ! $server->validateAuthorizeRequest( $request, $response ) ) {

				MO_OAuth_Server_Debug::error_log( 'Authorization Endpoint - Authorization Request validation failed' );
				MO_OAuth_Server_Debug::error_log( $response );

				$response->send();
				exit;
			}
			$prompt = $request->query( 'prompt' ) ? $request->query( 'prompt' ) : 'consent';
			if ( ! $request->query( 'ignore_prompt' ) && $prompt ) {
				if ( 'login' === $prompt ) {
					$actual_link      = $this->mo_oauth_server_get_current_page_url();
					$custom_login_url = get_option( 'mo_oauth_server_custom_login_url' );
					wp_logout();
					if ( $custom_login_url ) {
						wp_safe_redirect( $custom_login_url . '?redirect_to=' . rawurlencode( str_replace( 'prompt=login', 'prompt=consent', $actual_link ) ) );
						exit();
					} else {
						wp_safe_redirect( home_url() . '/wp-login.php?redirect_to=' . rawurlencode( str_replace( 'prompt=login', 'prompt=consent', $actual_link ) ) );
						exit();
					}
				}
			}
			$current_user = $this->mo_oauth_server_check_user_login( $request->query( 'client_id' ) );
			if ( ! $current_user ) {
				$actual_link      = $this->mo_oauth_server_get_current_page_url();
				$custom_login_url = get_option( 'mo_oauth_server_custom_login_url' );
				if ( $custom_login_url ) {
					wp_safe_redirect( esc_url_raw( $custom_login_url ) . '?redirect_to=' . rawurlencode( $actual_link ) );
					exit();
				} else {
					wp_safe_redirect( home_url() . '/wp-login.php?redirect_to=' . rawurlencode( $actual_link ) );
					exit();
				}
			}

			$prompt_grant  = 'on';
			$is_authorized = true;
			$client_id     = $request->query( 'client_id' );
			$grant_status  = is_null( $client_id ) ? false : get_user_meta( $current_user->ID, 'mo_oauth_server_granted_' . $client_id, true );
			$prompt        = ( 'allow' === $grant_status && $request->query( 'prompt' ) !== 'consent' ) || ( 'deny' === $grant_status && 'allow' === $prompt ) ? 'allow' : 'consent';
			if ( 'allow' === $prompt ) {
				$grant_status = 'allow';
			}
			if ( 'allow' === $grant_status && 'consent' !== $prompt ) {
				$is_authorized = true;
			} elseif ( 'deny' === $grant_status && 'consent' !== $prompt ) {
				$is_authorized = false;
			} elseif ( false === $grant_status || 'consent' === $prompt ) {
				$client_credentials = $server->getStorage( 'client_credentials' )->getClientDetails( $request->query( 'client_id' ) );
				$scope_required     = $request->query( 'scope' );
				$this->mo_oauth_server_render_consent_screen( $client_credentials, $scope_required );
				exit();
			}
			$server->handleAuthorizeRequest( $request, $response, $is_authorized, $current_user->ID );

			update_user_meta( $current_user->ID, 'mo_oauth_server_granted_' . $client_id, 'deny' );
			$response->send();

			MO_OAuth_Server_Debug::error_log( 'Authorization Endpoint execution done' );
			MO_OAuth_Server_Debug::error_log( $response );

			exit();
		}
	}

	/**
	 * Summary of mo_oauth_server_get_default_routes
	 *
	 * Gets the default routes of the plugin.
	 *
	 * @return array<array>
	 */
	public function mo_oauth_server_get_default_routes() {
		$default_routes = array(
			'token'    => array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'mo_oauth_server_token' ),
				'permission_callback' => '__return_true',
			),
			'resource' => array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'mo_oauth_server_resource' ),
				'permission_callback' => '__return_true',
			),
		);
		return $default_routes;
	}

	/**
	 * Summary of mo_get_well_known_routes
	 *
	 * Gets the well-known route.
	 *
	 * @return array<array>
	 */
	public function mo_get_well_known_routes() {
		$well_known_routes = array(
			'openid-configuration' => array(
				'methods'             => 'GET',
				'callback'            => '_mo_discovery',
				'permission_callback' => '__return_true',
			),
			'keys'                 => array(
				'methods'             => 'GET',
				'callback'            => '_mo_jwt_keys',
				'permission_callback' => '__return_true',
			),
		);
		return $well_known_routes;
	}

	/**
	 * Summary of mo_oauth_server_init
	 *
	 * @return OAuth2Server
	 */
	public function mo_oauth_server_init() {
		$master_switch = (bool) get_option( 'mo_oauth_server_master_switch' ) ? get_option( 'mo_oauth_server_master_switch' ) : 'on';
		if ( 'off' === $master_switch ) {
			wp_die( 'Currently your OAuth Server is not responding to any API request, please contact your site administrator.<br><b>ERROR:</b> ERR_MSWITCH' );
		}

		$sqlite_file = MOSERVER_DIR . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'oauth.sqlite';

		if ( ! file_exists( $sqlite_file ) ) {
			require_once MOSERVER_DIR . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'rebuild_db.php';
		}

		$storage = new MoPdo( array( 'dsn' => 'sqlite:' . $sqlite_file ) );

		// create array of supported grant types.
		$grant_types = array(
			'authorization_code' => new AuthorizationCode( $storage ),
			'user_credentials'   => new UserCredentials( $storage ),
			'refresh_token'      => new RefreshToken(
				$storage,
				array(
					'always_issue_new_refresh_token' => true,
				)
			),
		);

		$enforce_state = (bool) get_option( 'mo_oauth_server_enforce_state' ) ? get_option( 'mo_oauth_server_enforce_state' ) : 'off';
		$enable_oidc   = (bool) get_option( 'mo_oauth_server_enable_oidc' ) ? get_option( 'mo_oauth_server_enable_oidc' ) : 'on';

		global $moos_home_url_plus_rest_prefix;

		// instantiate the oauth server.
		$config = array(
			'enforce_state'          => ( 'on' === $enforce_state ),
			'allow_implicit'         => false,
			'use_openid_connect'     => ( 'on' === $enable_oidc ),
			'access_lifetime'        => get_option( 'mo_oauth_expiry_time' ) ? get_option( 'mo_oauth_expiry_time' ) : 3600,
			'refresh_token_lifetime' => get_option( 'mo_oauth_refresh_expiry_time' ) ? get_option( 'mo_oauth_refresh_expiry_time' ) : 1209600,
			'issuer'                 => $moos_home_url_plus_rest_prefix . '/moserver',
		);
		$server = new OAuth2Server( $storage, $config, $grant_types );
		return $server;
	}

	/**
	 * Summary of mo_oauth_server_token
	 *
	 * Handles the token request and response.
	 *
	 * @return void
	 */
	public function mo_oauth_server_token() {
		MO_OAuth_Server_Debug::error_log( 'Token Endpoint execution started.' );
		ob_end_clean();
		if ( isset( $_POST['grant_type'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification -- This is a REST endpoint and is called from external platform
			$grant          = sanitize_text_field( wp_unslash( $_POST['grant_type'] ) ); // phpcs:ignore WordPress.Security.NonceVerification -- This is a REST endpoint and is called from external platform
			$allowed_grants = array( 'authorization_code' );
			if ( ! in_array( $grant, $allowed_grants, true ) ) {

				MO_OAuth_Server_Debug::error_log( 'Token Endpoint - Grant requested not in allowed grants: ' );
				MO_OAuth_Server_Debug::error_log( $grant );

				wp_send_json(
					array(
						'error'             => 'invalid_grant',
						'error_description' => 'The "grant_type" requested is unsupported or invalid',
					),
					400
				);
			}
		}
		$request = Request::createFromGlobals();
		$server  = $this->mo_oauth_server_init();
		$this->mo_oauth_server_set_allow_origin( $request );

		$response = $server->handleTokenRequest( $request );

		MO_OAuth_Server_Debug::error_log( $response );
		MO_OAuth_Server_Debug::error_log( 'Token Endpoint execution done.' );

		$response->send();

		exit;
	}

	/**
	 * Summary of mo_oauth_server_resource
	 *
	 * Handles the resource request and response.
	 *
	 * @return array
	 */
	public function mo_oauth_server_resource() {
		$request  = Request::createFromGlobals();
		$response = new Response();
		$server   = $this->mo_oauth_server_init();

		if ( ! $server->verifyResourceRequest( $request, $response ) ) {
			$response = $server->getResponse();
			$response->send();

			MO_OAuth_Server_Debug::error_log( 'Resource Endpoint - Failed to verify Resource Request' );
			MO_OAuth_Server_Debug::error_log( $response );

			exit();
		}
		$token     = $server->getAccessTokenData( $request, $response );
		$user_info = $this->mo_oauth_server_get_token_user_info( $token );
		if ( is_null( $user_info ) || empty( $user_info ) ) {
			MO_OAuth_Server_Debug::error_log( 'Resource Endpoint - Empty User Info' );
			wp_send_json(
				array(
					'error' => 'invalid_token',
					'desc'  => 'access_token provided is either invalid or does not belong to a valid user.',
				),
				403
			);
		}

		$api_response = array(
			'id' => $user_info->ID,
			'ID' => $user_info->ID,
		);

		// scope based response filter.
		if ( strpos( $token['scope'], 'openid' ) !== false || empty( $token['scope'] ) ) {
			$api_response['sub'] = $user_info->ID;
		}
		if ( strpos( $token['scope'], 'email' ) !== false || empty( $token['scope'] ) ) {
			$api_response['email'] = $user_info->user_email;
		}
		if ( strpos( $token['scope'], 'profile' ) !== false || empty( $token['scope'] ) ) {
			$profile_array = array(
				'username'     => $user_info->user_login,
				'first_name'   => $user_info->first_name,
				'last_name'    => $user_info->last_name,
				'nickname'     => $user_info->nickname,
				'display_name' => $user_info->display_name,
			);
			$api_response  = array_merge( $api_response, $profile_array );
		}

		MO_OAuth_Server_Debug::error_log( 'Resource Endpoint - User Info send successfully' );
		MO_OAuth_Server_Debug::error_log( $api_response );

		return $api_response;
	}

	/**
	 * Summary of mo_oauth_server_logged_user_from_auth_cookie
	 *
	 * Get logged in user details from auth cookie.
	 *
	 * @return bool
	 */
	public function mo_oauth_server_logged_user_from_auth_cookie() {
		if ( ! is_user_logged_in() ) {
			return false;
		}

		$auth_cookie = wp_parse_auth_cookie( '', 'logged_in' );
		if ( ! $auth_cookie || is_wp_error( $auth_cookie ) || ! $auth_cookie['token'] || ! $auth_cookie['username'] ) {
			return false;
		}
		if ( wp_get_current_user()->user_login === $auth_cookie['username'] ) {
			return $auth_cookie;
		}
		return false;
	}

	/**
	 * Summary of mo_oauth_server_get_token_user_info
	 *
	 * Gets the user data of the token.
	 *
	 * @param mixed $token to get user info using token.
	 * @return array<array>
	 */
	public function mo_oauth_server_get_token_user_info( $token = null ) {
		if ( null === $token || ! isset( $token['user_id'] ) ) {
			return array();
		}
		$user_info = get_userdata( $token['user_id'] );
		if ( null === $user_info ) {
			return array();
		}
		MO_OAuth_Server_Debug::error_log( $user_info );
		return $user_info;
	}

	/**
	 * Summary of mo_oauth_server_check_user_login
	 *
	 * Check if a user is logged in.
	 *
	 * @param string $client_id the client ID.
	 * @return mixed
	 */
	public function mo_oauth_server_check_user_login( $client_id ) {
		$current_user_cookie = $this->mo_oauth_server_logged_user_from_auth_cookie();
		if ( ! $current_user_cookie ) {
			return false;
		}
		global $wpdb;
		if ( isset( $client_id ) ) { //phpcs:ignore WordPress.Security.NonceVerification -- 
			$server_details = $wpdb->get_results( $wpdb->prepare( 'SELECT active_oauth_server_id FROM ' . $wpdb->base_prefix . 'moos_oauth_clients WHERE client_id = %s', array( sanitize_text_field( wp_unslash( $client_id ) ) ) ) ); // phpcs:ignore WordPress.Security.NonceVerification, WordPress.DB.DirectDatabaseQuery
		}

		if ( null === $server_details ) {
			wp_die( 'Your client id is invalid. Please contact to your administrator.' );
			exit();
		}

		$user                   = get_user_by( 'login', $current_user_cookie['username'] );
		$is_user_member_of_blog = is_user_member_of_blog( $user->ID, $server_details[0]->active_oauth_server_id );

		if ( false === $is_user_member_of_blog ) {
			wp_logout();
			wp_die( 'Invalid credentials. Please contact to your administrator.' );
		}

		return $user;
	}

	/**
	 * Summary of mo_oauth_server_render_consent_screen
	 *
	 * Renders the consent screen for authorization.
	 *
	 * @param mixed  $client_credentials the client credentials.
	 * @param string $scope_required the scopes required.
	 * @return void
	 */
	public function mo_oauth_server_render_consent_screen( $client_credentials, $scope_required ) {

		$authorize_dialog_template = MOSERVER_DIR . DIRECTORY_SEPARATOR . 'endpoints' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . 'authorize-dialog.php';
		$authorize_dialog_template = apply_filters( 'mo_oauth_server_authorize_dialog_template_path', $authorize_dialog_template );
		header( 'Content-Type: text/html' );
		include $authorize_dialog_template;
		if ( function_exists( 'mo_oauth_server_emit_html' ) ) {

			$scope_list    = explode( ' ', $scope_required );
			$scope_message = array();
			if ( in_array( 'openid', $scope_list, true ) ) {
				array_push( $scope_message, 'Basic Information' );
			}

			if ( in_array( 'profile', $scope_list, true ) ) {
				array_push( $scope_message, 'Basic WordPress Profile' );
			}

			if ( in_array( 'email', $scope_list, true ) ) {
				array_push( $scope_message, 'Your Email Address' );
			}

			// If the scope is empty, it means every scope is required.
			if ( count( $scope_message ) === 0 ) {
				$scope_message = array( 'Default wordpress profile', 'Your email address' );
			}

			mo_oauth_server_emit_html( $client_credentials, $scope_message );
		}
		exit();
	}

	/**
	 * Summary of mo_oauth_server_validate_authorize_consent
	 *
	 * Validates authorization consent.
	 *
	 * @return void
	 */
	public function mo_oauth_server_validate_authorize_consent() {
		if ( isset( $_REQUEST['client_id'] ) ) {
			$user = $this->mo_oauth_server_check_user_login( sanitize_text_field( wp_unslash( $_REQUEST['client_id'] ) ) );
		}
		if ( isset( $_POST['mo_oauth_server_authorize_dialog'] ) ) {
			if ( ( isset( $_POST['mo_oauth_server_authorize_dialog_allow_form_field'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_POST['mo_oauth_server_authorize_dialog_allow_form_field'] ) ), 'mo_oauth_server_authorize_dialog_allow_form' ) ) ) {
				if ( isset( $_POST['mo_oauth_server_authorize'] ) ) {
					$response = sanitize_text_field( wp_unslash( $_POST['mo_oauth_server_authorize'] ) );
					update_user_meta( $user->ID, 'mo_oauth_server_granted_' . sanitize_text_field( wp_unslash( $_REQUEST['client_id'] ) ), $response );
					$current_url    = explode( '?', $this->mo_oauth_server_get_current_page_url() )[0];
					$_GET['prompt'] = $response;
					wp_safe_redirect( $current_url . '?' . http_build_query( $_GET ) );
				}
			} elseif ( ( isset( $_POST['mo_oauth_server_authorize_dialog_deny_form_field'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_POST['mo_oauth_server_authorize_dialog_deny_form_field'] ) ), 'mo_oauth_server_authorize_dialog_deny_form' ) ) ) {
				$error_message = Miniorange_Oauth_20_Server_Oauth_Constants::DENY_AUTHORIZATION;
				$redirect_uri  = isset( $_GET['redirect_uri'] ) ? esc_url_raw( wp_unslash( $_GET['redirect_uri'] ) ) : wp_die( esc_html( $error_message ) );
				$redirect_uri .= strpos( $redirect_uri, '?' ) !== false ? '&' : '?';
				$redirect_uri .= 'error=' . urlencode( $error_message );
				wp_safe_redirect( $redirect_uri );
			}
			exit();
		}
	}

	/**
	 * Summary of mo_oauth_server_get_current_page_url
	 *
	 * Gets the URL of current page.
	 *
	 * @return string
	 */
	public function mo_oauth_server_get_current_page_url() {
		$current_page_url = ( isset( $_SERVER['HTTPS'] ) ? 'https' : 'http' ) . '://';
		if ( isset( $_SERVER['HTTP_HOST'] ) ) {
			$current_page_url .= sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) );
		}
		if ( isset( $_SERVER['REQUEST_URI'] ) ) {
			$current_page_url .= esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) );
		}
		return $current_page_url;
	}

	/**
	 * Summary of mo_oauth_server_set_allow_origin
	 *
	 * Allows the redirect URI access across origin.
	 *
	 * @param mixed $request to allow origin.
	 * @return void
	 */
	public function mo_oauth_server_set_allow_origin( $request ) {
		if ( isset( $request->request['redirect_uri'] ) ) {
			$redirect_uri = explode( '/', $request->request['redirect_uri'] );
			header( 'Access-Control-Allow-Origin:' . $redirect_uri[0] . '//' . $redirect_uri[2] );
		}
	}


}
