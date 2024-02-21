<?php
/**
 * Cookie Handler Class
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Utilities;

/**
 * Cookies Methods
 *
 * @since 1.0.0
 */
class Cookies {

	/**
	 * Set a cookie
	 *
	 * @since 1.0.0
	 *
	 * @param string $name
	 * @param string $value
	 * @param int    $expiration
	 * @param bool   $httponly
	 *
	 * @return void
	 */
	public static function set( string $name, string $value, int $expiration, bool $httponly = true ): void {
		if ( headers_sent() ) {
			return;
		}

		setcookie( $name, $value, $expiration, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), $httponly ); // phpcs:ignore

		if ( COOKIEPATH != SITECOOKIEPATH ) {
			setcookie( $name, $value, $expiration, SITECOOKIEPATH, COOKIE_DOMAIN, is_ssl(), $httponly ); // phpcs:ignore
		}
	}

	/**
	 * Get a cookie
	 *
	 * @since 1.0.0
	 *
	 * @param string $name
	 *
	 * @return ?string
	 */
	public static function get( string $name ): ?string {
		if ( empty( $_COOKIE[ $name ] ) ) {
			return null;
		}

		return sanitize_text_field( wp_unslash( $_COOKIE[ $name ] ) ); // phpcs:ignore
	}

	/**
	 * Clear a cookie
	 *
	 * @since 1.0.0
	 *
	 * @param string $name
	 *
	 * @return void
	 */
	public static function clear( string $name ): void {
		self::set( $name, '', time() - YEAR_IN_SECONDS );
		unset( $_COOKIE[ $name ] ); // phpcs:ignore
	}
}
