<?php
/**
 * GoodBids Nonprofit Invoice
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Nonprofits;

use GoodBids\Utilities\Log;

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
	const PAYMENT_META_KEY = '_payment';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const STATUS_UNPAID = 'Unpaid';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const STATUS_PAID = 'Paid';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const STATUS_OVERDUE = 'Overdue';

	/**
	 * @since 1.0.0
	 * @var ?int
	 */
	private ?int $invoice_id;

	/**
	 * @since 1.0.0
	 * @var ?\WP_Post
	 */
	private ?\WP_Post $post;

	/**
	 * @since 1.0.0
	 * @var ?int
	 */
	private ?int $auction_id;

	/**
	 * @since 1.0.0
	 * @var ?float
	 */
	private ?float $amount;

	/**
	 * @since 1.0.0
	 * @var ?string
	 */
	private ?string $due_date;

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
				Log::error( 'Could not initialize invoice.', [ 'invoice_id' => $this->get_id() ] );
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
	 * @return \WP_Post
	 */
	public function get_post(): \WP_Post {
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

		// Set the invoice amount.
		$this->set_amount();

		// Set the invoice due date last.
		return $this->set_due_date();
	}

	/**
	 * Get the Auction ID for an Invoice
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_auction_id(): int {
		if ( ! is_null( $this->auction_id ) ) {
			return $this->auction_id;
		}

		$this->auction_id = get_post_meta( $this->get_id(), self::AUCTION_ID_META_KEY, true );

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

		// Add Invoice ID to Auction.
		update_post_meta( $this->auction_id, Invoices::INVOICE_ID_META_KEY, $this->get_id() );

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
	 * @return bool|int
	 */
	private function set_amount(): bool|int {
		$total_raised = goodbids()->auctions->get_total_raised( $this->get_auction_id() );
		$percent      = goodbids()->get_config( 'invoices.percent' );
		$amount       = $total_raised * ( $percent / 100 );

		// Set invoice amount.
		return update_post_meta( $this->get_id(), self::AUCTION_ID_META_KEY, $amount );
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
	 * @return bool|int
	 */
	private function set_due_date(): bool|int {
		$payment_terms = goodbids()->get_config( 'invoices.payment-terms' );

		try {
			$due_date = current_datetime()->add( new \DateInterval( 'P' . $payment_terms ) )->format( 'Y-m-d H:i:s' );
		} catch ( \Exception $e ) {
			// Log the error.
			Log::error( 'Error setting invoice due date: ' . $e->getMessage(), [ 'invoice_id' => $this->get_id() ] );
			return false;
		}

		// Set the invoice due date
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
		$now      = current_datetime()->format( 'Y-m-d H:i:s' );

		if ( $now > $due_date ) {
			return self::STATUS_OVERDUE;
		}

		return self::STATUS_UNPAID;
	}

	/**
	 * Check if an Invoice is paid
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_paid(): bool {
		return boolval( get_post_meta( $this->get_id(), self::PAYMENT_META_KEY, true ) );
	}
}
