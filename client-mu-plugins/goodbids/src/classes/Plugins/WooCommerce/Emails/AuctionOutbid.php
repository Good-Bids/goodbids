<?php
/**
 * Auction Outbid: Email the user that was out bid on an auction.
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce\Emails;

use GoodBids\Utilities\Log;

defined( 'ABSPATH' ) || exit;

/**
 * Bidder has been Outbid Email
 *
 * @since 1.0.0
 * @extends Email
 */
class AuctionOutbid extends Email {

	/**
	 * Set email defaults
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();

		$this->id             = 'goodbids_auction_outbid';
		$this->title          = __( 'Auction Outbid', 'goodbids' );
		$this->description    = __( 'Notification email sent to previous bidder when new bid is placed.', 'goodbids' );
		$this->template_html  = 'emails/auction-outbid.php';
		$this->template_plain = 'emails/plain/auction-outbid.php';
		$this->bidder_email   = true;

		$this->trigger_on_bid_order();
	}

	/**
	 * Trigger this email on Bid Order
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function trigger_on_bid_order(): void {
		add_action(
			'goodbids_order_payment_complete',
			function ( int $order_id, int $auction_id ) {
				if ( ! goodbids()->woocommerce->orders->is_bid_order( $order_id ) ) {
					return;
				}

				$auction    = goodbids()->auctions->get( $auction_id );
				$bid_orders = $auction->get_bid_orders( 5 ); // Account for multiple bids in a row.

				// No previous bid orders.
				if ( count( $bid_orders < 2 ) ) {
					return;
				}

				// Get the order before the current one.
				$next  = false;
				$order = false;
				foreach ( $bid_orders as $bid_order ) {
					if ( $bid_order === $order_id ) {
						$next = true;
						continue;
					}

					if ( $next ) {
						$order = wc_get_order( $bid_order );
						break;
					}
				}

				if ( ! $order ) {
					return;
				}

				Log::debug( 'Triggering Outbid email for Auction: ' . $auction_id );
				$this->trigger( $auction, $order->get_user_id() );
			},
			10,
			1
		);
	}

	/**
	 * Get email heading.
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_default_heading(): string {
		return __( 'It’s not too late!', 'goodbids' );
	}

	/**
	 * Get button text
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_default_button_text(): string {
		return __( 'Bid Now', 'goodbids' );
	}

	/**
	 * Set Button URL
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_button_url(): string {
		return '{auction.url}';
	}

	/**
	 * Get email subject.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_default_subject(): string {
		return sprintf(
			/* translators: %s: site title */
			__( '[%s] Yikes, you’ve been outbid', 'goodbids' ),
			'{site_title}',
		);
	}
}
