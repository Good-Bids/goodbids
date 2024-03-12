<?php
/**
 * Free Bid Earned: Email the users when a free Bid is earned.
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce\Emails;

use GoodBids\Auctions\FreeBid;
use GoodBids\Plugins\WooCommerce\Account;

defined( 'ABSPATH' ) || exit;

/**
 * Free Bid Earned Email
 *
 * @since 1.0.0
 * @extends Email
 */
class FreeBidEarned extends Email {

	/**
	 * Set email defaults
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();

		$this->id             = 'goodbids_free_bid_earned';
		$this->title          = __( 'Free Bid Earned', 'goodbids' );
		$this->description    = __( 'Notification email sent to participant when free bid is earned.', 'goodbids' );
		$this->template_html  = 'emails/free-bid-earned.php';
		$this->template_plain = 'emails/plain/free-bid-earned.php';
		$this->bidder_email   = true;

		$this->trigger_on_new_free_bid();
	}

	/**
	 * Trigger this email on free bid earned.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function trigger_on_new_free_bid(): void {
		add_action(
			'goodbids_award_free_bid',
			function ( FreeBid $free_bid, int $user_id ) {
				$this->trigger( $free_bid, $user_id );
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
		return wc_get_endpoint_url( Account::FREE_BIDS_SLUG );
	}
}
