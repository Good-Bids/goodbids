<?php
/**
 * Summary of class-mo-oauth-server-debug
 *
 * @package Debug
 */

/**
 * Summary of MO_OAuth_Server_Debug
 */
class MO_OAuth_Server_Debug {

	/**
	 * Summary of error_log
	 *
	 * Handles the debug logs.
	 *
	 * @param mixed $message error message.
	 * @return void
	 */
	public static function error_log( $message ) {

		if ( ! get_option( 'mo_oauth_server_is_debug_enabled' ) ) {
			return;
		}

		$file_location = MOSERVER_DIR . DIRECTORY_SEPARATOR . 'errorlogs' . DIRECTORY_SEPARATOR . 'wp_oauth_server_errors.log';
		$time          = gmdate( 'd-M-Y H:i:s' );
		$message       = '[ ' . $time . ' UTC]: ' . print_r( $message, true ) . PHP_EOL; //phpcs:ignore -- This is in debug logs.

		error_log( $message, 3, $file_location ); //phpcs:ignore -- This is in debug logs.
	}

}
