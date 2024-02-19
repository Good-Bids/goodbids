<?php
/**
 * Auction Watchers Live: Email the users that are watching when an auction goes live.
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce\Emails;

use GoodBids\Auctions\Auction;

defined( 'ABSPATH' ) || exit;

/**
 * Auction Watchers Live extend the custom BaseEmail class
 *
 * @since 1.0.0
 * @extends Email
 */
class AuctionAdminSummary extends Email {

	/**
	 * Set email defaults
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();

		$this->id             = 'goodbids_auction_admin_summary';
		$this->title          = __( 'Auction Admin Summary', 'goodbids' );
		$this->description    = __( 'Notification email sent to admins when an auction closes.', 'goodbids' );
		$this->template_html  = 'emails/auction-admin-summary.php';
		$this->template_plain = 'emails/plain/auction-admin-summary.php';
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
			__( '[%1$s] Auction summary for %2$s', 'goodbids' ),
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
		return sprintf(
			/* translators: %1$s: auction total raised, %2$s: site title */
			__( '%1$s raised for %2$s!', 'goodbids' ),
			'{auction.total_raised}',
			'{site_title}'
		);
	}

	/**
	 * Add custom vars
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init_vars(): void {
		$auction = $this->object instanceof Auction ? $this->object : null;
		$this->add_email_var( 'auction_estimated_value', $auction?->get_estimated_value() );
		$this->add_email_var( 'auction_goal', $auction?->get_goal() );
		$this->add_email_var( 'auction_expected_high_bid', $auction?->get_expected_high_bid() );
	}
}
