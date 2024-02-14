<?php
/**
 * GoodBids Stripe Invoices
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Nonprofits;

use GoodBids\Nonprofits\Stripe\Webhooks;
use GoodBids\Utilities\Log;
use stdClass;
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
	 * @var ?Webhooks
	 */
	private ?Webhooks $webhooks = null;

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
	 * The Invoice object
	 *
	 * @since 1.0.0
	 * @var ?Invoice
	 */
	private ?Invoice $invoice = null;

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action(
			'init',
			fn() => goodbids()->sites->main( [ $this, 'init' ] )
		);
	}

	/**
	 * Initialize the Stripe gateway.
	 * This only happens on the main site.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init(): void {
		if ( ! function_exists( 'woocommerce_gateway_stripe' ) ) {
			Log::warning( 'WooCommerce Stripe gateway not found. Please install or activate the WooCommerce Stripe Gateway plugin.' );
			return;
		}

		if ( ! woocommerce_gateway_stripe() ) {
			Log::warning( 'Could not initialize the Stripe gateway.' );
			return;
		}

		if ( ! \WC_Stripe_API::get_secret_key() ) {
			Log::warning( 'Stripe is not configured.' );
			return;
		}

		// Initialize webhooks.
		if ( class_exists( '\WC_Stripe_Webhook_Handler' ) ) {
			$this->webhooks = new Webhooks();
		} else {
			Log::warning( 'Could not initialize Stripe Webhooks.' );
		}

		$this->initialized = true;
	}

	/**
	 * Sets the Invoice for use with Stripe.
	 *
	 * @since 1.0.0
	 *
	 * @param Invoice $invoice
	 *
	 * @return void
	 */
	public function set_invoice( Invoice $invoice ) {
		$this->invoice = $invoice;
	}

	/**
	 * Gets the Invoice for use with Stripe.
	 *
	 * @since 1.0.0
	 *
	 * @return ?Invoice
	 */
	public function get_invoice(): ?Invoice {
		return $this->invoice;
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

		$this->set_invoice( $invoice );

		$context = [ 'invoice' => $this->get_invoice() ];

		// Generate the Invoice.
		$stripe_invoice_id = $this->initialize_invoice();

		if ( ! $stripe_invoice_id ) {
			return null;
		}

		if ( ! $this->add_invoice_item() ) {
			Log::error( 'Could not add item to invoice', $context );
			return null;
		}

		if ( ! $this->finalize_invoice() ) {
			Log::error( 'Could not finalize invoice', $context );
			return null;
		}

		if ( $send_invoice && ! $this->send_invoice() ) {
			Log::warning( 'Could not send invoice', $context );
		}

		return $stripe_invoice_id;
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

		$context     = [ 'invoice' => $this->invoice ];
		$customer_id = get_site_option( self::STRIPE_CUSTOMER_ID_OPT );

		if ( ! $customer_id ) {
			Log::debug( 'No Stripe Customer ID found.', $context );
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
		Log::debug( 'Creating new Stripe Customer.' );

		$context = [ 'invoice' => $this->invoice ];
		$params  = [
			'email' => get_bloginfo( 'admin_email' ),
			'name'  => get_bloginfo( 'name' ),
		];

		$customer_id = goodbids()->sites->main(
			function() use ( $params, $context ): ?string {
				try {
					$response = WC_Stripe_API::request( $params, 'customers' );
				} catch ( \WC_Stripe_Exception $e ) {
					Log::error( 'Could not create a Stripe Customer: ' . $e->getMessage(), $context);
					return null;
				}

				if ( ! empty( $response->error ) ) {
					Log::error( 'Error creating Stripe Customer: ' . $response->error->message, $context );
					return null;
				}

				return $response->id;
			}
		);

		if ( ! $customer_id ) {
			Log::error( 'Unable to create a Stripe Customer.', $context );
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
	 * @return ?string
	 */
	private function initialize_invoice(): ?string {
		if ( ! $this->get_invoice() ) {
			Log::error( 'No Invoice found.' );
			return null;
		}

		$context = [ 'invoice' => $this->get_invoice() ];

		if ( $this->invoice->get_stripe_invoice_id() ) {
			Log::warning( 'Stripe Invoice already exists.', $context );
			return $this->invoice->get_stripe_invoice_id();
		}

		Log::debug( 'Creating new Stripe Invoice.', $context );

		$params = [
			'customer'          => $this->get_customer_id(),
			'collection_method' => 'send_invoice',
			'description'       => get_the_title( $this->invoice->get_auction_id() ),
			'days_until_due'    => intval( goodbids()->get_config( 'invoices.payment-terms-days' ) ),
			'metadata'          => [
				'gb_invoice_id' => $this->invoice->get_id(),
				'gb_auction_id' => $this->invoice->get_auction_id(),
			],

			// This allows the invoice to be created without any items, even though the documentation says "exclude" is the default.
			'pending_invoice_items_behavior' => 'exclude',
		];

		$stripe_invoice_id = goodbids()->sites->main(
			function() use ( $params, $context ): ?string {
				try {
					$response = WC_Stripe_API::request( $params, 'invoices' );
				} catch ( \WC_Stripe_Exception $e ) {
					Log::error( 'Could not create Stripe Invoice: ' . $e->getMessage(), $context );
					return null;
				}

				if ( ! empty( $response->error ) ) {
					Log::error( 'Error creating Stripe Invoice: ' . $response->error->message, $context );
					return null;
				}

				return $response->id;
			}
		);

		if ( ! $stripe_invoice_id ) {
			Log::error( 'Unable to create Stripe Invoice.', $context );
			return false;
		}

		$this->invoice->set_stripe_invoice_id( $stripe_invoice_id );

		return $stripe_invoice_id;
	}

	/**
	 * Adds an item to a Stripe Invoice
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function add_invoice_item(): bool {
		if ( ! $this->get_invoice() ) {
			Log::error( 'No Invoice found.' );
			return false;
		}

		$context = [ 'invoice' => $this->get_invoice() ];

		Log::debug( 'Creating new Stripe Invoice Item.', $context );

		$params = [
			'customer' => $this->get_customer_id(),
			'invoice'  => $this->invoice->get_stripe_invoice_id(),
			'amount'   => $this->invoice->get_amount() * 100, // Convert to cents.
		];

		$item_id = goodbids()->sites->main(
			function() use ( $params, $context ): ?string {
				try {
					$response = WC_Stripe_API::request( $params, 'invoiceitems' );
				} catch ( \WC_Stripe_Exception $e ) {
					Log::error( 'Could not create Stripe Invoice Item: ' . $e->getMessage(), $context );
					return null;
				}

				if ( ! empty( $response->error ) ) {
					Log::error( 'Error creating Stripe Invoice Item: ' . $response->error->message, $context );
					return null;
				}

				return $response->id;
			}
		);

		if ( ! $item_id ) {
			Log::error( 'Unable to create Stripe Invoice Item.', $context );
			return false;
		}

		return true;
	}

	/**
	 * Finalize the invoice to prepare it for sending.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function finalize_invoice(): bool {
		if ( ! $this->get_invoice() ) {
			Log::error( 'No Invoice found.' );
			return false;
		}

		$context           = [ 'invoice' => $this->get_invoice() ];
		$stripe_invoice_id = $this->invoice->get_stripe_invoice_id();

		if ( ! $stripe_invoice_id ) {
			Log::error( 'Can\'t finalize Stripe Invoice. No Stripe Invoice ID found.', $context );
			return false;
		}

		Log::debug( 'Finalizing Stripe Invoice.', $context );

		$stripe_invoice = goodbids()->sites->main(
			function() use ( $stripe_invoice_id, $context ): ?stdClass {
				try {
					$response = WC_Stripe_API::request( [], sprintf( 'invoices/%s/finalize', $stripe_invoice_id ) );
				} catch ( \WC_Stripe_Exception $e ) {
					Log::error( 'Could not finalize Stripe Invoice: ' . $e->getMessage(), $context );
					return null;
				}

				if ( ! empty( $response->error ) ) {
					Log::error( 'Error finalizing Stripe Invoice: ' . $response->error->message, $context );
					return null;
				}

				return $response;
			}
		);

		if ( ! $stripe_invoice ) {
			Log::error( 'Unable to finalize Stripe Invoice.', $context );
			return false;
		}

		$this->invoice->set_stripe_invoice_number( $stripe_invoice->number );

		return true;
	}

	/**
	 * Send the Invoice
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function send_invoice(): bool {
		if ( ! $this->get_invoice() ) {
			Log::error( 'No Invoice found.' );
			return false;
		}

		$context           = [ 'invoice' => $this->get_invoice() ];
		$stripe_invoice_id = $this->invoice->get_stripe_invoice_id();

		if ( ! $stripe_invoice_id ) {
			Log::error( 'Can\'t send Stripe Invoice. No Stripe Invoice ID found.', $context );
			return false;
		}

		Log::debug( 'Sending Stripe Invoice.', $context );

		$invoice_url = goodbids()->sites->main(
			function() use ( $stripe_invoice_id, $context ): ?string {
				try {
					$response = WC_Stripe_API::request( [], sprintf( 'invoices/%s/send', $stripe_invoice_id ) );
				} catch ( \WC_Stripe_Exception $e ) {
					Log::error( 'Could not send Stripe Invoice: ' . $e->getMessage(), $context );
					return null;
				}

				if ( ! empty( $response->error ) ) {
					Log::error( 'Error sending Stripe Invoice: ' . $response->error->message, $context );
					return null;
				}

				return $response->hosted_invoice_url;
			}
		);

		if ( ! $invoice_url ) {
			Log::error( 'Unable to send Stripe Invoice.', $context );
			return false;
		}

		$this->invoice->set_stripe_invoice_url( $invoice_url );

		return true;
	}

	/**
	 * Get all Stripe Invoices
	 *
	 * TODO: Verify invoices coming from Stripe aren't paginated.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_invoices(): array {
		$stripe_invoices = goodbids()->sites->main(
			function() {
				try {
					$response = WC_Stripe_API::request( [], 'invoices', 'GET' );
				} catch ( \WC_Stripe_Exception $e ) {
					Log::error( 'Could not get Stripe Invoices: ' . $e->getMessage() );
					return [];
				}

				if ( ! empty( $response->error ) ) {
					Log::error( 'Error getting Stripe Invoices: ' . $response->error->message );
					return [];
				}

				return $response->data;
			}
		);

		if ( ! $stripe_invoices ) {
			Log::error( 'Unable to get Stripe Invoices.' );
			return [];
		}

		return $stripe_invoices;
	}

	/**
	 * Look up a Stripe Invoice by ID.
	 *
	 * @since 1.0.0
	 *
	 * @param string $stripe_invoice_id
	 *
	 * @return ?stdClass
	 */
	public function lookup_invoice( string $stripe_invoice_id ): ?stdClass {
		$stripe_invoice = goodbids()->sites->main(
			function() use ( $stripe_invoice_id ): ?stdClass {
				try {
					$response = WC_Stripe_API::request( [], sprintf( 'invoices/%s', $stripe_invoice_id ), 'GET' );
				} catch ( \WC_Stripe_Exception $e ) {
					Log::error( 'Could not lookup Stripe Invoice: ' . $e->getMessage(), compact( 'stripe_invoice_id' ) );
					return null;
				}

				if ( ! empty( $response->error ) ) {
					Log::error( 'Error looking up Stripe Invoice: ' . $response->error->message, compact( 'stripe_invoice_id' ) );
					return null;
				}

				return $response;
			}
		);

		if ( ! $stripe_invoice ) {
			Log::error( 'Unable to lookup Stripe Invoice.', compact( 'stripe_invoice_id' ) );
			return null;
		}

		return $stripe_invoice;
	}
}
