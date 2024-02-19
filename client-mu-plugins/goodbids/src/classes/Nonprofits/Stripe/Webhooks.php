<?php
/**
 * GoodBids Stripe Webhooks
 * Incoming Webhooks are sent to {site_url}/wc-api/?wc-api=wc_stripe
 * Must be configured in WooCommerce > Settings > Payments > Stripe > Settings > Edit account keys > Webhook Secret
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Nonprofits\Stripe;

use GoodBids\Nonprofits\Invoice;
use GoodBids\Utilities\Log;
use stdClass;
use WC_Stripe_Webhook_Handler;
use WC_Stripe_Webhook_State;

/**
 * Stripe Webhooks Class
 *
 * @since 1.0.0
 */
class Webhooks extends WC_Stripe_Webhook_Handler {

	private ?stdClass $payload = null;

	/**
	 * Configure Stripe Webhooks
	 * Mostly copied from parent constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		goodbids()->sites->main(
			function () {
				$this->retry_interval = 2;
				$stripe_settings      = get_option( 'woocommerce_stripe_settings', [] );
				$this->testmode       = ( ! empty( $stripe_settings['testmode'] ) && 'yes' === $stripe_settings['testmode'] ) ? true : false;
				$secret_key           = ( $this->testmode ? 'test_' : '' ) . 'webhook_secret';
				$this->secret         = ! empty( $stripe_settings[ $secret_key ] ) ? $stripe_settings[ $secret_key ] : false;

				if ( ! $this->secret ) {
					Log::warning( 'Stripe Webhook Secret not set - Stripe Webhooks will not be supported.' );
				}

				add_action( 'woocommerce_api_wc_stripe', [ $this, 'check_for_webhook' ], 8 );

				// Get/set the time we began monitoring the health of webhooks by fetching it.
				// This should be roughly the same as the activation time of the version of the
				// plugin when this code first appears.
				WC_Stripe_Webhook_State::get_monitoring_began_at();
			}
		);
	}

	/**
	 * Check incoming requests for Stripe Webhook data and process them.
	 * Mostly copied from parent method.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function check_for_webhook(): void {
		if ( ! isset( $_SERVER['REQUEST_METHOD'] )
			|| ( 'POST' !== $_SERVER['REQUEST_METHOD'] )
			|| ! isset( $_GET['wc-api'] )
			|| ( 'wc_stripe' !== $_GET['wc-api'] )
		) {
			return;
		}

		goodbids()->sites->main(
			function () {
				$request_body    = file_get_contents( 'php://input' );
				$request_headers = array_change_key_case( $this->get_request_headers(), CASE_UPPER );

				// Validate it to make sure it is legit.
				$validation_result = $this->validate_request( $request_headers, $request_body );

				if ( WC_Stripe_Webhook_State::VALIDATION_SUCCEEDED === $validation_result ) {
					$this->process_webhook( $request_body );

					$notification = json_decode( $request_body );
					WC_Stripe_Webhook_State::set_last_webhook_success_at( $notification->created );

					status_header( 200 );
					exit;
				} else {
					Log::error( 'Incoming webhook failed validation: ' . $validation_result, compact( 'request_body' ) );
					WC_Stripe_Webhook_State::set_last_webhook_failure_at( time() );
					WC_Stripe_Webhook_State::set_last_error_reason( $validation_result );

					// A webhook endpoint must return a 2xx HTTP status code to prevent future webhook
					// delivery failures.
					// @see https://stripe.com/docs/webhooks/build#acknowledge-events-immediately
					status_header( 204 );
					exit;
				}
			}
		);
	}

	/**
	 * Processes the incoming webhook.
	 *
	 * @since 1.0.0
	 *
	 * @param string $request_body
	 *
	 * @return void
	 */
	public function process_webhook( $request_body ): void {
		$event = json_decode( $request_body );

		if ( empty( $event->type ) || $event->data->object ) {
			Log::error( 'Stripe Webhook missing event type', compact( 'request_body' ) );
			return;
		}

		$this->payload = $event->data->object;
		$context       = [
			'event'   => $event->type,
			'payload' => $this->payload,
		];

		Log::debug( 'Stripe Webhook Received: ' . $event->type, $context );

		match ( $event->type ) {
			'invoice.sent' => $this->process_webhook_sent(),
			'invoice.paid' => $this->process_webhook_paid(),
			default        => $this->process_webhook_other( $event->type )
		};

		parent::process_webhook( $request_body );
	}

	/**
	 * Mark an invoice as Sent.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function process_webhook_sent(): void {
		$invoice = $this->get_invoice();
		if ( ! $invoice ) {
			return;
		}

		if ( $invoice->is_sent() ) {
			Log::warning( 'Invoice already marked as sent: ' . $invoice->get_id() );
			return;
		}

		$invoice->mark_as_sent();
	}

	/**
	 * Mark an invoice as Paid.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function process_webhook_paid(): void {
		$invoice = $this->get_invoice();
		if ( ! $invoice ) {
			return;
		}

		if ( $invoice->is_paid() ) {
			Log::error( 'Invoice already marked as sent: ' . $invoice->get_id() );
			return;
		}

		$invoice->mark_as_paid( $this->payload->charge );
	}

	/**
	 * Log unsupported Stripe Webhooks.
	 *
	 * @since 1.0.0
	 * @param string $event_type
	 * @return void
	 */
	private function process_webhook_other( string $event_type ): void {
		Log::info( 'Unsupported Stripe Webhook Received: ' . $event_type, [ 'payload' => $this->payload ] );
	}

	/**
	 * Get the Invoice from the Stripe Webhook payload.
	 *
	 * @since 1.0.0
	 *
	 * @return ?Invoice
	 */
	private function get_invoice(): ?Invoice {
		if ( empty( $this->payload->metadata->gb_invoice_id ) ) {
			// TODO: Maybe lookup invoice by Stripe Invoice ID?
			Log::error( 'Stripe Webhook missing GoodBids Invoice ID', [ 'payload' => $this->payload ] );
			return null;
		}

		$invoice_id = intval( $this->payload->metadata->gb_invoice_id );
		$invoice    = goodbids()->invoices->get_invoice( $invoice_id );

		if ( ! $invoice ) {
			Log::error( 'Could not retrieve invoice: ' . $invoice_id );
			return null;
		}

		return $invoice;
	}
}
