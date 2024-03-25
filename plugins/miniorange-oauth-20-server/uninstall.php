<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://www.miniorange.com
 * @since      1.0.0
 *
 * @package    Miniorange_Oauth_20_Server
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'host_name' );
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