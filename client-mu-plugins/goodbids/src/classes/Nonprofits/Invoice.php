<?php
/**
 * GoodBids Nonprofit Invoice
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Nonprofits;

use GoodBids\Utilities\Log;
use stdClass;
use WP_Post;

/**
 * Invoice Class
 *
 * @since 1.0.0
 */
class Invoice {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const AUCTION_ID_META_KEY = '_auction_id';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const AMOUNT_META_KEY = '_amount';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const DUE_DATE_META_KEY = '_due_date';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const SENT_DATE_META_KEY = '_sent_date';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const PAYMENT_DATE_META_KEY = '_payment_date';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const PAYMENT_ID_META_KEY = '_stripe_payment_id';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const STRIPE_INVOICE_ID_META_KEY = '_stripe_invoice_id';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const STRIPE_INVOICE_NUM_META_KEY = '_stripe_invoice_number';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const STRIPE_INVOICE_URL_META_KEY = '_stripe_invoice_url';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const STATUS_UNSENT = 'Unsent';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const STATUS_UNPAID = 'Unpaid';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const STATUS_OVERDUE = 'Overdue';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const STATUS_PAID = 'Paid';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const INTEGRITY_PASS = 'pass';

	/**
	 * @since 1.0.0
	 * @var ?WP_Post
	 */
	private ?WP_Post $post;

	/**
	 * @since 1.0.0
	 * @var ?int
	 */
	private ?int $auction_id = null;

	/**
	 * @since 1.0.0
	 * @var ?float
	 */
	private ?float $amount = null;

	/**
	 * @since 1.0.0
	 * @var ?string
	 */
	private ?string $due_date = null;

	/**
	 * @since 1.0.0
	 * @var ?string
	 */
	private ?string $sent_date = null;

	/**
	 * @since 1.0.0
	 * @var ?string
	 */
	private ?string $payment_date = null;

	/**
	 * @since 1.0.0
	 * @var ?string
	 */
	private ?string $stripe_payment_id = null;

	/**
	 * @since 1.0.0
	 * @var ?string
	 */
	private ?string $stripe_invoice_id = null;

	/**
	 * @since 1.0.0
	 * @var ?string
	 */
	private ?string $stripe_invoice_number = null;

	/**
	 * @since 1.0.0
	 * @var ?string
	 */
	private ?string $stripe_invoice_url = null;

	/**
	 * @since 1.0.0
	 *
	 * @return ?self
	 */
	public function __construct( int $post_id, ?int $auction_id = null ) {
		if ( get_post_type( $post_id ) !== Invoices::POST_TYPE ) {
			_doing_it_wrong( __METHOD__, 'The post ID provided is not an invoice type', '1.0.0' );
			return null;
		}

		$this->invoice_id = $post_id;
		$this->post       = get_post( $this->invoice_id );

		if ( null !== $auction_id ) {
			if ( ! $this->init( $auction_id ) ) {
				Log::error( 'Could not initialize invoice.', [ 'invoice_id' => $this->get_id(), 'auction_id' => $auction_id ] );
				return null;
			}
		}

		return $this;
	}

	/**
	 * Get the Post Object for an Invoice
	 *
	 * @since 1.0.0
	 *
	 * @return WP_Post
	 */
	public function get_post(): WP_Post {
		return $this->post;
	}

	/**
	 * Get the ID for an Invoice
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_id(): int {
		return $this->invoice_id;
	}

	/**
	 * Initialize a New Invoice
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return bool
	 */
	public function init( int $auction_id ): bool {
		if ( get_post_type( $auction_id ) !== goodbids()->auctions->get_post_type() ) {
			_doing_it_wrong( __METHOD__, 'The post ID provided is not an Auction post type.', '1.0.0' );
			return false;
		}

		// Set the Auction ID.
		$this->set_auction_id( $auction_id );

		// Add Invoice ID to Auction.
		update_post_meta( $this->auction_id, Invoices::INVOICE_ID_META_KEY, $this->get_id() );

		// Set the invoice amount.
		$this->set_amount();

		// Set the invoice due date last.
		$this->set_due_date();

		return true;
	}

	/**
	 * Get the Auction ID for an Invoice
	 *
	 * @since 1.0.0
	 *
	 * @return ?int
	 */
	public function get_auction_id(): ?int {
		if ( ! is_null( $this->auction_id ) ) {
			return $this->auction_id;
		}

		$auction_id = get_post_meta( $this->get_id(), self::AUCTION_ID_META_KEY, true );

		if ( $auction_id ) {
			$this->auction_id = intval( $auction_id );
		}

		return $this->auction_id;
	}

