<?php
/**
 * WooCommerce Stripe Tweaks
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce;

use WC_Order;

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

		// Customize the Transaction Description
		$this->custom_transaction_description();
	}

	/**
	 * Fix an error where nulls are trimmed.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function clear_stripe_nulls(): void {
		$clear_nulls = function ( mixed $settings ) : mixed {
			if ( empty( $settings ) ) {
				return [];
			}

			if ( ! is_array( $settings ) ) {
				return $settings;
			}

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

	/**
	 * Adjust the Transaction Description for Stripe Payments
	 *
	 * @since 1.0.1
	 *
	 * @return void
	 */
	private function custom_transaction_description(): void {
		if ( is_main_site() ) {
			return;
		}

		add_filter(
			'wc_stripe_payment_metadata',
			function( array $metadata, WC_Order $order ): array {
				// Original: %1$s - Order %2$s
				$metadata['description'] = sprintf(
					// translators: %1$s is the Nonprofit name, %2$s is the order number
					__( 'GOODBIDS: %1$s - Order %2$s', 'goodbids' ),
					wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ),
					$order->get_order_number()
				);

				return $metadata;
			},
			10,
			2
		);
	}
}
