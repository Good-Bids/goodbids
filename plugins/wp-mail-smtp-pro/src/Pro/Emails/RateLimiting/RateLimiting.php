<?php

namespace WPMailSMTP\Pro\Emails\RateLimiting;

use DateTime;
use DateTimeZone;
use WPMailSMTP\Options;

/**
 * Class RateLimiting.
 *
 * @since 4.0.0
 */
class RateLimiting {

	/**
	 * Register hooks.
	 *
	 * @since 4.0.0
	 */
	public function hooks() {

		// Options management.
		add_filter( 'wp_mail_smtp_options_set', [ $this, 'options_set' ] );
		add_filter( 'wp_mail_smtp_options_get_const_value', [ $this, 'filter_options_get_const_value' ], 10, 4 );
		add_filter( 'wp_mail_smtp_options_is_const_defined', [ $this, 'filter_options_is_const_defined' ], 10, 3 );

		if ( self::is_enabled() ) {
			// Enable the queue.
			add_filter( 'wp_mail_smtp_queue_is_enabled', '__return_true' );

			// Configure the amount of emails to process.
			// We hook on the highest possible priority to ensure
			// that rate-limiting has the last word
			// (e.g. when used in conjunction with optimized email sending).
			add_filter( 'wp_mail_smtp_queue_process_count', [ $this, 'count_processable_emails' ], PHP_INT_MAX );

			// Configure the date before which emails can be cleaned up.
			add_filter( 'wp_mail_smtp_queue_cleanup_before_datetime', [ $this, 'get_cleanup_before_datetime' ] );

			// Start enqueueing emails.
			add_filter( 'wp_mail_smtp_mail_catcher_send_enqueue_email', '__return_true' );
		};
	}

	/**
	 * Whether rate limiting is enabled or not.
	 *
	 * @since 4.0.0
	 *
	 * @return bool
	 */
	public static function is_enabled() {

		return (bool) Options::init()->get( 'rate_limit', 'enabled' );
	}

	/**
	 * Sanitize options before they're saved.
	 *
	 * @since 4.0.0
	 *
	 * @param array $options Currently processed options passed to a filter hook.
	 *
	 * @return array
	 */
	public function options_set( $options ) {

		if ( ! isset( $options['rate_limit'] ) ) {
			// All options are off by default.
			$options['rate_limit'] = [
				'enabled' => false,
				'minute'  => '',
				'hour'    => '',
				'day'     => '',
				'week'    => '',
				'month'   => '',
			];

			return $options;
		}

		foreach ( $options['rate_limit'] as $key => $value ) {
			if ( $key === 'enabled' ) {
				$value = (bool) $value;
			} else {
				if ( is_numeric( $value ) ) {
					$value = max( 0, intval( $value ) );
				} else {
					$value = '';
				}
			}

			$options['rate_limit'][ $key ] = $value;
		}

		return $options;
	}

	/**
	 * Process the options values through the constants check.
	 *
	 * @since 4.0.0
	 *
	 * @param mixed  $return Constant value.
	 * @param string $group  The option group.
	 * @param string $key    The option key.
	 * @param mixed  $value  DB option value.
	 *
	 * @return mixed
	 */
	public function filter_options_get_const_value( $return, $group, $key, $value ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.MaxExceeded

		$options = Options::init();

		if ( $group === 'rate_limit' ) {

			switch ( $key ) {
				case 'enabled':
					$return = $options->is_const_defined( $group, $key ) ?
						$options->parse_boolean( WPMS_RATE_LIMIT_ENABLED ) :
						$value;
					break;

				case 'minute':
					$return = $options->is_const_defined( $group, $key ) ?
						intval( WPMS_RATE_LIMIT_PER_MINUTE ) :
						$value;
					break;

				case 'hour':
					$return = $options->is_const_defined( $group, $key ) ?
						intval( WPMS_RATE_LIMIT_PER_HOUR ) :
						$value;
					break;

				case 'day':
					$return = $options->is_const_defined( $group, $key ) ?
						intval( WPMS_RATE_LIMIT_PER_DAY ) :
						$value;
					break;

				case 'week':
					$return = $options->is_const_defined( $group, $key ) ?
						intval( WPMS_RATE_LIMIT_PER_WEEK ) :
						$value;
					break;

				case 'month':
					$return = $options->is_const_defined( $group, $key ) ?
						intval( WPMS_RATE_LIMIT_PER_MONTH ) :
						$value;
					break;
			}
		}

		return $return;
	}

