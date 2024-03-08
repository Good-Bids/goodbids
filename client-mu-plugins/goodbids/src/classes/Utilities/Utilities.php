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

	/**
	 * Displays an Admin Notice
	 *
	 * @since 1.0.0
	 *
	 * @param string $message
	 * @param bool   $is_dismissible
	 * @param string $type
	 * @param bool   $network
	 *
	 * @return void
	 */
	public function display_admin_notice( string $message, bool $is_dismissible = true, string $type = 'info', bool $network = false ): void {
		$hook = $network ? 'network_admin_notices' : 'admin_notices';
		add_action(
			$hook,
			function() use ( $message, $is_dismissible, $type ) {
				printf(
					'<div class="notice notice-%s%s">
							<p>%s</p>
						</div>',
					esc_attr( $type ),
					$is_dismissible ? ' is-dismissible' : '',
					esc_html( $message )
				);
			}
		);
	}

	/**
	 * Displays an Admin Error Notice
	 *
	 * @since 1.0.0
	 *
	 * @param string $message
	 * @param bool   $is_dismissible
	 * @param bool   $network
	 *
	 * @return void
	 */
	public function display_admin_error( string $message, bool $is_dismissible = true, bool $network = false ): void {
		$this->display_admin_notice( $message, $is_dismissible, 'error', $network );
	}

	/**
	 * Displays an Admin Success Notice
	 *
	 * @since 1.0.0
	 *
	 * @param string $message
	 * @param bool   $is_dismissible
	 * @param bool   $network
	 *
	 * @return void
	 */
	public function display_admin_success( string $message, bool $is_dismissible = true, bool $network = false ): void {
		$this->display_admin_notice( $message, $is_dismissible, 'success', $network );
	}

	/**
	 * Displays an Admin Warning Notice
	 *
	 * @since 1.0.0
	 *
	 * @param string $message
	 * @param bool   $is_dismissible
	 * @param bool   $network
	 *
	 * @return void
	 */
	public function display_admin_warning( string $message, bool $is_dismissible = true, bool $network = false ): void {
		$this->display_admin_notice( $message, $is_dismissible, 'warning', $network );
	}

	/**
	 * Displays an Admin Info Notice
	 *
	 * @since 1.0.0
	 *
	 * @param string $message
	 * @param bool   $is_dismissible
	 * @param bool   $network
	 *
	 * @return void
	 */
	public function display_admin_info( string $message, bool $is_dismissible = true, bool $network = false ): void {
		$this->display_admin_notice( $message, $is_dismissible, $network );
	}

	/**
	 * Displays a custom Admin Notice
	 *
	 * @since 1.0.0
	 *
	 * @param string $notice
	 * @param bool   $network
	 *
	 * @return void
	 */
	public function display_admin_custom( string $notice, bool $network = false ): void {
		$hook = $network ? 'network_admin_notices' : 'admin_notices';
		add_action(
			$hook,
			function() use ( $notice ) {
				echo wp_kses_post( $notice );
			}
		);
	}

	/**
	 * Disable HyperDB For the remainder of the current request.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function disable_hyperdb_temporarily(): void {
		goodbids()->woocommerce->coupons->hyperdb_enabled = false;
	}

	/**
	 * Helper for get_page_by_path
	 *
	 * @since 1.0.0
	 *
	 * @param string $path
	 * @param string $output
	 * @param string $post_type
	 *
	 * @return mixed
	 */
	public function get_page_by_path( string $path, string $output = OBJECT, string $post_type = 'page' ): mixed {
		if ( function_exists( 'wpcom_vip_get_page_by_path' ) ) {
			return wpcom_vip_get_page_by_path( $path, $output, $post_type );
		}

		return get_page_by_path( $path, $output, $post_type ); // phpcs:ignore
	}
}
