<?php
/**
 * Free Bid Order Email: Email the user when they place a bid using a Free Bid
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce\Emails;

defined( 'ABSPATH' ) || exit;

/**
 * Free Bid Order Email
 *
 * @since 1.0.0
 * @extends Email
 */
class AuctionFreeBidUsed extends Email {

	/**
	 * Set the unique Email ID
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $id = 'goodbids_auction_free_bid_used';

	/**
	 * Set email defaults
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();

		$this->title          = __( 'Auction Free Bid Used', 'goodbids' );
		$this->description    = __( 'Notification email sent to participant when a free bid is used', 'goodbids' );
		$this->template_html  = 'emails/auction-free-bid-used.php';
		$this->template_plain = 'emails/plain/auction-free-bid-used.php';
		$this->bidder_email   = true;

		$this->trigger_on_free_bid_order();
	}

	/**
	 * Trigger this email when an order is placed using a free bid.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function trigger_on_free_bid_order(): void {
		add_action(
			'goodbids_order_payment_complete',
			function ( int $order_id ) {
				if ( ! goodbids()->woocommerce->orders->is_bid_order( $order_id ) ) {
					return;
				}

				if ( ! goodbids()->woocommerce->orders->is_free_bid_order( $order_id ) ) {
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
			__( '[%1$s] %2$s Free Bid Confirmation', 'goodbids' ),
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
		return __( 'Thanks for using your free bid!', 'goodbids' );
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
