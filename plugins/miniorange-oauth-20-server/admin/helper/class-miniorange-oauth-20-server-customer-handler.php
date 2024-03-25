<?php
/**
 * Class Miniorange_Oauth_20_Server_Customer_Handler
 *
 * @package Miniorange_Oauth_20_Server
 */

/**
 * Class Miniorange_Oauth_20_Server_Customer_Handler
 *
 * This class handles the addition of a new client.
 */
class Miniorange_Oauth_20_Server_Customer_Handler {

	/**
	 * Utils contains some commonly used functions
	 *
	 * @var [object]
	 */
	private $utils;

	/**
	 * Constructor for Miniorange_Oauth_20_Server_Customer_Handler.
	 */
	public function __construct() {
		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/helper/class-miniorange-oauth-20-server-db.php';
		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/helper/class-miniorange-oauth-20-server-utils.php';
		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/helper/class-miniorange-oauth-20-server-customer.php';

		$this->utils = new Miniorange_Oauth_20_Server_Utils();
	}

	/**
	 * This function handles customer registration.
	 */
	public function handle_customer_registration() {

		// verify the nonce.
		if ( isset( $_POST['mo_oauth_server_register_customer_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mo_oauth_server_register_customer_nonce'] ) ), 'mo_oauth_server_register_customer' ) ) {
			wp_die( 'Invalid nonce detected.' );
		}

		$post = isset( $_POST['option'] ) ? sanitize_text_field( wp_unslash( $_POST['option'] ) ) : '';

		// validation and sanitization.
		$email            = '';
		$phone            = '';
		$password         = '';
		$confirm_password = '';
		$fname            = '';
		$lname            = '';
		$company          = '';
		if ( ! isset( $_POST['email'] ) || ! isset( $_POST['password'] ) || ! isset( $_POST['confirm_password'] ) ) {
			update_option( 'message', 'All the fields are required. Please enter valid entries.', false );
			$this->utils->mo_oauth_show_error_message();
			return;
			} elseif ( strlen( $_POST['password'] ) < 8 || strlen( $_POST['confirm_password'] ) < 8 ) { //phpcs:ignore -- Not sanitizing and unslashing password and confirm password
			update_option( 'message', 'Choose a password with minimum length 8.', false );
			$this->utils->mo_oauth_show_error_message();
			return;
		} else {

			$email            = sanitize_email( wp_unslash( $_POST['email'] ) );
			$password         = $_POST['password']; //phpcs:ignore -- Not sanitizing and unslashing password
			$confirm_password = $_POST['confirm_password']; //phpcs:ignore -- Not sanitizing and unslashing confirm password
			$fname            = isset( $_POST['fname'] ) ? sanitize_text_field( wp_unslash( $_POST['fname'] ) ) : '';
			$lname            = isset( $_POST['lname'] ) ? sanitize_text_field( wp_unslash( $_POST['lname'] ) ) : '';
			$company          = isset( $_POST['company'] ) ? sanitize_text_field( wp_unslash( $_POST['company'] ) ) : '';
		}

			update_option( 'mo_oauth_admin_email', $email, false );
			update_option( 'mo_oauth_admin_fname', $fname, false );
			update_option( 'mo_oauth_admin_lname', $lname, false );
			update_option( 'mo_oauth_admin_company', $company, false );

		if ( $this->utils->mo_oauth_server_is_curl_installed() === 0 ) {
			return $this->utils->mo_oauth_show_curl_error();
		}

		if ( strcmp( $password, $confirm_password ) === 0 ) {
			$email    = get_option( 'mo_oauth_admin_email' );
			$customer = new Mo_Oauth_Server_Customer();
			$content  = json_decode( $customer->check_customer(), true );

			if ( strcasecmp( $content['status'], 'CUSTOMER_NOT_FOUND' ) === 0 ) {
				$response = json_decode( $customer->create_customer( $password ), true );
				if ( strcasecmp( $response['status'], 'SUCCESS' ) === 0 ) {
					$content      = $customer->get_customer_key( $password );
					$customer_key = json_decode( $content, true );

					if ( json_last_error() === JSON_ERROR_NONE ) {
						update_option( 'mo_oauth_server_admin_customer_key', $customer_key['id'], false );
						update_option( 'mo_oauth_server_admin_api_key', $customer_key['apiKey'], false );
						update_option( 'mo_oauth_server_customer_token', $customer_key['token'], false );
						update_option( 'mo_oauth_server_admin_phone', $customer_key['phone'], false );
						update_option( 'message', 'Customer created & retrieved successfully', false );
						delete_option( 'mo_oauth_server_verify_customer' );
						$this->utils->mo_oauth_show_success_message();
					}
					wp_safe_redirect( admin_url( '/admin.php?page=mo_oauth_server_settings&tab=account_setup' ), 301 );
					exit;
				} else {
					update_option( 'message', 'Failed to create customer. Try again.', false );
				}
				$this->utils->mo_oauth_show_success_message();
			} elseif ( strcasecmp( $content['status'], 'SUCCESS' ) === 0 ) {
				update_option( 'message', 'Account already exist. Please Login.', false );
			} else {
				update_option( 'message', $content['status'], false );
			}
			$this->utils->mo_oauth_show_success_message();

		} else {
			update_option( 'message', 'Passwords do not match.', false );
			delete_option( 'mo_oauth_server_verify_customer' );
			$this->utils->mo_oauth_show_error_message();
		}
	}

	/**
	 * This function handles customer verification.
	 *
	 * @param string $email Email of the customer.
	 * @param string $password Password of the customer.
	 */
	public function handle_customer_verification( $email, $password ) {
		if ( $this->utils->mo_oauth_server_is_curl_installed() === 0 ) {
			return $this->utils->mo_oauth_show_curl_error();
		}

		// validation and sanitization.
		if ( $this->utils->mo_oauth_check_empty_or_null( $email ) || $this->utils->mo_oauth_check_empty_or_null( $password ) ) {
			update_option( 'message', 'All the fields are required. Please enter valid entries.', false );
			$this->utils->mo_oauth_show_error_message();
			return;
		}

		update_option( 'mo_oauth_admin_email', $email, false );
		$customer     = new Mo_Oauth_Server_Customer();
		$content      = $customer->get_customer_key( $password );
		$customer_key = json_decode( $content, true );

		if ( json_last_error() === JSON_ERROR_NONE ) {
			update_option( 'mo_oauth_server_admin_customer_key', $customer_key['id'], false );
			update_option( 'mo_oauth_server_admin_api_key', $customer_key['apiKey'], false );
			update_option( 'mo_oauth_server_customer_token', $customer_key['token'], false );
			update_option( 'mo_oauth_server_admin_phone', $customer_key['phone'], false );
			update_option( 'message', 'Customer retrieved successfully', false );
			update_option( 'mo_oauth_server_admin_phone', $customer_key['phone'], false );
			delete_option( 'mo_oauth_server_verify_customer' );
			$this->utils->mo_oauth_show_success_message();
		} else {
			update_option( 'message', 'Invalid username or password. Please try again.', false );
			$this->utils->mo_oauth_show_error_message();
		}
	}

	/**
	 * Summary of mo_oauth_server_is_customer_registered
	 *
	 * Checks if a customer is registered with miniOrange.
	 *
	 * @return int
	 */
	public function mo_oauth_server_is_customer_registered() {
		$email        = get_option( 'mo_oauth_admin_email' );
		$customer_key = get_option( 'mo_oauth_server_admin_customer_key' );
		if ( ! $email || ! $customer_key || ! is_numeric( trim( $customer_key ) ) ) {
			return 0;
		} else {
			return 1;
		}
	}
}
