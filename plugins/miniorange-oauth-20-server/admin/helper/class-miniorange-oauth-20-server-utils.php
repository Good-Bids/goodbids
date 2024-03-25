<?php
/**
 * Class Miniorange_Oauth_20_Server_Utils
 *
 * @package Miniorange_Oauth_20_Server
 */

/**
 * Class Miniorange_Oauth_20_Server_Utils
 *
 * This class handles the utility functions.
 */
class Miniorange_Oauth_20_Server_Utils {
	/**
	 * Summary of moos_generate_random_string
	 *
	 * Generates a random string for client ID and client secret.
	 *
	 * @param int $length of the random string to be generated.
	 * @return string
	 */
	public function moos_generate_random_string( $length = 10 ) {
		$characters        = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$characters_length = strlen( $characters );
		$random_string     = '';
		for ( $i = 0; $i < $length; $i++ ) {
			$random_string .= $characters[ random_int( 0, $characters_length - 1 ) ];
		}
		return $random_string;
	}

	/**
	 * Summary of mo_oauth_check_empty_or_null
	 *
	 * Checks if the input is empty or null.
	 *
	 * @param mixed $value to check if empty or null.
	 * @return bool
	 */
	public function mo_oauth_check_empty_or_null( $value ) {
		if ( ! isset( $value ) || empty( $value ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Function to get home url with permalink structure.
	 */
	public function get_home_url_with_permalink_structure() {

		// returns empty string in case of plain permalink structure.
		$permalink_structure = get_option( 'permalink_structure' );

		if ( ! $permalink_structure ) {
			return home_url() . '/?rest_route=';
		} else {
			return home_url() . '/' . rest_get_url_prefix();
		}

	}

	/**
	 * Summary of mo_oauth_server_encrypt
	 *
	 * Encrypts the input string.
	 *
	 * @param string $str_secret the client secret to encrypt.
	 * @param string $str_name the client name whose secret is to be encrypted.
	 * @return string
	 */
	public function mo_oauth_server_encrypt( $str_secret, $str_name ) {
		$pass = $str_name;
		if ( ! $pass ) {
			return 'false';
		}
		$pass = str_split( str_pad( '', strlen( $str_secret ), $pass, STR_PAD_RIGHT ) );
		$stra = str_split( $str_secret );
		foreach ( $stra as $k => $v ) {
			$tmp        = ord( $v ) + ord( $pass[ $k ] );
			$stra[ $k ] = chr( $tmp > 255 ? ( $tmp - 256 ) : $tmp );
		}
		return base64_encode( join( '', $stra ) ); // phpcs:ignore -- This function encrypts the client_secret that is stored in the database
	}

	/**
	 * Summary of mo_oauth_server_decrypt
	 *
	 * Decrypts the input string.
	 *
	 * @param string $str the client secret to decrypt.
	 * @param string $str_name the client name whose secret is to be decrypted.
	 * @return string
	 */
	public function mo_oauth_server_decrypt( $str, $str_name ) {
		// miniorange oauth server plugin update version 5 onwards.
		// storing client secret in encrypted format.

		$is_client_secret_encrypted = get_option( 'mo_oauth_server_is_client_secret_encrypted' );

		if ( ! $is_client_secret_encrypted ) {
			// If client secret is not encrypted encrypt it.
			$mo_oauth_server_db = new Mo_Oauth_Server_Db();
			$clientlist         = $mo_oauth_server_db->get_clients();
			if ( count( $clientlist ) < 1 ) {
				echo 'Client not found! Please set up client first.';
				exit();
			}
			$client_secret = $clientlist[0]->client_secret;
			$client_name   = $clientlist[0]->client_name;
			$client_secret = $this->mo_oauth_server_encrypt( $client_secret, $client_name );

			// Insert updated client secret to database.
			global $wpdb;
			$wpdb->query( $wpdb->prepare( 'UPDATE %s moos_oauth_clients SET client_secret = %s WHERE client_name = %s and active_oauth_server_id = %d', $wpdb->base_prefix, $client_secret, $client_name, get_current_blog_id() ) ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery

			$is_client_secret_encrypted = 1;
			update_option( 'mo_oauth_server_is_client_secret_encrypted', $is_client_secret_encrypted, false );
			$str = $client_secret;
		}

		$str  = base64_decode( $str ); // phpcs:ignore -- This function decrypts the client_secret that is stored in the database
		$pass = $str_name;
		if ( ! $pass ) {
			return 'false';
		}

		$pass = str_split( str_pad( '', strlen( $str ), $pass, STR_PAD_RIGHT ) );
		$stra = str_split( $str );
		foreach ( $stra as $k => $v ) {
			$tmp        = ord( $v ) - ord( $pass[ $k ] );
			$stra[ $k ] = chr( $tmp < 0 ? ( $tmp + 256 ) : $tmp );
		}
		return join( '', $stra );
	}

	/**
	 * Summary of mo_oauth_show_error_message
	 *
	 * Adds error messages using WP action.
	 *
	 * @return void
	 */
	public function mo_oauth_show_error_message() {
		remove_action( 'admin_notices', array( $this, 'mo_oauth_error_message' ) );
		add_action( 'admin_notices', array( $this, 'mo_oauth_success_message' ) );
	}

	/**
	 * Summary of mo_oauth_show_success_message
	 *
	 * Adds success messages using WP action.
	 *
	 * @return void
	 */
	public function mo_oauth_show_success_message() {
		remove_action( 'admin_notices', array( $this, 'mo_oauth_success_message' ) );
		add_action( 'admin_notices', array( $this, 'mo_oauth_error_message' ) );
	}

	/**
	 * Summary of mo_oauth_error_message
	 *
	 * Displays error messages.
	 *
	 * @return void
	 */
	public function mo_oauth_error_message() {
		$class   = 'updated ml-0 mr-5';
		$message = esc_html( get_option( 'message' ) );

		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
	}

	/**
	 * Summary of mo_oauth_success_message
	 *
	 * Displays a success message.
	 *
	 * @return void
	 */
	public function mo_oauth_success_message() {
		$class   = 'error ml-0 mr-5';
		$message = esc_html( get_option( 'message' ) );

		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
	}

	/**
	 * Summary of mo_oauth_server_is_curl_installed
	 *
	 * Checks if cURL is installed.
	 *
	 * @return int 1 if cURL is installed, 0 otherwise.
	 */
	public function mo_oauth_server_is_curl_installed() {
		if ( in_array( 'curl', get_loaded_extensions(), true ) ) {
			return 1;
		} else {
			return 0;
		}
	}

	/**
	 * Summary of mo_oauth_show_curl_error
	 *
	 * Shows error message if cURL is not installed.
	 *
	 * @return void
	 */
	public function mo_oauth_show_curl_error() {
		if ( $this->mo_oauth_server_is_curl_installed() === 0 ) {
			update_option( 'message', '<a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP CURL extension</a> is not installed or disabled. Please enable it to continue.', false );
			$this->mo_oauth_show_error_message();
			return;
		}
	}
}
