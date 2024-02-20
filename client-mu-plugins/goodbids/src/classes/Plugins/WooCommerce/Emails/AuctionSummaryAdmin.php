<?php
/**
 * Auction Summary Admin: Summary of Auction for Admins
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce\Emails;

use GoodBids\Auctions\Auction;

defined( 'ABSPATH' ) || exit;

/**
 * Auction Summary Admin email
 *
 * @since 1.0.0
 * @extends Email
 */
class AuctionSummaryAdmin extends Email {

	/**
	 * Set email defaults
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();

		$this->id             = 'goodbids_auction_summary_admin';
		$this->title          = __( 'Auction Summary (Admin)', 'goodbids' );
		$this->description    = __( 'Auction Summary for site admins when an Auction closes.', 'goodbids' );
		$this->template_html  = 'emails/auction-summary-admin.php';
		$this->template_plain = 'emails/plain/auction-summary-admin.php';
		$this->admin_email    = true;
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
		$this->add_email_var( 'auction_estimated_value_formatted', $auction?->get_estimated_value_formatted() );
		$this->add_email_var( 'auction_goal', $auction?->get_goal() );
		$this->add_email_var( 'auction_goal_formatted', $auction?->get_goal_formatted() );
		$this->add_email_var( 'auction_expected_high_bid', $auction?->get_expected_high_bid() );
		$this->add_email_var( 'auction_expected_high_bid_formatted', $auction?->get_expected_high_bid_formatted() );
	}
}
