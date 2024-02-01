<?php
/**
 * Logging Class
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Utilities;

/**
 * Logging Functionality
 *
 * @since 1.0.0
 */
class Log {

	private static bool $new_relic_loaded = false;

	/**
	 * Initialize
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function init(): void {
		self::init_new_relic();
	}

	/**
	 * Configure New Relic if available.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private static function init_new_relic(): void {
		if ( ! extension_loaded( 'newrelic' ) || defined( 'VIP_GO_APP_ENVIRONMENT' ) || 'production' !== VIP_GO_APP_ENVIRONMENT ) {
			return;
		}

		self::$new_relic_loaded = true;
		newrelic_set_appname( $_SERVER['HTTP_HOST'] ); // phpcs:ignore
	}

	/**
	 * Method for adding new log messages.
	 *
	 * @since 1.0.0
	 *
	 * @param string $message
	 * @param mixed  $debug_data
	 * @param string $level
	 *
	 * @return void
	 */
	public static function add( string $message, mixed $debug_data = null, string $level = E_USER_NOTICE ): void {
		if ( E_USER_ERROR === $level ) {
			trigger_error( $message ); // phpcs:ignore
		}

		if ( self::$new_relic_loaded && function_exists( 'newrelic_notice_error' ) ) {
			newrelic_notice_error( $message );
		}
	}

	/**
	 * Alias for add method with level E_USER_ERROR.
	 *
	 * @since 1.0.0
	 *
	 * @param string $message
	 * @param mixed  $debug_data
	 *
	 * @return void
	 */
	public static function add_info( string $message, mixed $debug_data = null ): void {
		self::add( $message, $debug_data );
	}

	/**
	 * Log a debug Warning message.
	 *
	 * @since 1.0.0
	 *
	 * @param string $message
	 * @param mixed  $debug_data
	 *
	 * @return void
	 */
	public static function add_warning( string $message, mixed $debug_data = null ): void {
		self::add( $message, $debug_data, E_USER_WARNING );
	}

	/**
	 * Log a debug Error message.
	 *
	 * @since 1.0.0
	 *
	 * @param string $message
	 * @param mixed  $debug_data
	 *
	 * @return void
	 */
	public static function add_error( string $message, mixed $debug_data = null ): void {
		self::add( $message, $debug_data, E_USER_ERROR );
	}
}
