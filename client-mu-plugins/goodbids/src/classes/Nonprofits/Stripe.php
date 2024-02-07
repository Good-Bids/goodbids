<?php
/**
 * GoodBids Stripe Invoices
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Nonprofits;

use GoodBids\Utilities\Log;
use WC_Stripe;
use WC_Stripe_API;

/**
 * Stripe Invoices Class
 *
 * @since 1.0.0
 */
class Stripe {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const STRIPE_CUSTOMER_ID_OPT = '_goodbids_stripe_customer_id';

	/**
	 * @since 1.0.0
	 * @var bool
	 */
	private bool $initialized = false;

	/**
	 * Instance of WooCommerce Stripe plugin.
	 *
	 * @since 1.0.0
	 * @var ?WC_Stripe
	 */
	private ?WC_Stripe $stripe = null;

	/**
	 * The Stripe Customer ID
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private string $customer_id = '';

	/**
	 * The Stripe API Secret Key
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private string $secret_key = '';

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action(
			'init',
			function() {
				goodbids()->sites->swap(
					function () {
						if ( ! function_exists( 'woocommerce_gateway_stripe' ) ) {
							Log::warning( 'WooCommerce Stripe gateway not found. Please install or activate the WooCommerce Stripe Gatewway plugin.' );
							return;
						}

						$this->init();
					},
					get_main_site_id()
				);
			}
		);
	}

	/**
	 * Initialize the Stripe gateway
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init(): void {
		$this->stripe = woocommerce_gateway_stripe();

		if ( ! $this->stripe ) {
			Log::warning( 'Could not initialize the Stripe gateway.' );
			return;
		}

		$this->secret_key = \WC_Stripe_API::get_secret_key();

		if ( ! $this->secret_key ) {
			Log::warning( 'Stripe is not configured.' );
			return;
		}

		$this->initialized = true;
	}

	/**
	 * Create a Stripe Invoice
	 *
	 * @since 1.0.0
	 *
	 * @param Invoice $invoice
	 *
	 * @return ?string
	 */
	public function create_invoice( Invoice $invoice ): ?string {
		if ( ! $this->initialized ) {
			return null;
		}

		if ( ! $this->get_customer() ) {
			if ( ! $this->create_customer() ) {
				return null;
			}
		}

		return '';
	}

	/**
	 * Get the Stripe Customer ID if it exists.
	 *
	 * @since 1.0.0
	 *
	 * @return ?string
	 */
	private function get_customer(): ?string {
		if ( $this->customer_id ) {
			return $this->customer_id;
		}

		$customer_id = get_site_option( self::STRIPE_CUSTOMER_ID_OPT );

		if ( ! $customer_id ) {
			return null;
		}

		$this->customer_id = $customer_id;
		return $this->customer_id;
	}

	/**
	 * Create a new Stripe Customer
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function create_customer(): bool {
		$params = [
			'email' => get_bloginfo( 'admin_email' ),
			'name'  => get_bloginfo( 'name' ),
		];

		$customer_id = goodbids()->sites->swap(
			function() use ( $params ): ?string {
				try {
					$response = WC_Stripe_API::request( $params, 'customers' );
				} catch ( \WC_Stripe_Exception $e ) {
					Log::error( 'Could not create a Stripe customer: ' . $e->getMessage() );
					return null;
				}

				if ( ! empty( $response->error ) ) {
					Log::error( 'Error creating Stripe Customer: ' . $response->error->message );
					return null;
				}

				return $response->id;
			},
			get_main_site_id()
		);

		if ( ! $customer_id ) {
			Log::error( 'Unable to create a Stripe customer.' );
			return false;
		}

		$this->customer_id = $customer_id;
		update_site_option( self::STRIPE_CUSTOMER_ID_OPT, $this->customer_id );

		return true;
	}

}
