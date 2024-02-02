<?php
/**
 * Logging Class
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Utilities;

use GoodBids\Core;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

/**
 * Logging Functionality
 *
 * @since 1.0.0
 */
class Log {

	/**
	 * Logger instance.
	 *
	 * @since 1.0.0
	 * @var ?Logger $log
	 */
	private static ?Logger $monolog = null;

	/**
	 * New Relic loaded flag.
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	private static bool $new_relic_loaded = false;

	/**
	 * Logs directory.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private static string $logs_dir = WPCOM_VIP_PRIVATE_DIR . '/logs/';

	/**
	 * Enable this flag to log ALL messages to the console.
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	private static bool $debug_mode = false;

	/**
	 * Empty constructor to clear warning on log() method.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct() {}

	/**
	 * Initialize
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function init(): void {
		self::set_debug_mode();
		self::init_monolog();
		self::init_new_relic();
	}

	/**
	 * Initialize Monolog.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private static function init_monolog(): void {
		$output    = "%datetime% | %level_name% %message% %context% %extra%" . PHP_EOL;
		$formatter = new LineFormatter( $output );
		$formatter->ignoreEmptyContextAndExtra();

		$date    = date( 'Y-m-d' ); // phpcs:ignore
		$handler = new StreamHandler( self::$logs_dir . 'goodbids' . $date . '.log', Level::Debug );
		$handler->setFormatter( $formatter );

		self::$monolog = new Logger( 'GoodBids' );
		self::$monolog->pushHandler( $handler );
	}

	/**
	 * Configure New Relic if available.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private static function init_new_relic(): void {
		if ( self::$new_relic_loaded || ! extension_loaded( 'newrelic' ) || defined( 'VIP_GO_APP_ENVIRONMENT' ) || 'production' !== VIP_GO_APP_ENVIRONMENT ) {
			return;
		}

		self::$new_relic_loaded = true;
		newrelic_set_appname( $_SERVER['HTTP_HOST'] ); // phpcs:ignore
	}

	/**
	 * Check config for debug mode.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private static function set_debug_mode(): void {
		add_action(
			'muplugins_loaded',
			function() {
				self::$debug_mode = goodbids()->get_config( 'debug-mode' );
			}
		);
	}

	/**
	 * Method for logging new messages.
	 *
	 * @since 1.0.0
	 *
	 * @param string $message
	 * @param mixed  $context
	 * @param Level  $level
	 *
	 * @return void
	 */
	private static function log( string $message, mixed $context = null, Level $level = Level::Debug ): void {
		if ( ! self::$monolog ) {
			self::init_monolog();
		}

		if ( null !== $context && ! is_array( $context ) ) {
			$context = [ 'context' => $context ];
		}

		$source    = self::get_source();
		$php_level = self::convert_monolog_level_to_php( $level );

		$mono_message = sprintf( '| %s', $message );
		$mono_context = array_merge( $context, [ ':source:' => $source ] );

		self::$monolog->log( $level, $mono_message, $mono_context );

		$console_source  = self::get_source_string( $source, false );
		$console_message = sprintf( '%s' . PHP_EOL . '-> %s', $console_source, $message );

		// Log to WP VIP Dashboard.
		if ( E_USER_ERROR === $php_level ) {
			trigger_error( $console_message . PHP_EOL ); // phpcs:ignore

			// Log to New Relic if available.
			if ( self::$new_relic_loaded && function_exists( 'newrelic_notice_error' ) ) {
				newrelic_notice_error( $message );
			}
		} elseif ( Core::is_dev_env() && ( self::$debug_mode || E_USER_NOTICE !== $php_level ) ) {
			// Log to console.
			error_log( $console_message ); // phpcs:ignore

			if ( ! empty( $context ) ) {
				error_log( print_r( $context, true ) ); // phpcs:ignore
			}
		}
	}

