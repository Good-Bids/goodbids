<?php
/**
 * Class Miniorange_Oauth_20_Server_Add_Client
 *
 * @package Miniorange_Oauth_20_Server
 */

/**
 * Class Miniorange_Oauth_20_Server_Add_Client
 *
 * This class handles the addition of a new client.
 */
class Miniorange_Oauth_20_Server_Add_Client {

	/**
	 * Utils contains some commonly used functions
	 *
	 * @var [object]
	 */
	private $utils;

	/**
	 * Constructor for Miniorange_Oauth_20_Server_Add_Client.
	 */
	public function __construct() {
		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/helper/class-miniorange-oauth-20-server-db.php';
		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/helper/class-miniorange-oauth-20-server-utils.php';

		$this->utils = new Miniorange_Oauth_20_Server_Utils();
	}

	/**
	 * This function handles the addition of a new client.
	 *
	 * @param string $client_name The name of the client.
	 * @param string $redirect_uri The redirect uri of the client.
	 */
	public function handle_add_client( $client_name, $redirect_uri ) {

		$mo_oauth_server_db         = new Mo_Oauth_Server_Db();
		$clientlist                 = $mo_oauth_server_db->get_clients();
		$is_client_secret_encrypted = 1;
		update_option( 'mo_oauth_server_is_client_secret_encrypted', $is_client_secret_encrypted, false );
		$client_secret = $this->utils->mo_oauth_server_encrypt( $this->utils->moos_generate_random_string( 32 ), $client_name );

		$active_oauth_server_id = get_current_blog_id();

		$jwt_signing_algorithm = 'RS256';
		$private_key           = file_get_contents( dirname( dirname( dirname( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . '/admin/secrets/private_key.pem' ); //phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Using file_get_contents to fetch a local file, not a remote file.

		$public_key = file_get_contents( dirname( dirname( dirname( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . '/admin/secrets/public_key.pem' ); //phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Using file_get_contents to fetch a local file, not a remote file.

		$mo_oauth_server_db->add_client( $client_name, $client_secret, $redirect_uri, $active_oauth_server_id, $jwt_signing_algorithm, $private_key, $public_key );
	}
}
