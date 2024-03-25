<?php
/**
 * Class Miniorange_Oauth_20_Server_Demo_Request
 *
 * @package Miniorange_Oauth_20_Server
 */

/**
 * Class Miniorange_Oauth_20_Server_Demo_Request
 *
 * This class handles the demo request.
 */
class Miniorange_Oauth_20_Server_Demo_Request {

	/**
	 * Utils contains some commonly used functions
	 *
	 * @var [object]
	 */
	private $utils;

	/**
	 * Constructor for Miniorange_Oauth_20_Server_Demo_Request.
	 */
	public function __construct() {
		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/helper/class-miniorange-oauth-20-server-db.php';
		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/helper/class-miniorange-oauth-20-server-utils.php';
		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/helper/class-miniorange-oauth-20-server-customer.php';

		$this->utils = new Miniorange_Oauth_20_Server_Utils();
	}

	/**
	 * This function handles the demo request.
	 *
	 * @param string $email The email of customer.
	 * @param string $demo_plan The demo plan name.
	 * @param string $query The query for demo request.
	 */
	public function handle_demo_request( $email, $demo_plan, $query ) {
		if ( current_user_can( 'administrator' ) ) {
			if ( $this->utils->mo_oauth_server_is_curl_installed() === 0 ) {
				return $this->utils->mo_oauth_show_curl_error();
			}

			if ( $this->utils->mo_oauth_check_empty_or_null( $email ) || $this->utils->mo_oauth_check_empty_or_null( $demo_plan ) || $this->utils->mo_oauth_check_empty_or_null( $query ) ) {
				update_option( 'message', 'Please fill up Usecase and Email field to submit your query.' );
				$this->utils->mo_oauth_show_error_message();
			} else {
				$customer = new Mo_Oauth_Server_Customer();
				$query    = '<b>Demo Request</b> for WP OAuth server premium version - ' . $query;
				$submited = $customer->demo_request( $email, $query );

				if ( false === $submited ) {
					update_option( 'message', 'Your query could not be submitted. Please try again.', false );
					$this->utils->mo_oauth_show_error_message();
				} else {
					update_option( 'message', 'Thanks for getting in touch! We shall get back to you shortly.', false );
					$this->utils->mo_oauth_show_success_message();
				}
			}
		}
	}

	/**
	 * This function handles the video demo.
	 *
	 * @param string $email The email of the user.
	 * @param string $query The query of the user.
	 * @param string $call_date The date of the call.
	 * @param string $time_diff The time difference.
	 * @param string $call_time The time of the call.
	 */
	public function handle_video_demo( $email, $query, $call_date, $time_diff, $call_time ) {
		if ( current_user_can( 'administrator' ) ) {
			if ( $this->utils->mo_oauth_server_is_curl_installed() === 0 ) {
				return $this->utils->mo_oauth_show_curl_error();
			}

			if ( $this->utils->mo_oauth_check_empty_or_null( $email ) || $this->utils->mo_oauth_check_empty_or_null( $call_date ) || $this->utils->mo_oauth_check_empty_or_null( $query ) || $this->utils->mo_oauth_check_empty_or_null( $time_diff ) || $this->utils->mo_oauth_check_empty_or_null( $call_time ) ) {
				update_option( 'message', 'Please fill up all the required details to submit your query.' );
				$this->utils->mo_oauth_show_error_message();
			} else {
				$mo_oauth_video_demo_request_validated = false;

				if ( ! ( $this->utils->mo_oauth_check_empty_or_null( $email ) || $this->utils->mo_oauth_check_empty_or_null( $query ) || $this->utils->mo_oauth_check_empty_or_null( $call_date ) || $this->utils->mo_oauth_check_empty_or_null( $time_diff ) || $this->utils->mo_oauth_check_empty_or_null( $call_time ) ) ) {
					// Modify the $time_diff to test for the different timezones.
					// Note - $time_diff for IST is -330.
					$hrs      = floor( abs( $time_diff ) / 60 );
						$mins = fmod( abs( $time_diff ), 60 );
					if ( 0 === $mins ) {
						$mins = '00';
					}
						$sign = '+';
					if ( $time_diff > 0 ) {
						$sign = '-';
					}
						$call_time_zone = 'UTC ' . $sign . ' ' . $hrs . ':' . $mins;
						$call_date      = gmdate( 'jS F', strtotime( $call_date ) );

						// code to convert local time to IST.
						$local_hrs      = explode( ':', $call_time )[0];
						$local_mins     = explode( ':', $call_time )[1];
						$call_time_mins = ( $local_hrs * 60 ) + $local_mins;
						$ist_time       = $call_time_mins + $time_diff + 330;
						$ist_date       = $call_date;
					if ( $ist_time > 1440 ) {
						$ist_time = fmod( $ist_time, 1440 );
						$ist_date = gmdate( 'jS F', strtotime( '1 day', strtotime( $call_date ) ) );
					} elseif ( $ist_time < 0 ) {
						$ist_time = 1440 + $ist_time;
						$ist_date = gmdate( 'jS F', strtotime( '-1 day', strtotime( $call_date ) ) );
					}
					$ist_hrs = floor( $ist_time / 60 );
					$ist_hrs = sprintf( '%02d', $ist_hrs );

					$ist_mins = fmod( $ist_time, 60 );
					$ist_mins = sprintf( '%02d', $ist_mins );

					$ist_time                              = $ist_hrs . ':' . $ist_mins;
					$mo_oauth_video_demo_request_validated = true;
				}

				if ( $mo_oauth_video_demo_request_validated ) {
					$customer = new Mo_Oauth_Server_Customer();
					$query    = '<b>Video Demo Request</b> for WP OAuth server premium version - ' .
					'<br><br><div>Customer local time (' . $call_time_zone . ') : ' . $call_time . ' on ' . $call_date . '<br><br>IST format    : ' . $ist_time . ' on ' . $ist_date . '<br><br>Requirements (User usecase)           : ' . $query . '</div>';
					$submited = $customer->demo_request( $email, $query );
					if ( false === $submited ) {
						update_option( 'message', 'Your query could not be submitted. Please try again.', false );
						$this->utils->mo_oauth_show_error_message();
					} else {
						update_option( 'message', 'Thanks for getting in touch! We shall get back to you shortly.', false );
						$this->utils->mo_oauth_show_success_message();
					}
				} else {
					update_option( 'message', 'Your query could not be submitted. Please fill up all the required fields and try again.' );
					$this->utils->mo_oauth_show_error_message();
				}
			}
		}
	}
}
