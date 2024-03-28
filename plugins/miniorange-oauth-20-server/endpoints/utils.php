<?php
/**
 * Summary of registry
 *
 * @package Registry
 */

/**
 * Just to fix PHPCS.
 */

use OAuth2\Server as OAuth2Server;
use OAuth2\Storage\MoPdo;
use OAuth2\OpenID\GrantType\AuthorizationCode;
use OAuth2\GrantType\UserCredentials;
use OAuth2\GrantType\RefreshToken;

/**
 * Summary of mo_oauth_server_init
 *
 * @return OAuth2Server
 */
function mo_oauth_server_init() {
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