	/**
	 * Set the Invoice Auction ID
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return bool|int
	 */
	private function set_auction_id( int $auction_id ): bool|int {
		$this->auction_id = $auction_id;

		// Set the invoice auction ID.
		return update_post_meta( $this->get_id(), self::AUCTION_ID_META_KEY, $this->auction_id );
	}

	/**
	 * Get the Amount of an Invoice
	 *
	 * @since 1.0.0
	 *
	 * @return float
	 */
	public function get_amount(): float {
		if ( ! is_null( $this->amount ) ) {
			return $this->amount;
		}

		$amount = get_post_meta( $this->get_id(), self::AMOUNT_META_KEY, true );

		if ( ! $amount ) {
			return 0.00;
		}

		$this->amount = $amount;

		return $this->amount;
	}

	/**
	 * Set the invoice amount
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $amount Incoming Amount in Cents from Stripe
	 *
	 * @return bool|int
	 */
	private function set_amount( ?int $amount = null ): bool|int {
		if ( is_null( $amount ) ) {
			$total_raised = goodbids()->auctions->get_total_raised( $this->get_auction_id() );
			$percent      = intval( goodbids()->get_config( 'invoices.percent' ) );
			$amount       = $total_raised * ( $percent / 100 );
		} else {
			$amount = $amount / 100;
		}

		$this->amount = $amount;

		// Set invoice amount.
		return update_post_meta( $this->get_id(), self::AMOUNT_META_KEY, $this->amount );
	}

	/**
	 * Get the Due Date for an Invoice
	 *
	 * @since 1.0.0
	 *
	 * @param ?string $format
	 *
	 * @return string
	 */
	public function get_due_date( ?string $format = 'Y-m-d H:i:s' ): string {
		if ( is_null( $this->due_date ) ) {
			$this->due_date = get_post_meta( $this->get_id(), self::DUE_DATE_META_KEY, true );
		}

		return goodbids()->utilities->format_date_time( $this->due_date, $format );
	}

	/**
	 * Set the Invoice Due Date
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $due_date Incoming GMT Timestamp from Stripe
	 *
	 * @return bool|int
	 */
	private function set_due_date( ?int $due_date = null ): bool|int {
		if ( is_null( $due_date ) ) {
			$payment_terms = intval( goodbids()->get_config( 'invoices.payment-terms-days' ) );

			try {
				$due_date = current_datetime()
					->setTimezone( new \DateTimeZone( 'GMT' ) )
					->add( new \DateInterval( 'P' . $payment_terms . 'D' ) )
					->format( 'Y-m-d H:i:s' );
			} catch ( \Exception $e ) {
				// Log the error.
				Log::error( 'Error setting invoice due date: ' . $e->getMessage(), [ 'invoice_id' => $this->get_id() ] );
				return false;
			}
		} else {
			$due_date = wp_date( 'Y-m-d H:i:s', $due_date, new \DateTimeZone( 'GMT' ) );
		}

		// Set the invoice due date in GMT
		return update_post_meta( $this->get_id(), self::DUE_DATE_META_KEY, $due_date );
	}

	/**
	 * Get the status of the invoice
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_status(): string {
		if ( $this->is_paid() ) {
			return self::STATUS_PAID;
		}

		$due_date = $this->get_due_date();
		$now      = current_time( 'mysql', true );

		if ( $now > $due_date ) {
			return self::STATUS_OVERDUE;
		}

		if ( ! $this->is_sent() ) {
			return self::STATUS_UNSENT;
		}

		return self::STATUS_UNPAID;
	}

	/**
	 * Check if an Invoice has been sent
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_sent(): bool {
		return boolval( $this->get_sent_date() );
	}

	/**
	 * Mark an Invoice as Sent
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function mark_as_sent(): void {
		$this->set_sent_date( current_time( 'mysql', true ) );
	}

	/**
	 * Set the Stripe Invoice ID
	 *
	 * @since 1.0.0
	 *
	 * @param string $stripe_invoice_id
	 *
	 * @return bool|int
	 */
	public function set_stripe_invoice_id( string $stripe_invoice_id ): bool|int {
		$this->stripe_invoice_id = $stripe_invoice_id;
		return update_post_meta( $this->get_id(), self::STRIPE_INVOICE_ID_META_KEY, $this->stripe_invoice_id );
	}

