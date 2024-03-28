<?php
/**
 * Class Miniorange_Oauth_20_Server_Enable_JWT_Support
 *
 * @package Miniorange_Oauth_20_Server
 */

/**
 * Class Miniorange_Oauth_20_Server_Enable_JWT_Support
 *
 * This class handles the addition of a new client.
 */
class Miniorange_Oauth_20_Server_Enable_JWT_Support {

	/**
	 * Utils contains some commonly used functions
	 *
	 * @var [object]
	 */
	private $utils;

	/**
	 * Constructor for MiniOrange_Oauth_20_Server_Enable_JWT_Support.
	 */
	public function __construct() {
		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/helper/class-miniorange-oauth-20-server-db.php';
		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/helper/class-miniorange-oauth-20-server-utils.php';
		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/helper/class-miniorange-oauth-20-server-customer.php';

		$this->utils = new Miniorange_Oauth_20_Server_Utils();
	}

	/**
	 * This function handles the addition of a new client.
	 */
	public function handle_enable_jwt_support() {

		// Verify the nonce.
		if ( ! isset( $_POST['mo_oauth_server_jwt_settings_form_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mo_oauth_server_jwt_settings_form_nonce'] ) ), 'mo_oauth_server_jwt_settings_form' ) ) {
			wp_die( 'Failed nonce verification.' );
		}

		if ( ! isset( $_POST['mo_oauth_server_appname'] ) ) {
			update_option( 'message', 'There was an error saving configuration, please try again.' );
			$this->utils->mo_oauth_show_success_message();
			wp_safe_redirect( 'admin.php?page=mo_oauth_server_settings&tab=config' );
		}
		$client_name = str_replace( ' ', '_', sanitize_text_field( wp_unslash( $_POST['mo_oauth_server_appname'] ) ) );
		if ( ! isset( $_POST[ "mo_server_enable_jwt_support_for_$client_name" ] ) ) {
			update_option( 'mo_oauth_server_enable_jwt_support_for_' . $client_name, 'off' );
		} else {
			update_option( 'mo_oauth_server_enable_jwt_support_for_' . $client_name, 'on' );
		}

		if ( isset( $_POST[ 'mo_oauth_server_jwt_signing_algo_for_' . $client_name ] ) ) {

			$is_changed       = false;
			$previous_setting = get_option( 'mo_oauth_server_jwt_signing_algo_for_' . $client_name ) ? get_option( 'mo_oauth_server_jwt_signing_algo_for_' . $client_name ) : false;
			if ( $previous_setting !== $_POST[ 'mo_oauth_server_jwt_signing_algo_for_' . $client_name ] ) {
				update_option( 'mo_oauth_server_jwt_signing_algo_for_' . $client_name, sanitize_text_field( wp_unslash( $_POST[ 'mo_oauth_server_jwt_signing_algo_for_' . $client_name ] ) ) );
				$current_config = sanitize_text_field( wp_unslash( $_POST[ 'mo_oauth_server_jwt_signing_algo_for_' . $client_name ] ) );
				$algo           = explode( 'S', $current_config );
				$sha            = $algo[1];
				global $wpdb;
				$myrows        = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->base_prefix . 'moos_oauth_clients WHERE client_name = %s and active_oauth_server_id = %d', sanitize_text_field( wp_unslash( $_POST['mo_oauth_server_appname'] ) ), get_current_blog_id() ) ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery
				$client_exists = $wpdb->query( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->base_prefix . 'moos_oauth_public_keys WHERE client_id = %s', $myrows[0]->client_id ) ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery
				if ( ! $client_exists ) {
					$result = $wpdb->query( $wpdb->prepare( 'INSERT INTO ' . $wpdb->base_prefix . "moos_oauth_public_keys (client_id, public_key, private_key, encryption_algorithm) VALUES (%s, '', '', 'RS256')", $myrows[0]->client_id ) ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery
				}
				if ( 'R' === $algo[0] ) {

					$private_key = file_get_contents( dirname( dirname( dirname( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . '/admin/secrets/private_key.pem' ); //phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Using file_get_contents to fetch a local file, not a remote file.

					$public_key = file_get_contents( dirname( dirname( dirname( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . '/admin/secrets/public_key.pem' ); //phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Using file_get_contents to fetch a local file, not a remote file.

					$result = $wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->base_prefix . 'moos_oauth_public_keys SET public_key = %s, private_key = %s, encryption_algorithm = %s WHERE client_id = %s', $public_key, $private_key, $current_config, $myrows[0]->client_id ) ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery
				} else {
					$result = $wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->base_prefix . "moos_oauth_public_keys SET public_key = '', private_key = %s, encryption_algorithm = %s WHERE client_id = %s", $myrows[0]->client_secret, $current_config, $myrows[0]->client_id ) ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery
				}
			}
		}

		update_option( 'message', 'Your settings are saved successfully.' );
		$this->utils->mo_oauth_show_success_message();
		wp_safe_redirect( 'admin.php?page=mo_oauth_server_settings&tab=config&action=update&client=' . str_replace( '_', '+', $client_name ) );

	}

}
