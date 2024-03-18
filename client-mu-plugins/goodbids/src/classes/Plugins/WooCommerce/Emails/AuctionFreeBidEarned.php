<?php
/**
 * Auction Free Bid Earned: Email the users when a free Bid is earned.
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce\Emails;

defined( 'ABSPATH' ) || exit;

/**
 * Auction Free Bid Earned Email
 *
 * @since 1.0.0
 * @extends Email
 */
class AuctionFreeBidEarned extends Email {

	/**
	 * Set email defaults
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();

		$this->id             = 'goodbids_free_bid_earned';
		$this->title          = __( 'Auction Free Bid Earned', 'goodbids' );
		$this->description    = __( 'Notification email sent to participant when free bid is earned.', 'goodbids' );
		$this->template_html  = 'emails/auction-free-bid-earned.php';
		$this->template_plain = 'emails/auction-free-bid-earned.php';
		$this->bidder_email   = true;

		$this->trigger_on_free_bid_earned();
	}

	/**
	 * Trigger this email on free bid earned.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function trigger_on_free_bid_earned(): void {
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
			/* translators: %1$s: site title */
			__( '[%1$s] Congratulations, you earned a Free Bid!', 'goodbids' ),
			'{site_title}',
		);
	}

	/**
	 * Get email heading.
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_default_heading(): string {
		return __( 'Ready to GOODBID for free?', 'goodbids' );
	}

	/**
	 * Get button text
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_default_button_text(): string {
		return __( 'View Free Bids', 'goodbids' );
	}

	/**
	 * Set Button URL
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_button_url(): string {
		// TODO update this the right link
		return '{free_bids.url}';
	}
}
