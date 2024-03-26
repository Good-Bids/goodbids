<?php
/**
 * Auction Paid Bid Order: Email the user when they place a Paid Bid Order.
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce\Emails;

defined( 'ABSPATH' ) || exit;

/**
 * Auction Paid Bid Placed
 *
 * @since 1.0.0
 * @extends Email
 */
class AuctionPaidBidPlaced extends Email {

	/**
	 * Set the unique Email ID
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $id = 'goodbids_auction_paid_bid_placed';

	/**
	 * Set email defaults
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();

		$this->title          = __( 'Auction Paid Bid Placed', 'goodbids' );
		$this->description    = __( 'Notification email sent to participant when a bid is placed on an auction.', 'goodbids' );
		$this->template_html  = 'emails/auction-paid-bid-placed.php';
		$this->template_plain = 'emails/plain/auction-paid-bid-placed.php';
		$this->bidder_email   = true;

		$this->trigger_on_paid_bid_order();
	}

	/**
	 * Trigger this email when a Paid bid is placed.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function trigger_on_paid_bid_order(): void {
		add_action(
			'goodbids_order_payment_complete',
			function ( int $order_id ) {
				if ( ! goodbids()->woocommerce->orders->is_bid_order( $order_id ) ) {
					return;
				}

				if ( goodbids()->woocommerce->orders->is_free_bid_order( $order_id ) ) {
					return;
				}

				$order = wc_get_order( $order_id );
				$this->trigger( $order, get_current_user_id() );
			}
		);
	}

	/**
	 * Get email subject.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_default_subject(): string {
		return sprintf(
			/* translators: %1$s: site title, %2$s: auction title */
			__( '[%1$s] %2$s Bid Confirmation', 'goodbids' ),
			'{site_title}',
			'{auction.title}'
		);
	}

	/**
	 * Get email heading.
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_default_heading(): string {
		return __( 'Thanks for bidding!', 'goodbids' );
	}

	/**
	 * Get button text
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_default_button_text(): string {
		return __( 'Track this Auction', 'goodbids' );
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
}