	/**
	 * Returns the Stripe Invoice ID
	 *
	 * @since 1.0.0
	 *
	 * @return ?string
	 */
	public function get_stripe_invoice_id(): ?string {
		if ( $this->stripe_invoice_id ) {
			return $this->stripe_invoice_id;
		}

		$stripe_invoice_id = get_post_meta( $this->get_id(), self::STRIPE_INVOICE_ID_META_KEY, true );

		if ( ! $stripe_invoice_id ) {
			return null;
		}

		$this->stripe_invoice_id = $stripe_invoice_id;
		return $this->stripe_invoice_id;
	}

	/**
	 * Set the Stripe Invoice Number
	 *
	 * @since 1.0.0
	 *
	 * @param string $stripe_invoice_number
	 *
	 * @return bool|int
	 */
	public function set_stripe_invoice_number( string $stripe_invoice_number ): bool|int {
		$this->stripe_invoice_number = $stripe_invoice_number;
		return update_post_meta( $this->get_id(), self::STRIPE_INVOICE_NUM_META_KEY, $this->stripe_invoice_number );
	}

	/**
	 * Returns the Stripe Invoice ID
	 *
	 * @since 1.0.0
	 *
	 * @return ?string
	 */
	public function get_stripe_invoice_number(): ?string {
		if ( $this->stripe_invoice_number ) {
			return $this->stripe_invoice_number;
		}

		$stripe_invoice_number = get_post_meta( $this->get_id(), self::STRIPE_INVOICE_NUM_META_KEY, true );

		if ( ! $stripe_invoice_number ) {
			return null;
		}

		$this->stripe_invoice_number = $stripe_invoice_number;
		return $this->stripe_invoice_number;
	}

	/**
	 * Set the Stripe Invoice URL
	 *
	 * @since 1.0.0
	 *
	 * @param string $stripe_invoice_url
	 *
	 * @return bool|int
	 */
	public function set_stripe_invoice_url( string $stripe_invoice_url ): bool|int {
		$this->stripe_invoice_url = $stripe_invoice_url;
		return update_post_meta( $this->get_id(), self::STRIPE_INVOICE_URL_META_KEY, $this->stripe_invoice_url );
	}

	/**
	 * Returns the Stripe Invoice URL
	 *
	 * @since 1.0.0
	 * @return ?string
	 */
	public function get_stripe_invoice_url(): ?string {
		if ( $this->stripe_invoice_url ) {
			return $this->stripe_invoice_url;
		}

		$stripe_invoice_url = get_post_meta( $this->get_id(), self::STRIPE_INVOICE_URL_META_KEY, true );

		if ( ! $stripe_invoice_url ) {
			return null;
		}

		$this->stripe_invoice_url = $stripe_invoice_url;
		return $this->stripe_invoice_url;
	}

	/**
	 * Get the Stripe Invoice
	 *
	 * @since 1.0.0
	 * @return ?stdClass
	 */
	public function get_stripe_invoice(): ?stdClass {
		return goodbids()->invoices->stripe->lookup_invoice( $this->get_stripe_invoice_id() );
	}

	/**
	 * Check if an Invoice is paid
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_paid(): bool {
		return boolval( $this->get_payment_date() );
	}

	/**
	 * Mark an Invoice as Paid
	 *
	 * @since 1.0.0
	 *
	 * @param string $stripe_payment_id
	 *
	 * @return void
	 */
	public function mark_as_paid( string $stripe_payment_id ): void {
		$this->set_payment_date( current_datetime()->format( 'Y-m-d H:i:s' ) );
		$this->set_payment_id( $stripe_payment_id );
	}

	/**
	 * Set the Invoice Payment Date
	 *
	 * @since 1.0.0
	 *
	 * @param string $payment_date
	 *
	 * @return bool|int
	 */
	public function set_payment_date( string $payment_date ): bool|int {
		$this->payment_date = $payment_date;
		return update_post_meta( $this->get_id(), self::PAYMENT_DATE_META_KEY, $this->payment_date );
	}

