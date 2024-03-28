<?php
/** MiniOrange enables user to log in into their OAuth/OpenID Connect applications through WordPress users.
	Copyright (C) 2015  miniOrange

	Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

 * @package      miniOrange OAuth
 * @license      https://docs.miniorange.com/mit-license MIT/Expat
 */

require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'helper' . DIRECTORY_SEPARATOR . 'constants' . DIRECTORY_SEPARATOR . 'class-miniorange-oauth-20-server-oauth-constants.php';

/**
	This library is miniOrange Authentication Service.
	Contains Request Calls to Customer service.
 **/
class Mo_Oauth_Server_Customer {

	/**
	 * Summary of email
	 *
	 * @var mixed
	 */
	public $email;

	/**
	 * Summary of phone
	 *
	 * @var mixed
	 */
	public $phone;

	/**
	 * Summary of default_customer_key
	 *
	 * @var mixed
	 */
	private $default_customer_key = '16555';

	/**
	 * Summary of default_api_key
	 *
	 * @var mixed
	 */
	private $default_api_key = 'fFd2XcvTGDemZvbw1bcUesNJWEqKbbUq';

	/**
	 * Summary of create_customer
	 *
	 * Creates customer in miniOrange database using /customer/add API.
	 *
	 * @param string $password for registeration or login with miniOrange.
	 * @return mixed
	 */
	public function create_customer( $password = '' ) {
		$url         = get_option( 'host_name' ) . '/moas/rest/customer/add';
		$this->email = get_option( 'mo_oauth_admin_email' );
		$this->phone = get_option( 'mo_oauth_server_admin_phone' );
		$first_name  = get_option( 'mo_oauth_admin_fname' );
		$last_name   = get_option( 'mo_oauth_admin_lname' );
		$company     = get_option( 'mo_oauth_admin_company' );

		$fields       = array(
			'companyName'    => $company,
			'areaOfInterest' => 'WP OAuth 2.0 Server',
			'firstname'      => $first_name,
			'lastname'       => $last_name,
			'email'          => $this->email,
			'phone'          => $this->phone,
			'password'       => $password,
		);
		$field_string = wp_json_encode( $fields );
		$headers      = array(
			'Content-Type'  => 'application/json',
			'charset'       => 'UTF - 8',
			'Authorization' => 'Basic',
		);
		$args         = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '30',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,

		);

