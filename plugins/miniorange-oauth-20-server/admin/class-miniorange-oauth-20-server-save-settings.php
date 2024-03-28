<?php
/**
 * Miniorange_Oauth_20_Server_Save_Settings
 *
 * @package Miniorange_Oauth_20_Server_Save_Settings
 */

/**
 * Class Miniorange_Oauth_20_Server_Save_Settings
 *
 * This class handles the saving of settings.
 */
class Miniorange_Oauth_20_Server_Save_Settings {

	/**
	 * Utils contains some commonly used functions
	 *
	 * @var [object]
	 */
	private $utils;

	/**
	 * Constructor for Miniorange_Oauth_20_Server_Save_Settings.
	 */
	public function __construct() {
		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/helper/class-miniorange-oauth-20-server-delete-options.php';
		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/helper/class-miniorange-oauth-20-server-customer.php';
		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/helper/class-miniorange-oauth-20-server-customer-handler.php';
		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/helper/class-miniorange-oauth-20-server-add-client.php';
		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/helper/class-miniorange-oauth-20-server-enable-jwt-support.php';
		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/helper/class-miniorange-oauth-20-server-db.php';
		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/helper/class-miniorange-oauth-20-server-contact-us.php';
		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/helper/class-miniorange-oauth-20-server-demo-request.php';
		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/helper/class-miniorange-oauth-20-server-postman-collection-download.php';
		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/helper/class-miniorange-oauth-20-server-log-delete.php';
		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/helper/class-miniorange-oauth-20-server-log-download.php';

		$this->utils = new Miniorange_Oauth_20_Server_Utils();
	}

	/**
	 * This function handles the saving of settings.
	 */
	public function miniorange_oauth_save_settings() {

		if ( ! current_user_can( 'administrator' ) ) {
			return;
		}

		if ( isset( $_POST['selected-client'] ) ) {
			// update the WordPress db.
			$selected_client = sanitize_text_field( wp_unslash( $_POST['selected-client'] ) );
			update_option( 'mo_oauth_server_client', $selected_client );

			// todo error handling if fake client is inserted.
			wp_safe_redirect( admin_url( '/admin.php?page=mo_oauth_server_settings&tab=config' ), 301 );

		}

		if ( isset( $_POST['mo_oauth_server_add_new_client_form_nonce'] ) ) {

			// Verify the nonce.
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mo_oauth_server_add_new_client_form_nonce'] ) ), 'mo_oauth_server_add_new_client_form' ) ) {
				wp_die( 'Invalid nonce detected.' );
			}

			if ( isset( $_POST['client_name'] ) && ! empty( $_POST['client_name'] ) ) {
				$client_name = sanitize_text_field( wp_unslash( $_POST['client_name'] ) );
			} else {
				// handle error, client_name not provided.
				update_option( 'message', 'Client name is empty, please provide a client name.', false );
				$this->utils->mo_oauth_show_error_message();
				return;
			}

			if ( isset( $_POST['redirect_uri'] ) && ! empty( $_POST['redirect_uri'] ) ) {
				$redirect_uri = sanitize_text_field( wp_unslash( $_POST['redirect_uri'] ) );
			} else {
				// handle error, redirect_uri not provided.
				$redirect_uri = '';
			}

			$add_client = new Miniorange_Oauth_20_Server_Add_Client();
			$add_client->handle_add_client( $client_name, $redirect_uri );
		}

		// Delete and Update client app.
		if ( isset( $_POST['mo_oauth_server_client_update_delete_action_nonce'] ) ) {
			// Verify the nonce.
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mo_oauth_server_client_update_delete_action_nonce'] ) ), 'mo_oauth_server_client_update_delete_action' ) ) {
				wp_die( 'Invalid nonce detected.' );
			}

			if ( isset( $_POST['client_name'] ) && ! empty( $_POST['client_name'] ) ) {
				$client_name = sanitize_text_field( wp_unslash( $_POST['client_name'] ) );
			} else {
				// handle error, client_name not provided.
				update_option( 'message', 'Client name is empty, please provide a client name.', false );
				$this->utils->mo_oauth_show_error_message();
			}

