<?php
/**
 * WooCommerce Stripe Tweaks
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce;

/**
 * Stripe Tweaks
 *
 * @since 1.0.0
 */
class Stripe {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Change nulls to empty strings.
		$this->clear_stripe_nulls();
	}

	/**
	 * Fix an error where nulls are trimmed.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function clear_stripe_nulls(): void {
		$clear_nulls = function ( array $settings ) : array {
			foreach ( $settings as $key => $value ) {
				if ( is_null( $value ) ) {
					$settings[ $key ] = '';
				}
			}
			return $settings;
		};

		add_filter( 'option_woocommerce_stripe_settings', $clear_nulls, 50 );
		add_filter( 'default_option_woocommerce_stripe_settings', $clear_nulls, 50 );
	}
}