	/**
	 * Check is constant defined.
	 *
	 * @since 4.0.0
	 *
	 * @param mixed  $return Constant value.
	 * @param string $group  The option group.
	 * @param string $key    The option key.
	 *
	 * @return bool
	 */
	public function filter_options_is_const_defined( $return, $group, $key ) { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh

		if ( $group === 'rate_limit' ) {

			switch ( $key ) {
				case 'enabled':
					$return = defined( 'WPMS_RATE_LIMIT_ENABLED' );
					break;

				case 'minute':
					$return = defined( 'WPMS_RATE_LIMIT_PER_MINUTE' );
					break;

				case 'hour':
					$return = defined( 'WPMS_RATE_LIMIT_PER_HOUR' );
					break;

				case 'day':
					$return = defined( 'WPMS_RATE_LIMIT_PER_DAY' );
					break;

				case 'week':
					$return = defined( 'WPMS_RATE_LIMIT_PER_WEEK' );
					break;

				case 'month':
					$return = defined( 'WPMS_RATE_LIMIT_PER_MONTH' );
					break;
			}
		}

		return $return;
	}

	/**
	 * Get a list of interval slugs
	 * and their DateTime offset.
	 *
	 * @since 4.0.0
	 *
	 * @return array
	 */
	private function get_intervals() {

		return [
			'month'  => '1 month ago',
			'week'   => '1 week ago',
			'day'    => '1 day ago',
			'hour'   => '1 hour ago',
			'minute' => '1 minute ago',
		];
	}

	/**
	 * Count how many emails can be
	 * currently scheduled for sending.
	 *
	 * @since 4.0.0
	 *
	 * @return int|null
	 */
	public function count_processable_emails() {

		$options         = Options::init();
		$queue           = wp_mail_smtp()->get_queue();
		$intervals       = $this->get_intervals();
		$interval_counts = [];

		foreach ( $intervals as $interval_slug => $interval_value ) {
			$interval_limit = $options->get( 'rate_limit', $interval_slug );

			// Skip if no rate limit is configured for this interval.
			if ( ! is_numeric( $interval_limit ) ) {
				continue;
			}

			$interval_datetime = new DateTime( $interval_value, new DateTimeZone( 'UTC' ) );

			// Count the amount of sent emails during
			// the current interval.
			$interval_count = $queue->count_processed_emails( $interval_datetime );

			// Calculate how many emails could still be
			// sent in the current interval.
			$interval_processable_emails = $interval_limit - $interval_count;

			// Bail if the amount of sent emails over the current
			// interval is already beyond the rate limit.
			if ( $interval_processable_emails <= 0 ) {
				return 0;
			}

			$interval_counts[ $interval_slug ] = $interval_processable_emails;
		}

		// Return the full queue if no intervals are configured.
		if ( empty( $interval_counts ) ) {
			return null;
		}

		// Return the minimum processable
		// amount of emails across all intervals.
		return min( array_values( $interval_counts ) );
	}

	/**
	 * Get the date and time before which
	 * to cleanup processed emails.
	 *
	 * @param null|DateTime $datetime The date and time before which to cleanup processed emails.
	 *
	 * @since 4.0.0
	 *
	 * @return DateTime
	 */
	public function get_cleanup_before_datetime( $datetime ) {

		return new DateTime( '1 month ago', new DateTimeZone( 'UTC' ) );
	}
}
