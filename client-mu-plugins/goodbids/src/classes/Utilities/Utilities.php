<?php
/**
 * Utilities Class
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Utilities;

use DateTimeImmutable;
use Exception;

/**
 * Utilities Methods
 *
 * @since 1.0.0
 */
class Utilities {

	/**
	 * @since 1.0.0
	 */
	public function __construct() {}

	/**
	 * Format a date/time string.
	 *
	 * @since 1.0.0
	 *
	 * @param string $datetime
	 * @param string $format
	 *
	 * @return string
	 */
	public function format_date_time( string $datetime, string $format ): string {
		if ( ! $format ) {
			return $datetime;
		}

		$formatted = $datetime;

		try {
			$formatted = ( new DateTimeImmutable( $datetime ) )->format( $format );
		} catch ( Exception $e ) {
			Log::error( $e->getMessage(), compact( 'datetime', 'format' ) );
		}

		return $formatted;
	}

	/**
	 * Convert a number to include ordinal.
	 *
	 * @since 1.0.0
	 *
	 * @param int $number
	 *
	 * @return string
	 */
	public function get_ordinal( int $number ): string {
		$ordinal = 'th';

		if ( $number % 10 === 1 && $number % 100 !== 11 ) {
			$ordinal = 'st';
		} elseif ( $number % 10 === 2 && $number % 100 !== 12 ) {
			$ordinal = 'nd';
		} elseif ( $number % 10 === 3 && $number % 100 !== 13 ) {
			$ordinal = 'rd';
		}

		return $number . $ordinal;
	}
}
