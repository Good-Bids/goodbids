<?php
/**
 * Auction Ending Email: Email the users when a free bid is used.
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce\Emails;

use GoodBids\Utilities\Log;

defined( 'ABSPATH' ) || exit;

/**
 * Auction Ending Email
 *
 * @since 1.0.0
 * @extends Email
 */
class AuctionFreeBidUsed extends Email {

	/**
	 * Set email defaults
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();

		$this->id             = 'goodbids_auction_free_bid_used';
		$this->title          = __( 'Auction Free Bid Used', 'goodbids' );
		$this->description    = __( 'Notification email sent to participant when a free bid is used', 'goodbids' );
		$this->template_html  = 'emails/auction-free-bid-used.php';
		$this->template_plain = 'emails/plain/auction-free-bid-used.php';
		$this->watcher_email  = true;
		$this->bidder_email   = true;

		$this->trigger_on_free_bid_used();
	}

	/**
	 * Add a custom footer.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function init_customizations(): void {
		add_action( 'woocommerce_email_footer', [ $this, 'all_auctions_html' ], 7, 2 );
	}

	/**
	 * Remove custom footer.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function remove_customizations(): void {
		remove_action( 'woocommerce_email_footer', [ $this, 'all_auctions_html' ], 7 );
	}

	/**
	 * Trigger this email on free bid used.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function trigger_on_free_bid_used(): void {
		// TODO fire Trigger
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
			__( '[%1$s] %2$s Confirmation', 'goodbids' ),
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

	/**
	 * Display Link to All Auctions
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function all_auctions_html(): void {
		printf(
			'<p style="text-align:center;">%s <a href="%s">%s</a>',
			esc_html__( 'Want to support another great GOODBIDS cause?', 'goodbids' ),
			'{auctions_url}',
			esc_html__( 'View All Auctions', 'goodbids' )
		);
	}
}
