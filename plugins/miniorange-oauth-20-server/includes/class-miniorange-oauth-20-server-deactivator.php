<?php
/**
 * Fired during plugin deactivation
 *
 * @link       https://www.miniorange.com
 * @since      1.0.0
 *
 * @package    Miniorange_Oauth_20_Server
 * @subpackage Miniorange_Oauth_20_Server/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Miniorange_Oauth_20_Server
 * @subpackage Miniorange_Oauth_20_Server/includes
 * @author     miniOrange <info@xecurify.com>
 */
class Miniorange_Oauth_20_Server_Deactivator {

	/**
	 * Deactivates the plugin and removes all stored key-value pairs associated with it.
	 * This function deletes all the options that are set by the plugin to clean up the database and leave no traces behind after deactivating the plugin.
	 * This includes options related to OAuth server registration, customer details, security warnings, and message data.
	 *
	 * @since    1.0.0
	 */
	public function deactivate() {
		// delete all stored key-value pairs.
		delete_option( 'host_name' );
		delete_option( 'mo_oauth_server_new_registration' );
		delete_option( 'mo_oauth_server_admin_phone' );
		delete_option( 'mo_oauth_server_verify_customer' );
		delete_option( 'mo_oauth_server_admin_customer_key' );
		delete_option( 'mo_oauth_server_admin_api_key' );
		delete_option( 'mo_oauth_server_new_customer' );
		delete_option( 'mo_oauth_server_customer_token' );
		delete_option( 'message' );
		delete_option( 'mo_oauth_server_registration_status' );
		delete_option( 'mo_oauth_show_mo_server_message' );
		delete_option( 'mo_oauth_server_hide_security_warning_admin' );
		delete_option( 'mo_oauth_server_security_warning_remind_date' );
		delete_option( 'mo_oauth_server_jwks_uri_hit_count' );
		delete_option( 'mo_oauth_server_is_security_warning_mail_sent' );
	}

}