	/**
	 * Log an info message.
	 *
	 * @since 1.0.0
	 *
	 * @param string $message
	 * @param array  $context
	 *
	 * @return void
	 */
	public static function info( string $message, array $context = [] ): void {
		self::log( $message, $context, Level::Info );
	}

	/**
	 * Log a Warning message.
	 *
	 * @since 1.0.0
	 *
	 * @param string $message
	 * @param array  $context
	 *
	 * @return void
	 */
	public static function warning( string $message, array $context = [] ): void {
		self::log( $message, $context, Level::Warning );
	}

	/**
	 * Log an Error message.
	 *
	 * @since 1.0.0
	 *
	 * @param string $message
	 * @param array  $context
	 *
	 * @return void
	 */
	public static function error( string $message, array $context = [] ): void {
		self::log( $message, $context, Level::Error );
	}

	/**
	 * Log a Debug message.
	 *
	 * @since 1.0.0
	 *
	 * @param string $message
	 * @param array  $context
	 *
	 * @return void
	 */
	public static function debug( string $message, array $context = [] ): void {
		self::log( $message, $context );
	}

	/**
	 * Log an Alert message.
	 *
	 * @since 1.0.0
	 *
	 * @param string $message
	 * @param array  $context
	 *
	 * @return void
	 */
	public static function alert( string $message, array $context = [] ): void {
		self::log( $message, $context, Level::Alert );
	}

	/**
	 * Log a Critical message.
	 *
	 * @since 1.0.0
	 *
	 * @param string $message
	 * @param array  $context
	 *
	 * @return void
	 */
	public static function critical( string $message, array $context = [] ): void {
		self::log( $message, $context, Level::Critical );
	}

	/**
	 * Log an Emergency message.
	 *
	 * @since 1.0.0
	 *
	 * @param string $message
	 * @param array  $context
	 *
	 * @return void
	 */
	public static function emergency( string $message, array $context = [] ): void {
		self::log( $message, $context, Level::Emergency );
	}

	/**
	 * Retrieve the source of the log caller.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	private static function get_source(): array {
		$limit     = 4;
		$backtrace = debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT, $limit ); // phpcs:ignore
		$caller    = [
			'class'    => '/',
			'function' => '()',
			'file'     => '?',
			'line'     => 0,
		];

		if ( isset( $backtrace[ $limit - 1 ] ) ) {
			// Merge with defaults.
			$caller = array_merge( $caller, $backtrace[ $limit - 1 ] );
		}

		return $caller;
	}

	/**
	 * Format the source array into a string.
	 *
	 * @since 1.0.0
	 *
	 * @param array $source
	 * @param bool  $file_path
	 *
	 * @return string
	 */
	private static function get_source_string( array $source, bool $file_path = true ): string {
		$func = $source['function'];
		if ( ! empty( $source['class'] ) ) {
			$func = $source['class'] . '::' . $func;
		}

		if ( ! $file_path ) {
			return sprintf( '%s:%d', $func, $source['line'] );
		}

		return sprintf( '%s:%d (%s)', $func, $source['line'], $source['file'] );
	}

	/**
	 * Convert the Monolog Level to a PHP error level.
	 *
	 * @since 1.0.0
	 *
	 * @param Level $level
	 *
	 * @return int
	 */
	private static function convert_monolog_level_to_php( Level $level ): int {
		return match ( $level ) {
			Level::Emergency,
			Level::Alert,
			Level::Critical,
			Level::Error   => E_USER_ERROR,
			Level::Warning => E_USER_WARNING,
			default        => E_USER_NOTICE,
		};
	}

	/**
	 * Convert PHP Error codes to a string
	 *
	 * @since 1.0.0
	 *
	 * @param int $error_code
	 *
	 * @return string
	 */
	public static function convert_php_level_to_string( int $error_code ): string {
		return match ( $error_code ) {
			E_USER_ERROR   => 'ERROR',
			E_USER_WARNING => 'WARNING',
			default        => 'NOTICE',
		};
	}
}
