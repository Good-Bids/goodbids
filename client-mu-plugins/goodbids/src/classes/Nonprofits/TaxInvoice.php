<?php
/**
 * GoodBids Nonprofit Tax Invoice
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Nonprofits;

use GoodBids\Utilities\Log;
use WC_Order;

/**
 * Invoice Class
 *
 * @since 1.0.0
 */
class TaxInvoice extends Invoice {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const TYPE = 'Tax';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const ORDER_ID_META_KEY = '_order_id';

	/**
	 * @since 1.0.0
	 * @var ?int
	 */
	private ?int $order_id = null;

	/**
	 * Initialize a New Tax Invoice. This will also connect the Auction to this Invoice.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 * @param int $order_id
	 * @param bool $check_existing
	 *
	 * @return bool
	 */
	public function init_tax( int $auction_id, int $order_id, bool $check_existing = true ): bool {
		if ( get_post_type( $auction_id ) !== goodbids()->auctions->get_post_type() ) {
			_doing_it_wrong( __METHOD__, 'The post ID provided is not an Auction post type.', '1.0.0' );
			return false;
		}

		$auction = goodbids()->auctions->get( $auction_id );

		if ( $check_existing && $auction->get_tax_invoice_id() ) {
			_doing_it_wrong( __METHOD__, 'Invoice already exists for Auction.', '1.0.0' );
			return false;
		}

		// Set the Auction and Order ID.
		$this->set_auction_id( $auction_id );
		$this->set_order_id( $order_id );

		// Add Invoice ID to Auction.
		update_post_meta( $this->auction_id, Invoices::TAX_INVOICE_ID_META_KEY, $this->get_id() );

		// Set the Default Values.
		$this->use_default_values();

		return true;
	}

	/**
	 * Check if the Invoice is valid
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_valid(): bool {
		return ! is_null( $this->post ) && $this->get_auction_id() && $this->get_order_id();
	}

	/**
	 * Get the Order ID for a Tax Invoice
	 *
	 * @since 1.0.0
	 *
	 * @return ?int
	 */
	public function get_order_id(): ?int {
		if ( ! is_null( $this->order_id ) ) {
			return $this->order_id;
		}

		$order_id = get_post_meta( $this->get_id(), self::ORDER_ID_META_KEY, true );

		if ( $order_id ) {
			$this->order_id = intval( $order_id );
		}

		return $this->order_id;
	}

	/**
	 * Get the Tax Invoice Order
	 *
	 * @since 1.0.0
	 *
	 * @return ?WC_Order
	 */
	public function get_order(): ?WC_Order {
		$order_id = $this->get_order_id();
		if ( ! $order_id ) {
			return null;
		}

		return wc_get_order( $order_id );
	}

	/**
	 * Set the Tax Invoice Order ID
	 *
	 * @since 1.0.0
	 *
	 * @param int $order_id
	 *
	 * @return bool|int
	 */
	protected function set_order_id( int $order_id ): bool|int {
		$this->order_id = $order_id;

		// Set the tax invoice order ID.
		return update_post_meta( $this->get_id(), self::ORDER_ID_META_KEY, $this->order_id );
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
	protected function set_amount( ?int $amount = null ): bool|int {
		if ( is_null( $amount ) ) {
			$order  = $this->get_order();
			$amount = $order->get_total_tax( 'edit' );
		} else {
			$amount = $amount / 100;
		}

		$this->amount = $amount;

		// Set invoice amount.
		return update_post_meta( $this->get_id(), self::AMOUNT_META_KEY, $this->amount );
	}

}
