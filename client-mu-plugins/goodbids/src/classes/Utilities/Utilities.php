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
use JetBrains\PhpStorm\NoReturn;

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
	 * Render a custom message and exit.
	 *
	 * @since 1.0.0
	 *
	 * @param string $message
	 *
	 * @return void
	 */
	public function die( string $message ): void {
		goodbids()->load_view( 'parts/die.php', compact( 'message' ) );
		exit;
	}

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
	 * Check if the context inside the Network Admin is the Main site.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function network_is_main_site(): bool {
		if ( ! is_network_admin() ) {
			return is_main_site();
		}

		$site_id = $this->network_get_current_blog_id();

		if ( ! $site_id ) {
			return is_main_site();
		}

		return $site_id === get_main_site_id();
	}

	/**
	 * Gets the current Site ID in the context of the Network Admin.
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function network_get_current_blog_id(): int {
		if ( ! is_network_admin() || empty( $_GET['id'] ) ) { // phpcs:ignore
			return get_current_blog_id();
		}

		return intval( sanitize_text_field( $_GET['id'] ) ); // phpcs:ignore
	}

	/**
	 * Sanitize Special Characters in Emails
	 *
	 * @since 1.0.1
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	public function sanitize_email_special_chars( string $string ): string {
		$string = htmlspecialchars_decode( $string );
		return html_entity_decode( $string );
	}
}