	/**
	 * Get the Date when Invoice was Paid.
	 *
	 * @since 1.0.0
	 *
	 * @param string $format
	 *
	 * @return ?string
	 */
	public function get_payment_date( string $format = 'n/j/Y' ): ?string {
		if ( $this->payment_date ) {
			return goodbids()->utilities->format_date_time( $this->payment_date, $format );
		}

		$payment_date = get_post_meta( $this->get_id(), self::PAYMENT_DATE_META_KEY, true );

		if ( ! $payment_date ) {
			return null;
		}

		$this->payment_date = $payment_date;

		return goodbids()->utilities->format_date_time( $this->payment_date, $format );
	}

	/**
	 * Set the Stripe Payment ID
	 *
	 * @since 1.0.0
	 *
	 * @param string $stripe_payment_id
	 *
	 * @return bool|int
	 */
	public function set_payment_id( string $stripe_payment_id ): bool|int {
		$this->stripe_payment_id = $stripe_payment_id;
		return update_post_meta( $this->get_id(), self::PAYMENT_ID_META_KEY, $this->stripe_payment_id );
	}

	/**
	 * Get the Stripe Payment ID.
	 *
	 * @since 1.0.0
	 *
	 * @return ?string
	 */
	public function get_payment_id(): ?string {
		if ( $this->stripe_payment_id ) {
			return $this->stripe_payment_id;
		}

		$stripe_payment_id = get_post_meta( $this->get_id(), self::PAYMENT_ID_META_KEY, true );

		if ( ! $stripe_payment_id ) {
			return null;
		}

		$this->stripe_payment_id = $stripe_payment_id;

		return $this->stripe_payment_id;
	}

	/**
	 * Reset Payment Data (if integrity check fails)
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function reset_payment(): void {
		$this->stripe_payment_id = null;
		$this->payment_date      = null;

		delete_post_meta( $this->get_id(), self::PAYMENT_ID_META_KEY );
		delete_post_meta( $this->get_id(), self::PAYMENT_DATE_META_KEY );
	}

	/**
	 * Set the Invoice Sent Date
	 *
	 * @since 1.0.0
	 *
	 * @param string $sent_date
	 *
	 * @return bool|int
	 */
	public function set_sent_date( string $sent_date ): bool|int {
		$this->sent_date = $sent_date;
		return update_post_meta( $this->get_id(), self::SENT_DATE_META_KEY, $this->sent_date );
	}

	/**
	 * Get the Date when Invoice was Sent.
	 *
	 * @since 1.0.0
	 *
	 * @param string $format
	 *
	 * @return ?string
	 */
	public function get_sent_date( string $format = 'n/j/Y' ): ?string {
		if ( $this->sent_date ) {
			return goodbids()->utilities->format_date_time( $this->sent_date, $format );
		}

		$sent_date = get_post_meta( $this->get_id(), self::SENT_DATE_META_KEY, true );

		if ( ! $sent_date ) {
			return null;
		}

		$this->sent_date = $sent_date;

		return goodbids()->utilities->format_date_time( $this->sent_date, $format );
	}

	/**
	 * Perform an Integrity Check against the Stripe Invoice data
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function integrity_check(): array {
		$stripe_invoice = $this->get_stripe_invoice();

		$report  = [];
		$mapping = [
			'amount_due' => 'set_amount',
			'paid'       => 'reset_payment',
			'due_date'   => 'set_due_date',
			'number'     => 'set_stripe_invoice_number',
		];
		$values  = [
			'amount_due' => intval( $this->get_amount() * 100 ),
			'paid'       => $this->is_paid(),
			'due_date'   => strtotime( $this->get_due_date() ),
			'number'     => $this->get_stripe_invoice_number(),
		];

		foreach ( $mapping as $field => $method ) {
			if ( $stripe_invoice->$field !== $values[ $field ] ) {
				$stripe_value = $stripe_invoice->$field;

				if ( is_null( $stripe_value ) ) {
					$stripe_value = '[null]';
				} elseif ( is_bool( $stripe_value ) ) {
					$stripe_value = intval( $stripe_value );
				} elseif ( is_array( $stripe_value ) ) {
					$stripe_value = print_r( $stripe_value, true ); // phpcs:ignore
				}

				$report[ $field ] = [
					'stripe'  => $stripe_value,
					'invoice' => $values[ $field ],
				];

				if ( $method ) {
					$this->$method( $stripe_invoice->$field );
				}
			} else {
				$report[ $field ] = self::INTEGRITY_PASS;
			}
		}

		return $report;
	}
}