		$response = wp_remote_post( $url, $args );
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			echo 'Something went wrong: ' . esc_attr( $error_message );
			exit();
		}

		return wp_remote_retrieve_body( $response );
	}


	/**
	 * Summary of get_timestamp
	 *
	 * Gets the timestanp.
	 *
	 * @return mixed
	 */
	public function get_timestamp() {
		$url = get_option( 'host_name' ) . '/moas/rest/mobile/get-timestamp';

		$headers = array(
			'Content-Type'  => 'application/json',
			'charset'       => 'UTF - 8',
			'Authorization' => 'Basic',
		);
		$args    = array(
			'method'      => 'POST',
			'body'        => array(),
			'timeout'     => '30',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,

		);

		return $this->mo_idp_wp_remote_post( $url, $args );

	}

	/**
	 * Sends a contact request to the MOAS REST API endpoint.
	 *
	 * @param string $email The email of the user submitting the contact request.
	 * @param string $query The message/query of the user submitting the contact request.
	 * @return string The response body of the contact request sent to the MOAS REST API endpoint.
	 */
	public function demo_request( $email, $query ) {
		global $current_user;
		wp_get_current_user();
		$query        = '[WP OAuth 2.0 Server] ' . $query;
		$fields       = array(
			'firstName' => $current_user->user_firstname,
			'lastName'  => $current_user->user_lastname,
			'company'   => isset( $_SERVER['SERVER_NAME'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) : '',
			'email'     => $email,
			'ccEmail'   => 'wpidpsupport@xecurify.com',
			'query'     => $query,
		);
		$field_string = wp_json_encode( $fields );

		$url     = get_option( 'host_name' ) . '/moas/rest/customer/contact-us';
		$headers = array(
			'Content-Type'  => 'application/json',
			'charset'       => 'UTF - 8',
			'Authorization' => 'Basic',
		);
		$args    = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '30',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,

		);

		return $this->mo_idp_wp_remote_post( $url, $args );
	}

	/**
	 * Summary of mo_oauth_send_email_alert
	 *
	 * Sends an email for plugin feedback.
	 *
	 * @param string $email of the customer.
	 * @param string $phone of the customer.
	 * @param string $message given by the customer.
	 * @return mixed
	 */
	public function mo_oauth_send_email_alert( $email, $phone, $message, $rating = '' ) {

		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'includes' . DIRECTORY_SEPARATOR . 'class-miniorange-oauth-20-server.php';
		$mo_server = new Miniorange_Oauth_20_Server();
		$version   = $mo_server->get_version();

		$url                    = get_option( 'host_name' ) . '/moas/api/notify/send';
		$customer_key           = $this->default_customer_key;
		$api_key                = $this->default_api_key;
		$current_time_in_millis = self::get_timestamp();
		$string_to_hash         = $customer_key . $current_time_in_millis . $api_key;
		$hash_value             = hash( 'sha512', $string_to_hash );
		$customer_key_header    = 'Customer-Key: ' . $customer_key;
		$timestamp_header       = 'Timestamp: ' . $current_time_in_millis;
		$authorization_header   = 'Authorization: ' . $hash_value;
		$from_email             = $email;
		$subject                = 'Feedback: WordPress OAuth Server Plugin';
		$site_url               = site_url();
		global $user;
		$user = wp_get_current_user();

		$server_name = isset( $_SERVER['SERVER_NAME'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) : 'Not able to fetch server name from $_SERVER[\'SERVER_NAME\']';

		$query   = '[WordPress WP OAuth Server] : ' . $message;
		$content = '<div >Hello, <br><br>First Name :' . $user->user_firstname . '<br><br>Last  Name :' . $user->user_lastname . '   <br><br>Company :<a href="' . $server_name . '" target="_blank" >' . sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) . '</a><br><br>Phone Number :' . $phone . '<br><br>Email :<a href="mailto:' . $from_email . '" target="_blank">' . $from_email . '</a><br><br>Rating :' . $rating . '<br><br>Version :' . $version . '<br><br>Query :' . $query . '</div>';

		$fields = array(
			'customerKey' => $customer_key,
			'sendEmail'   => true,
			'email'       => array(
				'customerKey' => $customer_key,
				'fromEmail'   => $from_email,
				'bccEmail'    => 'wpidpsupport@xecurify.com',
				'fromName'    => 'miniOrange',
				'toEmail'     => 'wpidpsupport@xecurify.com',
				'toName'      => 'wpidpsupport@xecurify.com',
				'subject'     => $subject,
				'content'     => $content,
			),
		);

		$field_string             = wp_json_encode( $fields );
		$headers                  = array( 'Content-Type' => 'application/json' );
		$headers['Customer-Key']  = $customer_key;
		$headers['Timestamp']     = $current_time_in_millis;
		$headers['Authorization'] = $hash_value;
		$args                     = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '30',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,
		);

		return $this->mo_idp_wp_remote_post( $url, $args );
	}


	/**
	 * Summary of get_customer_key
	 *
	 * Gets the customer key for the registered customer.
	 *
	 * @param string $password of the customer.
	 * @return mixed
	 */
	public function get_customer_key( $password = '' ) {
		$url   = get_option( 'host_name' ) . '/moas/rest/customer/key';
		$email = get_option( 'mo_oauth_admin_email' );

		$fields       = array(
			'email'    => $email,
			'password' => $password,
		);
		$field_string = wp_json_encode( $fields );

		$headers = array(
			'Content-Type'  => 'application/json',
			'charset'       => 'UTF - 8',
			'Authorization' => 'Basic',
		);
		$args    = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '30',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,

		);

		return $this->mo_idp_wp_remote_post( $url, $args );
	}

	/**
	 * Summary of submit_contact_us
	 *
	 * Sends an email for customer query.
	 *
	 * @param string $email of the customer.
	 * @param string $phone of the customer.
	 * @param string $query of the customer.
	 * @return mixed
	 */
	public function submit_contact_us( $email, $phone, $query ) {
		global $current_user;
		wp_get_current_user();
		$query        = '[WP OAuth 2.0 Server] ' . $query;
		$fields       = array(
			'firstName' => $current_user->user_firstname,
			'lastName'  => $current_user->user_lastname,
			'company'   => isset( $_SERVER['SERVER_NAME'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) : '',
			'email'     => $email,
			'ccEmail'   => 'wpidpsupport@xecurify.com',
			'phone'     => $phone,
			'query'     => $query,
		);
		$field_string = wp_json_encode( $fields );

		$url     = get_option( 'host_name' ) . '/moas/rest/customer/contact-us';
		$headers = array(
			'Content-Type'  => 'application/json',
			'charset'       => 'UTF - 8',
			'Authorization' => 'Basic',
		);
		$args    = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '30',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,

		);

		$response = wp_remote_post( $url, $args );
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			if( strpos( $error_message, 'cURL error 6:' ) !== false ){
				echo 'Unable to connect to the Internet. Please try again.';
			} else{
				echo 'Something went wrong: ' . esc_attr( $error_message );
			}
			exit();
		}

		if ( wp_remote_retrieve_body( $response ) !== Miniorange_Oauth_20_Server_Oauth_Constants::QUERY_SUBMITTED ) {
			return false;
		}
		return true;
	}

	/**
	 * Summary of mo_oauth_send_demo_alert
	 *
	 * Sends an email regarding demo data.
	 *
	 * @param string $email of the customer.
	 * @param string $demo_plan chosen by the customer.
	 * @param string $message of the customer.
	 * @param string $subject of the email.
	 * @return void
	 */
	public function mo_oauth_send_demo_alert( $email, $demo_plan, $message, $subject ) {

		$url = get_option( 'host_name' ) . '/moas/api/notify/send';

		$customer_key = $this->default_customer_key;
		$api_key      = $this->default_api_key;

		$current_time_in_millis = self::get_timestamp();
		$string_to_hash         = $customer_key . $current_time_in_millis . $api_key;
		$hash_value             = hash( 'sha512', $string_to_hash );
		$customer_key_header    = 'Customer-Key: ' . $customer_key;
		$timestamp_header       = 'Timestamp: ' . $current_time_in_millis;
		$authorization_header   = 'Authorization: ' . $hash_value;
		$from_email             = $email;
		$site_url               = site_url();

		global $user;
		$user = wp_get_current_user();

		$content = '<div >Hello, </a><br><br>Email :<a href="mailto:' . $from_email . '" target="_blank">' . $from_email . '</a><br><br>Requested Demo for     : ' . $demo_plan . '<br><br>Requirements (User usecase)           : ' . $message . '</div>';

		$fields                   = array(
			'customerKey' => $customer_key,
			'sendEmail'   => true,
			'email'       => array(
				'customerKey' => $customer_key,
				'from_email'  => $from_email,
				'bccEmail'    => 'wpidpsupport@xecurify.com',
				'fromName'    => 'miniOrange',
				'toEmail'     => 'wpidpsupport@xecurify.com',
				'toName'      => 'wpidpsupport@xecurify.com',
				'subject'     => $subject,
				'content'     => $content,
			),
		);
		$field_string             = wp_json_encode( $fields );
		$headers                  = array( 'Content-Type' => 'application/json' );
		$headers['Customer-Key']  = $customer_key;
		$headers['Timestamp']     = $current_time_in_millis;
		$headers['Authorization'] = $hash_value;
		$args                     = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '30',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,

		);

		return $this->mo_idp_wp_remote_post( $url, $args );
	}

	/**
	 * Summary of mo_oauth_send_jwks_alert
	 *
	 * Sends an email alert when the JWKS URI is it more than 10 times.
	 *
	 * @param string $email of the customer.
	 * @param string $message of the customer.
	 * @param string $subject for the email.
	 * @return void
	 */
	public function mo_oauth_send_jwks_alert( $email, $message, $subject ) {

		$url = get_option( 'host_name' ) . '/moas/api/notify/send';

		$customer_key = $this->default_customer_key;
		$api_key      = $this->default_api_key;

		$current_time_in_millis = self::get_timestamp();
		$string_to_hash         = $customer_key . $current_time_in_millis . $api_key;
		$hash_value             = hash( 'sha512', $string_to_hash );
		$customer_key_header    = 'Customer-Key: ' . $customer_key;
		$timestamp_header       = 'Timestamp: ' . $current_time_in_millis;
		$authorization_header   = 'Authorization: ' . $hash_value;
		$from_email             = $email;
		$site_url               = site_url();

		global $user;
		$user = wp_get_current_user();

		$content = '<div >' . $message . '</div>';

		$fields                   = array(
			'customerKey' => $customer_key,
			'sendEmail'   => true,
			'email'       => array(
				'customerKey' => $customer_key,
				'bccEmail'    => 'wpidpsupport@xecurify.com',
				'fromName'    => 'miniOrange',
				'toEmail'     => $email,
				'toName'      => $email,
				'subject'     => $subject,
				'content'     => $content,
			),
		);
		$field_string             = wp_json_encode( $fields );
		$headers                  = array( 'Content-Type' => 'application/json' );
		$headers['Customer-Key']  = $customer_key;
		$headers['Timestamp']     = $current_time_in_millis;
		$headers['Authorization'] = $hash_value;
		$args                     = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '30',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,

		);

		return $this->mo_idp_wp_remote_post( $url, $args );
	}

	/**
	 * Summary of check_customer
	 *
	 * Checks if a customer exists in miniOrange database.
	 *
	 * @return mixed
	 */
	public function check_customer() {
			$url   = get_option( 'host_name' ) . '/moas/rest/customer/check-if-exists';
			$email = get_option( 'mo_oauth_admin_email' );

			$fields       = array(
				'email' => $email,
			);
			$field_string = wp_json_encode( $fields );

			$headers = array(
				'Content-Type'  => 'application/json',
				'charset'       => 'UTF - 8',
				'Authorization' => 'Basic',
			);
			$args    = array(
				'method'      => 'POST',
				'body'        => $field_string,
				'timeout'     => '30',
				'redirection' => '5',
				'httpversion' => '1.0',
				'blocking'    => true,
				'headers'     => $headers,

			);

			return $this->mo_idp_wp_remote_post( $url, $args );
	}

	/**
	 * Summary of mo_oauth_send_video_demo_alert
	 *
	 * Sends an email alert regarding video demo data.
	 *
	 * @param string $email email of the customer.
	 * @param string $ist_date IST date for video demo.
	 * @param string $query query of the customer.
	 * @param string $ist_time IST time for video demo.
	 * @param string $subject subject for the email.
	 * @param string $call_time_zone customer timezone for video demo.
	 * @param string $call_time customer time for video demo.
	 * @param string $call_date customer date for video demo.
	 * @return void
	 */
	public function mo_oauth_send_video_demo_alert( $email, $ist_date, $query, $ist_time, $subject, $call_time_zone, $call_time, $call_date ) {

		$url = get_option( 'host_name' ) . '/moas/api/notify/send';

		$customer_key = $this->default_customer_key;
		$api_key      = $this->default_api_key;

		$current_time_in_millis = self::get_timestamp();
		$string_to_hash         = $customer_key . $current_time_in_millis . $api_key;
		$hash_value             = hash( 'sha512', $string_to_hash );
		$customer_key_header    = 'Customer-Key: ' . $customer_key;
		$timestamp_header       = 'Timestamp: ' . $current_time_in_millis;
		$authorization_header   = 'Authorization: ' . $hash_value;
		$from_email             = $email;
		$site_url               = site_url();

		global $user;
		$user    = wp_get_current_user();
		$content = '<div >Hello, </a><br><br>Email :<a href="mailto:' . $from_email . '" target="_blank">' . $from_email . '</a><br><br> Customer local time (' . $call_time_zone . ') : ' . $call_time . ' on ' . $call_date . '<br><br>IST format    : ' . $ist_time . ' on ' . $ist_date . '<br><br>Requirements (User usecase)           : ' . $query . '</div>';

		$fields                   = array(
			'customerKey' => $customer_key,
			'sendEmail'   => true,
			'email'       => array(
				'customerKey' => $customer_key,
				'from_email'  => $from_email,
				'bccEmail'    => 'wpidpsupport@xecurify.com',
				'fromName'    => 'miniOrange',
				'toEmail'     => 'wpidpsupport@xecurify.com',
				'toName'      => 'wpidpsupport@xecurify.com',
				'subject'     => $subject,
				'content'     => $content,
			),
		);
		$field_string             = wp_json_encode( $fields );
		$headers                  = array( 'Content-Type' => 'application/json' );
		$headers['Customer-Key']  = $customer_key;
		$headers['Timestamp']     = $current_time_in_millis;
		$headers['Authorization'] = $hash_value;
		$args                     = array(
			'method'      => 'POST',
			'body'        => $field_string,
			'timeout'     => '30',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,

		);

		return $this->mo_idp_wp_remote_post( $url, $args );
	}

	/**
	 * Perform a WordPress remote POST request and handle errors.
	 *
	 * @param string $url   The URL to which the POST request is sent.
	 * @param array  $args  Arguments for the request.
	 *
	 * @return string|false Response body on success, exit on failure with error message.
	 */
	public function mo_idp_wp_remote_post($url, $args){
		$response = wp_remote_post( $url, $args );
		if ( ! is_wp_error( $response ) ){
			return wp_remote_retrieve_body( $response );
		} else {
			$error_message = $response->get_error_message();
			if( strpos( $error_message, 'cURL error 6:' ) !== false ){
				echo 'Unable to connect to the Internet. Please try again.';
			} else{
				echo 'Something went wrong: ' . esc_attr( $error_message );
			}
			exit();
		}
	}
}