			if ( isset( $_POST['redirect_uri'] ) && ! empty( $_POST['redirect_uri'] ) ) {
				$redirect_uri = sanitize_text_field( wp_unslash( $_POST['redirect_uri'] ) );
			} else {
				// handle error, redirect_uri not provided.
				$redirect_uri = '';
			}

			if ( isset( $_POST['client_id'] ) && ! empty( $_POST['client_id'] ) ) {
				$clientid = sanitize_text_field( wp_unslash( $_POST['client_id'] ) );
			} else {
				// handle error, client_id not provided.
				update_option( 'message', 'Error occured (WP OAuth server): Client id is empty', false );
				$this->utils->mo_oauth_show_error_message();
			}

			// Handle delete client app.
			if ( isset( $_POST['delete_client_button'] ) && ! empty( $_POST['delete_client_button'] ) && 'delete_client_app' === $_POST['delete_client_button'] ) {
				$mo_oauth_server_db = new Mo_Oauth_Server_Db();
				$clientlist         = $mo_oauth_server_db->delete_client( $client_name, $clientid );

				// remove the choosen client.
				delete_option( 'mo_oauth_server_client' );

				wp_safe_redirect( 'admin.php?page=mo_oauth_server_settings' );
			}

			// Handle update client app.
			if ( isset( $_POST['update_client_button'] ) && ! empty( $_POST['update_client_button'] ) && 'update_client_app' === $_POST['update_client_button'] ) {
				$mo_oauth_server_db = new Mo_Oauth_Server_Db();
				$clientlist         = $mo_oauth_server_db->update_client( $client_name, $redirect_uri );
			}
		}

		// Handle form submission for the general settings page of the plugin.
		if ( isset( $_POST['mo_oauth_server_settings_form_nonce'] ) ) {

			// Verify the nonce.
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mo_oauth_server_settings_form_nonce'] ) ), 'mo_oauth_server_settings_form' ) ) {
				wp_die( 'Invalid nonce detected.' );
			}

			// Handle post request for the received post parameters.

			if ( isset( $_POST['mo_oauth_server_master_switch'] ) && sanitize_text_field( wp_unslash( $_POST['mo_oauth_server_master_switch'] ) ) === 'on' ) {
				update_option( 'mo_oauth_server_master_switch', 'on', false );
			} else {
				update_option( 'mo_oauth_server_master_switch', 'off', false );
			}

			if ( isset( $_POST['mo_oauth_server_custom_login_url'] ) && ! empty( $_POST['mo_oauth_server_custom_login_url'] ) ) {
				$custom_login_url = sanitize_text_field( wp_unslash( $_POST['mo_oauth_server_custom_login_url'] ) );
				update_option( 'mo_oauth_server_custom_login_url', $custom_login_url, false );
			} else {
				update_option( 'mo_oauth_server_custom_login_url', '', false );
			}

			if ( isset( $_POST['mo_oauth_server_openid_connect'] ) && sanitize_text_field( wp_unslash( $_POST['mo_oauth_server_openid_connect'] ) ) === 'on' ) {
				update_option( 'mo_oauth_server_enable_oidc', 'on', false );
			} else {
				update_option( 'mo_oauth_server_enable_oidc', 'off', false );
			}

			if ( isset( $_POST['mo_oauth_server_state_parameter'] ) && sanitize_text_field( wp_unslash( $_POST['mo_oauth_server_state_parameter'] ) ) === 'on' ) {
				update_option( 'mo_oauth_server_enforce_state', 'on', false );
			} else {
				update_option( 'mo_oauth_server_enforce_state', 'off', false );
			}

			update_option( 'message', 'Settings saved successfully.', false );
			$this->utils->mo_oauth_show_success_message();
		}

		// Contact Us form handler.
		if ( isset( $_POST['mo_oauth_server_contact_us_nonce'] ) ) {

			// Verify the nonce.
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mo_oauth_server_contact_us_nonce'] ) ), 'mo_oauth_server_contact_us_form' ) ) {
				wp_die( 'Invalid nonce detected.' );
			}

			$email       = isset( $_POST['mo_oauth_contact_us_email'] ) ? sanitize_email( wp_unslash( $_POST['mo_oauth_contact_us_email'] ) ) : '';
			$phone       = isset( $_POST['mo_oauth_contact_us_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['mo_oauth_contact_us_phone'] ) ) : '';
			$query       = isset( $_POST['mo_oauth_contact_us_query'] ) ? sanitize_text_field( wp_unslash( $_POST['mo_oauth_contact_us_query'] ) ) : '';
			$no_of_users = isset( $_POST['mo_oauth_no_of_users'] ) ? sanitize_text_field( wp_unslash( $_POST['mo_oauth_no_of_users'] ) ) : '';

			$contact_us = new Miniorange_Oauth_20_Server_Contact_Us();
			$contact_us->handle_contact_us( $email, $phone, $query, $no_of_users );

		}

		// Contact Us form for main dashboard.
		if ( isset( $_POST['mo_oauth_server_contact_us_form_dashboard_nonce'] ) ) {

			// Verify the nonce.
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mo_oauth_server_contact_us_form_dashboard_nonce'] ) ), 'mo_oauth_server_contact_us_form_dashboard' ) ) {
				wp_die( 'Invalid nonce detected.' );
			}

			$email     = isset( $_POST['mo_oauth_contact_us_email'] ) ? sanitize_email( wp_unslash( $_POST['mo_oauth_contact_us_email'] ) ) : '';
			$phone     = isset( $_POST['mo_oauth_contact_us_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['mo_oauth_contact_us_phone'] ) ) : '';
			$query     = isset( $_POST['mo_oauth_contact_us_query'] ) ? sanitize_text_field( wp_unslash( $_POST['mo_oauth_contact_us_query'] ) ) : '';
			$plan_name = isset( $_POST['mo_idp_upgrade_plan_name'] ) ? sanitize_text_field( wp_unslash( $_POST['mo_idp_upgrade_plan_name'] ) ) : '';

			$contact_us = new Miniorange_Oauth_20_Server_Contact_Us();
			$contact_us->handle_contact_us( $email, $phone, $query, $plan_name );

		}

		// Form handler for the account regestration form.
		if ( isset( $_POST['mo_oauth_server_register_customer_nonce'] ) ) {
			// verify the nonce.
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mo_oauth_server_register_customer_nonce'] ) ), 'mo_oauth_server_register_customer' ) ) {
				wp_die( 'Invalid nonce detected.' );
			}

			$customer_registration = new Miniorange_Oauth_20_Server_Customer_Handler();
			$customer_registration->handle_customer_registration();

		}

		// Change account form handler.
		if ( isset( $_POST['mo_oauth_server_change_account_nonce'] ) ) {

			// verify the nonce.
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mo_oauth_server_change_account_nonce'] ) ), 'mo_oauth_server_change_account' ) ) {
				wp_die( 'Invalid nonce detected.' );
			}

			// Delete all the options that are stored in the database for the current account.
			$delete_options = new Miniorange_Oauth_20_Server_Delete_Options();
			$delete_options->delete_options();

			return;
		}

		// Form handler for the account verification.
		if ( isset( $_POST['mo_oauth_server_account_verification_nonce'] ) ) {
			// verify the nonce.
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mo_oauth_server_account_verification_nonce'] ) ), 'mo_oauth_server_account_verification' ) ) {
				wp_die( 'Invalid nonce detected.' );
			}

			$email    = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
			$password = isset( $_POST['password'] ) ? $_POST['password'] : ''; //phpcs:ignore -- Not sanitizing and unslashing password

			$customer_verification = new Miniorange_Oauth_20_Server_Customer_Handler();
			$customer_verification->handle_customer_verification( $email, $password );

			wp_safe_redirect( admin_url( 'admin.php?page=mo_oauth_server_settings&tab=account_setup' ) );
		}

		// Form handler for trial, demo request.
		if ( isset( $_POST['mo_oauth_server_trial_demo_nonce'] ) ) {

			// Verify the nonce.
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mo_oauth_server_trial_demo_nonce'] ) ), 'mo_oauth_server_trial_demo_form' ) ) {
				wp_die( 'Invalid nonce detected.' );
			}

			$email     = isset( $_POST['mo_auto_create_demosite_email'] ) ? sanitize_email( wp_unslash( $_POST['mo_auto_create_demosite_email'] ) ) : '';
			$demo_plan = isset( $_POST['mo_auto_create_demosite_demo_plan'] ) ? sanitize_text_field( wp_unslash( $_POST['mo_auto_create_demosite_demo_plan'] ) ) : '';
			$query     = isset( $_POST['mo_auto_create_demosite_usecase'] ) ? sanitize_textarea_field( wp_unslash( $_POST['mo_auto_create_demosite_usecase'] ) ) : '';

			$demo_request = new Miniorange_Oauth_20_Server_Demo_Request();
			$demo_request->handle_demo_request( $email, $demo_plan, $query );

		}

		// Form handler for trial, video demo request.
		if ( isset( $_POST['mo_oauth_server_trial_video_demo_nonce'] ) ) {

			// Verify the nonce.
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mo_oauth_server_trial_video_demo_nonce'] ) ), 'mo_oauth_server_trial_video_demo' ) ) {
				wp_die( 'Invalid nonce detected.' );
			}

			$email     = isset( $_POST['mo_oauth_video_demo_email'] ) ? sanitize_email( wp_unslash( $_POST['mo_oauth_video_demo_email'] ) ) : '';
			$query     = isset( $_POST['mo_oauth_video_demo_request_usecase_text'] ) ? sanitize_text_field( wp_unslash( $_POST['mo_oauth_video_demo_request_usecase_text'] ) ) : '';
			$call_date = isset( $_POST['mo_oauth_video_demo_request_date'] ) ? sanitize_text_field( wp_unslash( $_POST['mo_oauth_video_demo_request_date'] ) ) : '';
			$time_diff = isset( $_POST['mo_oauth_video_demo_time_diff'] ) ? sanitize_text_field( wp_unslash( $_POST['mo_oauth_video_demo_time_diff'] ) ) : '';    // timezone offset.
			$call_time = isset( $_POST['mo_oauth_video_demo_request_time'] ) ? sanitize_text_field( wp_unslash( $_POST['mo_oauth_video_demo_request_time'] ) ) : ''; // time input.

			$demo_request = new Miniorange_Oauth_20_Server_Demo_Request();
			$demo_request->handle_video_demo( $email, $query, $call_date, $time_diff, $call_time );

		}

		// Form handler for jwt settings.
		if ( isset( $_POST['mo_oauth_server_jwt_settings_form_nonce'] ) ) {

			// Verify the nonce.
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mo_oauth_server_jwt_settings_form_nonce'] ) ), 'mo_oauth_server_jwt_settings_form' ) ) {
				wp_die( 'Invalid nonce detected.' );
			}

			$enable_jwt_support = new Miniorange_Oauth_20_Server_Enable_JWT_Support();
			$enable_jwt_support->handle_enable_jwt_support();
		}

		// Form handler for jwt signing certificate download.
		if ( isset( $_POST['mo_oauth_server_jwt_signing_cert_download_form_nonce'] ) ) {

			// Verify the nonce.
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mo_oauth_server_jwt_signing_cert_download_form_nonce'] ) ), 'mo_oauth_server_jwt_signing_cert_download_form' ) ) {
				wp_die( 'Invalid nonce detected.' );
			}

			global $wpdb;
			$client_id = isset( $_REQUEST['client'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['client'] ) ) : false;
			if ( false === $client_id ) {
				wp_die( 'Invalid Client.' );
			}
			$public_key = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->base_prefix . 'moos_oauth_public_keys where client_id = %s', $client_id ), ARRAY_A )['public_key']; //phpcs:ignore WordPress.DB.DirectDatabaseQuery
			header( 'Content-Disposition: attachment; filename="pubKey.pem"' );
			header( 'Content-Type: text/plain' );
			header( 'Content-Length: ' . strlen( $public_key ) );
			header( 'Connection: close' );

			echo esc_attr( $public_key );
			exit();
		}

		// Form handler for postman collection download.
		if ( isset( $_POST['mo_oauth_server_postman_collection_form_nonce'] ) ) {

			// Verify the nonce.
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mo_oauth_server_postman_collection_form_nonce'] ) ), 'mo_oauth_server_postman_collection_form' ) ) {
				wp_die( 'Invalid nonce detected.' );
			}

			$postman_collection = new Miniorange_Oauth_20_Server_Postman_Collection_Download();
			$postman_collection->postman_collection_download();

		}

		// Form handler for debug log button toggle.
		if ( isset( $_POST['mo_oauth_server_debug_logs_form_nonce'] ) ) {
			// Verify the nonce.
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mo_oauth_server_debug_logs_form_nonce'] ) ), 'mo_oauth_server_debug_logs_form' ) ) {
				wp_die( 'Invalid nonce detected.' );
			}

			if ( isset( $_POST['mo_oauth_server_log_button_toggle'] ) && 'on' === sanitize_text_field( wp_unslash( $_POST['mo_oauth_server_log_button_toggle'] ) ) ) {
				update_option( 'mo_oauth_server_is_debug_enabled', 1, false );
			} else {
				update_option( 'mo_oauth_server_is_debug_enabled', 0, false );
			}

			if ( isset( $_POST['mo_oauth_server_download_logs'] ) && 'true' === sanitize_text_field( wp_unslash( $_POST['mo_oauth_server_download_logs'] ) ) ) {
				$debug_logs_download = new Miniorange_Oauth_20_Server_Log_Download();
				$debug_logs_download->handle_log_download();
			}

			if ( isset( $_POST['mo_oauth_server_delete_logs'] ) && 'true' === sanitize_text_field( wp_unslash( $_POST['mo_oauth_server_delete_logs'] ) ) ) {
				$debug_logs_delete = new Miniorange_Oauth_20_Server_Log_Delete();
				$debug_logs_delete->handle_log_delete();
			}

			wp_safe_redirect( 'admin.php?page=mo_oauth_server_settings&tab=troubleshooting' );
			exit;
		}

		// Form handler for feedback form.
		if ( isset( $_POST['option'] ) && 'mo_oauth_server_skip_feedback' === sanitize_text_field( wp_unslash( $_POST['option'] ) ) && isset( $_REQUEST['mo_oauth_server_skip_feedback_form_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['mo_oauth_server_skip_feedback_form_field'] ) ), 'mo_oauth_server_skip_feedback_form' ) ) {
			deactivate_plugins( dirname( dirname( __FILE__ ) ) . '\\mo_oauth_settings.php' );
			update_option( 'message', 'Plugin deactivated successfully', false );
			$this->utils->mo_oauth_show_success_message();
		} elseif ( isset( $_POST['mo_oauth_server_feedback'] ) && 'true' === sanitize_text_field( wp_unslash( $_POST['mo_oauth_server_feedback'] ) ) && isset( $_REQUEST['mo_oauth_server_feedback_form_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['mo_oauth_server_feedback_form_field'] ) ), 'mo_oauth_server_feedback_form' ) ) {
			$message                   = 'Plugin Deactivated: ';
			$rating                    = array_key_exists( 'rating', $_POST ) ? sanitize_textarea_field( wp_unslash( $_POST['rating'] ) ) : '';
			$deactivate_reason_message = array_key_exists( 'query_feedback', $_POST ) ? sanitize_textarea_field( wp_unslash( $_POST['query_feedback'] ) ) : false;
			$feed_email 	           = $_POST['os_feed_email'];
			$os_support_reply          = isset( $_POST['os_support_reply']) == 'checked' ? '' : ' [ No Reply ]';
			$phone                     = get_option( 'mo_oauth_server_admin_phone' );
			
			if( '' != $os_support_reply ) {
				$rating .= $os_support_reply;
			}
			if ( isset( $deactivate_reason_message ) ) {
				$message .= ': ' . $deactivate_reason_message;
			}
			
			$url      = get_option( 'host_name' ) . '/moas/rest/mobile/get-timestamp';
			$response = wp_remote_post( $url );

			if ( is_wp_error( $response ) ){
				deactivate_plugins( dirname( dirname( __FILE__ ) ) . '\\mo_oauth_settings.php' );
				wp_safe_redirect( self_admin_url( 'plugins.php?deactivate=true' ) );
				exit();
			}

			$feedback_reasons = new Mo_Oauth_Server_Customer();
			$submited         = json_decode( $feedback_reasons->mo_oauth_send_email_alert( $feed_email, $phone, $message, $rating ), true );
			deactivate_plugins( dirname( dirname( __FILE__ ) ) . '\\mo_oauth_settings.php' );
			update_option( 'message', 'Thank you for the feedback.' );
			$this->utils->mo_oauth_show_success_message();
		}

	}
}
