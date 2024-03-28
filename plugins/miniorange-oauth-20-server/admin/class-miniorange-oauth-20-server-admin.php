<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.miniorange.com
 * @since      1.0.0
 *
 * @package    Miniorange_Oauth_20_Server
 * @subpackage Miniorange_Oauth_20_Server/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Miniorange_Oauth_20_Server
 * @subpackage Miniorange_Oauth_20_Server/admin
 * @author     miniOrange <info@xecurify.com>
 */
class Miniorange_Oauth_20_Server_Admin {


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
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		$current_page = filter_input( INPUT_GET, 'page', FILTER_UNSAFE_RAW );

		if ( ! empty( $current_page ) && 'mo_oauth_server_settings' === $current_page ) {
			wp_enqueue_style( 'font-awesome', plugin_dir_url( __FILE__ ) . 'css/all.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'miniorange-oauth-20-server-bulma-css.css', plugin_dir_url( __FILE__ ) . 'css/bulma.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'miniorange-oauth-20-server-bulma-pricingtable.css', plugin_dir_url( __FILE__ ) . 'css/bulma-pricingtable.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'miniorange-oauth-20-server-bulma-switch.css', plugin_dir_url( __FILE__ ) . 'css/bulma-switch.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'miniorange-oauth-20-server-bulma-tooltip.css', plugin_dir_url( __FILE__ ) . 'css/bulma-tooltip.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'miniorange-oauth-20-server-intl-tel-input', plugin_dir_url( __FILE__ ) . 'css/intl-tel-input.css', array(), $this->version, 'all' );

			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/miniorange-oauth-20-server-admin.css', array(), $this->version, 'all' );
		}
		wp_enqueue_style( 'mo_oauth_server_admin_security_notice', plugin_dir_url( __FILE__ ) . 'css/security_notice.css', array(), $this->version, $in_footer = false );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$current_page = filter_input( INPUT_GET, 'page', FILTER_UNSAFE_RAW );

		if ( ! empty( $current_page ) && 'mo_oauth_server_settings' === $current_page ) {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/miniorange-oauth-20-server-admin.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'miniorange-oauth-20-server-intl-tel-input', plugin_dir_url( __FILE__ ) . 'js/intl-tel-input.js', array( 'jquery' ), $this->version, false );
		}
	}

	/**
	 * Summary of miniorange_menu
	 *
	 * Adds the miniOrange OAuth Server Menu in the WP Admin Menu.
	 *
	 * @return void
	 */
	public function mo_oauth_server_admin_menu() {
		// Add miniOrange plugin to the menu.
		$page = add_menu_page( 'MO OAuth Settings ' . __( 'Configure OAuth', 'mo_oauth_server_settings' ), 'miniOrange OAuth Server', 'administrator', 'mo_oauth_server_settings', array( $this, 'mo_oauth_render_view' ), MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_URL . 'assets/logo/miniorange.png' );
	}

	/**
	 * Summary of mo_oauth_render_view
	 *
	 * Renders the miniOrange OAuth Server Settings Page.
	 *
	 * @return void
	 */
	public function mo_oauth_render_view() {

		// Check the value of the 'tab' parameter.
		$tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : ''; // phpcs:ignore

		// Show any security messages.
		$this->mo_oauth_show_security_message_main_page();

		// main screen.
		if ( '' === $tab ) {
			// parse the json file.
			$oauth_client_list_json_file_path = MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_URL . 'admin/oauth-client-list.json';

			$oauth_client_list_json = file_get_contents( dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . '/admin/oauth-client-list.json' ); //phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Using file_get_contents to fetch a local file, not a remote file.

			$oauth_client_list_json_data = json_decode( $oauth_client_list_json, true );

			// get the choosen client from the database.
			$choosen_client = get_option( 'mo_oauth_server_client' ) ? get_option( 'mo_oauth_server_client' ) : '';
			// fetch json based on choosen client.
			if ( $choosen_client ) {
				$client_settings = $oauth_client_list_json_data[ $choosen_client ];

				$additional_settings = '';
				if ( isset( $client_settings['additional_settings'] ) ) {
					$additional_settings = $client_settings['additional_settings'];
				}
			}

			require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/views/miniorange-oauth-20-server-settings-main-page.php';
			return;
		}

		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/views/miniorange-oauth-20-server-settings.php';

		// Include the appropriate PHP view file based on the 'tab' value.
		switch ( $tab ) {
			case 'config':
				$this->mo_oauth_server_handle_config_page();
				break;
			case 'advance_settings':
				$this->mo_oauth_server_handle_advance_settings_page();
				break;
			case 'premium_features':
				require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/views/miniorange-oauth-20-server-settings-premium-features.php';
				break;
			case 'server_response':
				require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/views/miniorange-oauth-20-server-settings-server-response.php';
				break;
			case 'contact_us':
				require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/views/miniorange-oauth-20-server-settings-contact-us.php';
				break;
			case 'troubleshooting':
				$this->mo_oauth_server_handle_troubleshooting_page();
				break;
			case 'account_setup':
				$this->mo_oauth_server_handle_account_setup_page_view();
				break;
			case 'login_existing_user':
				require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/views/miniorange-oauth-20-server-settings-account-setup-login.php';
				break;
			case 'integrations':
				$this->mo_oauth_render_view_integrations_page();
				break;
			case 'licensing_tab':
				require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/views/miniorange-oauth-20-server-settings-licensing.php';
				break;
			case 'trials_available':
				require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/views/miniorange-oauth-20-server-settings-request-for-demo.php';
				break;
			default:
				require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/views/miniorange-oauth-20-server-settings-contact-us.php';
				break;
		}
	}

	/**
	 * Summary of mo_oauth_server_handle_config_page
	 *
	 * Saves plugin settings, it is hooked on 'admin_init' action.
	 *
	 * @return void
	 */
	public function mo_oauth_server_admin_init_save_settings() {
		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/class-miniorange-oauth-20-server-save-settings.php';

		$save_settings_helper = new Miniorange_Oauth_20_Server_Save_Settings();
		$save_settings_helper->miniorange_oauth_save_settings();
	}

	/**
	 * Summary of mo_oauth_server_autoloader
	 *
	 * Autoloader function for loading all helper classes.
	 *
	 * @param mixed $class_name Name of the class to be loaded.
	 * @return void
	 */
	public function mo_oauth_server_autoloader( $class_name ) {
		$class_file = plugin_dir_path( __FILE__ ) . 'helper/class-' . str_replace( '_', '-', str_replace( '\\', '/', $class_name ) ) . '.php';
		if ( file_exists( $class_file ) ) {
			require_once $class_file;
		}
	}

	/**
	 * Function to delete debug log file.
	 *
	 * @return void
	 */
	public function mo_oauth_server_debug_delete_log() {
		// delete debug log file.
		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/helper/class-miniorange-oauth-20-server-log-delete.php';
		$log_handler = new Miniorange_Oauth_20_Server_Log_Delete();
		$log_handler->mo_oauth_delete_debug_log_file();
	}

	/**
	 * Function to download debug log file.
	 *
	 * @return void
	 */
	public function mo_oauth_server_handle_account_setup_page_view() {
		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/helper/class-miniorange-oauth-20-server-customer-handler.php';
		$customer_registered = new Miniorange_Oauth_20_Server_Customer_Handler();

		if (
			'MO_OAUTH_REGISTRATION_COMPLETE' ==
			get_option( 'mo_oauth_server_registration_status' ) ||
			'MO_OAUTH_CUSTOMER_RETRIEVED' ==
			get_option( 'mo_oauth_server_registration_status' ) ||
			boolval( $customer_registered->mo_oauth_server_is_customer_registered() )
		) {
			require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/views/miniorange-oauth-20-server-settings-account-setup-account-page.php';
			return;
		}

		$status              = get_option( 'mo_oauth_server_registration_status' );
		$customer_registered = boolval( $customer_registered->mo_oauth_server_is_customer_registered() );
		$email               = trim( get_option( 'mo_oauth_admin_email' ) );
		$api_key             = trim( get_option( 'mo_oauth_server_admin_api_key' ) );
		$new_registration    = get_option( 'mo_oauth_server_new_registration' );

		if ( 'MO_OAUTH_REGISTRATION_COMPLETE' === $status || 'MO_OAUTH_CUSTOMER_RETRIEVED' === $status || $customer_registered ) {
			require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/views/miniorange-oauth-20-server-settings-account-setup-account-page.php';
		} elseif ( get_option( 'mo_oauth_server_verify_customer' ) === 'true' ) {
			mo_oauth_server_show_verify_password_page();
		} elseif ( '' !== $email && '' === $api_key && 'true' !== $new_registration ) {
			require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/views/miniorange-oauth-20-server-settings-account-setup.php';
		} else {
			delete_option( 'password_mismatch' );
			require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/views/miniorange-oauth-20-server-settings-account-setup.php';
		}
	}

	/**
	 * Function to handle the config page view.
	 *
	 * @return void
	 */
	public function mo_oauth_server_handle_config_page() {
		// get info about the choosen client and display accordingly.
		// parse the json file.
		$oauth_client_list_json_file_path = MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_URL . 'admin/oauth-client-list.json';

		$oauth_client_list_json = file_get_contents( dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . '/admin/oauth-client-list.json' ); //phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Using file_get_contents to fetch a local file, not a remote file.

		$oauth_client_list_json_data = json_decode( $oauth_client_list_json, true );

		// get the choosen client from the database.
		$choosen_client = get_option( 'mo_oauth_server_client' );
		// fetch json based on choosen client.
		$client_settings = '';
		if ( $choosen_client ) {
			$client_settings = $oauth_client_list_json_data[ $choosen_client ];
		} else {
			$client_settings = $oauth_client_list_json_data['wordpress'];
		}

		$additional_settings = '';
		if ( isset( $client_settings['additional_settings'] ) ) {
			$additional_settings = $client_settings['additional_settings'];
		}

		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/helper/class-miniorange-oauth-20-server-db.php';
		$mo_oauth_server_db       = new Mo_Oauth_Server_Db();
		$clientlist               = $mo_oauth_server_db->get_clients();
		$no_of_configured_clients = count( $clientlist );
		if ( 1 === $no_of_configured_clients ) {
			require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/helper/class-miniorange-oauth-20-server-utils.php';
			$mo_utils                  = new Miniorange_Oauth_20_Server_Utils();
			$home_url_plus_rest_prefix = $mo_utils->get_home_url_with_permalink_structure();

			$client = $clientlist[0];

			require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/helper/class-miniorange-oauth-20-server-utils.php';
			$mo_utils      = new Miniorange_Oauth_20_Server_Utils();
			$client_secret = $mo_utils->mo_oauth_server_decrypt( esc_attr( $client->client_secret ), esc_attr( $client->client_name ) );
			$client_name   = $client->client_name;
			$client_id     = $client->client_id;

			$sub        = str_replace( ' ', '_', $client->client_name );
			$jwt_switch = get_option( "mo_oauth_server_enable_jwt_support_for_$sub" );
			if ( 'on' === $jwt_switch ) {
				$jwt_switch = 'checked';
			} else {
				$jwt_switch = '';
			}

			$jwt_signing_algo = get_option( 'mo_oauth_server_jwt_signing_algo_for_' . $sub ) ? get_option( 'mo_oauth_server_jwt_signing_algo_for_' . $sub ) : 'RS256';

			require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/views/miniorange-oauth-20-server-settings-config-configured.php';
		} else {

			$choosen_client = get_option( 'mo_oauth_server_client' );
			if ( $choosen_client ) {
				require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/views/miniorange-oauth-20-server-settings-config-unconfigured.php';
			} else {
				require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/views/miniorange-oauth-20-server-settings-welcome-page.php';
			}
		}
	}

	/**
	 * Function to handle the general settings page view.
	 *
	 * @return void
	 */
	public function mo_oauth_server_handle_advance_settings_page() {
		$master_switch = get_option( 'mo_oauth_server_master_switch' );
		if ( 'off' === $master_switch ) {
			$master_switch = '';
		} else {
			$master_switch = 'checked';
		}

		$open_id_switch = get_option( 'mo_oauth_server_enable_oidc' );
		if ( 'off' === $open_id_switch ) {
			$open_id_switch = '';
		} else {
			$open_id_switch = 'checked';
		}
		$state_parameter_switch = get_option( 'mo_oauth_server_enforce_state' );
		if ( 'on' === $state_parameter_switch ) {
			$state_parameter_switch = 'checked';
		} else {
			$state_parameter_switch = '';
		}

		$custom_url = get_option( 'mo_oauth_server_custom_login_url' );

		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/views/miniorange-oauth-20-server-settings-advance-settings.php';
	}

	/**
	 * Function to handle the troubleshooting page view.
	 *
	 * @return void
	 */
	public function mo_oauth_server_handle_troubleshooting_page() {
		$debug_log_button = get_option( 'mo_oauth_server_is_debug_enabled' );
		if ( '1' === $debug_log_button ) {
			$debug_log_button = 'checked';
		} else {
			$debug_log_button = '';
		}
		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/views/miniorange-oauth-20-server-settings-troubleshooting.php';
	}

	/**
	 * Function to handle the integrations page view.
	 */
	public function mo_oauth_render_view_integrations_page() {
		// parse the json file.
		$oauth_client_list_json_file_path = MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/oauth-client-list.json';

		$oauth_client_list_json = file_get_contents( dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . '/admin/oauth-client-list.json' ); //phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Using file_get_contents to fetch a local file, not a remote file.

		$oauth_client_list_json_data = json_decode( $oauth_client_list_json, true );
		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/views/miniorange-oauth-20-server-settings-integrations.php';
	}

	/**
	 * Summary of mo_oauth_server_display_feedback_form
	 *
	 * Displayes the feedback form on plugin deactivation.
	 *
	 * @return void
	 */
	public function mo_oauth_server_feedback_form() {
		if ( isset( $_SERVER['PHP_SELF'] ) && 'plugins.php' !== basename( sanitize_text_field( wp_unslash( $_SERVER['PHP_SELF'] ) ) ) ) {
			return;
		}

		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'wp-pointer' );
		wp_enqueue_script( 'utils' );

		$email = get_option( 'admin_email' );
		if ( empty( $email ) ) {
			$user  = wp_get_current_user();
			$email = $user->user_email;
		}

		$imagepath = plugins_url( 'images/', __FILE__ );

		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'wp-pointer' );
		wp_enqueue_script( 'utils' );

		// wp_enqueue_style( 'mo_oauth_server_css', MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_URL . 'admin/css/mo-oauth-server-feedback.css', array(), $this->version, 'all' );

		include MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/views/miniorange-oauth-20-server-feedback-form.php';
	}

	/**
	 * Function to handle the security warning message.
	 *
	 * @return void
	 */
	public function mo_oauth_server_security_warning_message() {
		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/views/miniorange-oauth-20-server-security-warning-message.php';
		mo_oauth_server_security_warning_message_view();
	}

	/**
	 * Function to handle the security warning message.
	 *
	 * @return void
	 */
	public function mo_oauth_show_security_message_main_page() {
		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/views/miniorange-oauth-20-server-security-warning-message-main-page.php';
		mo_oauth_server_security_warning_message_view_main_page();
	}

}

