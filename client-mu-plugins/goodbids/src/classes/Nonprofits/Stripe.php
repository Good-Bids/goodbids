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
	 * The Stripe Customer ID
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private string $customer_id = '';

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
		if ( ! woocommerce_gateway_stripe() ) {
			Log::warning( 'Could not initialize the Stripe gateway.' );
			return;
		}

		if ( ! \WC_Stripe_API::get_secret_key() ) {
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
	 * @param bool    $send_invoice
	 *
	 * @return ?string
	 */
	public function create_invoice( Invoice $invoice, bool $send_invoice = true ): ?string {
		if ( ! $this->initialized ) {
			return null;
		}

		// Make sure we have a customer.
		if ( ! $this->get_customer_id() ) {
			if ( ! $this->create_customer() ) {
				return null;
			}
		}

		$invoice_id = $this->initialize_invoice( $invoice );

		if ( ! $invoice_id ) {
			return null;
		}

		if ( ! $this->add_invoice_item( $invoice ) ) {
			Log::error( 'Could not add item to invoice', compact( 'invoice' ) );
			return null;
		}

		if ( $send_invoice && ! $this->send_invoice( $invoice ) ) {
			Log::warning( 'Could not send invoice', compact( 'invoice' ) );
		}

		return $invoice_id;
	}

	/**
	 * Get the Stripe Customer ID if it exists.
	 *
	 * @since 1.0.0
	 *
	 * @return ?string
	 */
	private function get_customer_id(): ?string {
		if ( $this->customer_id ) {
			return $this->customer_id;
		}

		$customer_id = get_site_option( self::STRIPE_CUSTOMER_ID_OPT );

		if ( ! $customer_id ) {
			return null;
		}

		// TODO: Validate the customer ID.

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

	/**
	 * Initialize a new Stripe Invoice
	 *
	 * @since 1.0.0
	 *
	 * @param Invoice $invoice
	 *
	 * @return ?string
	 */
	private function initialize_invoice( Invoice $invoice ): ?string {
		if ( $invoice->get_stripe_invoice_id() ) {
			Log::warning( 'Stripe Invoice already exists.', compact( 'invoice' ) );
			return $invoice->get_stripe_invoice_id();
		}

		$params = [
			'customer'          => $this->get_customer_id(),
			'collection_method' => 'send_invoice',
			'description'       => get_the_title( $invoice->get_auction_id() ),
			'days_until_due'    => intval( goodbids()->get_config( 'invoices.payment-terms-days' ) ),
		];

		$invoice_id = goodbids()->sites->swap(
			function() use ( $params ): ?string {
				try {
					$response = WC_Stripe_API::request( $params, 'invoices' );
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

		if ( ! $invoice_id ) {
			Log::error( 'Unable to create a Stripe customer.' );
			return false;
		}

		$invoice->set_stripe_invoice_id( $invoice_id );

		return $invoice_id;
	}

	/**
	 * Adds an item to a Stripe Invoice
	 *
	 * @since 1.0.0
	 *
	 * @param Invoice $invoice
	 *
	 * @return bool
	 */
	private function add_invoice_item( Invoice $invoice ): bool {
		$params = [
			'customer' => $this->get_customer_id(),
			'invoice'  => $invoice->get_stripe_invoice_id(),
			'amount'   => $invoice->get_amount(),
		];

		$item_id = goodbids()->sites->swap(
			function() use ( $params ): ?string {
				try {
					$response = WC_Stripe_API::request( $params, 'invoiceitems' );
				} catch ( \WC_Stripe_Exception $e ) {
					Log::error( 'Could not create a Stripe invoice item: ' . $e->getMessage() );
					return null;
				}

				if ( ! empty( $response->error ) ) {
					Log::error( 'Error creating Stripe invoice item: ' . $response->error->message );
					return null;
				}

				return $response->id;
			},
			get_main_site_id()
		);

		if ( ! $item_id ) {
			Log::error( 'Unable to create a Stripe invoice item.' );
			return false;
		}

		return true;
	}

	/**
	 * Send the Invoice
	 *
	 * @since 1.0.0
	 *
	 * @param Invoice $invoice
	 *
	 * @return bool
	 */
	public function send_invoice( Invoice $invoice ): bool {
		$invoice_id = $invoice->get_stripe_invoice_id();

		$response = goodbids()->sites->swap(
			function() use ( $invoice_id ): ?string {
				try {
					$response = WC_Stripe_API::request( [], sprintf( 'invoices/%s/send', $invoice_id ) );
				} catch ( \WC_Stripe_Exception $e ) {
					Log::error( 'Could not send a Stripe invoice: ' . $e->getMessage() );
					return null;
				}

				if ( ! empty( $response->error ) ) {
					Log::error( 'Error sending Stripe invoice: ' . $response->error->message );
					return null;
				}

				return $response->id;
			},
			get_main_site_id()
		);

		if ( ! $response ) {
			Log::error( 'Unable to send a Stripe invoice.' );
			return false;
		}

		return true;
	}
}
