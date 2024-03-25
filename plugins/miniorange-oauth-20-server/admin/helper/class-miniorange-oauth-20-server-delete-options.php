<?php
/**
 * Class Miniorange_Oauth_20_Server_Delete_Options
 *
 * @package Miniorange_Oauth_20_Server
 */

/**
 * Class Miniorange_Oauth_20_Server_Delete_Options
 *
 * This class handles the deletion of options.
 */
class Miniorange_Oauth_20_Server_Delete_Options {

	/**
	 * Function to delete options from wp_options table.
	 */
	public function delete_options() {
		delete_option( 'mo_oauth_admin_email' );
		delete_option( 'mo_oauth_server_admin_phone' );
		delete_option( 'mo_oauth_server_verify_customer' );
		delete_option( 'mo_oauth_server_admin_customer_key' );
		delete_option( 'mo_oauth_server_admin_api_key' );
		delete_option( 'mo_oauth_server_customer_token' );
		delete_option( 'mo_oauth_server_new_customer' );
		delete_option( 'message' );
		delete_option( 'mo_oauth_server_new_registration' );
		delete_option( 'mo_oauth_server_registration_status' );
		delete_option( 'mo_oauth_show_mo_server_message' );
	}
}
