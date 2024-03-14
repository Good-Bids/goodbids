<?php
/**
 * Auction Summary Admin: Summary of Auction for Admins
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce\Emails;

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
		$this->description    = __( 'Notification email sent to all site admins when an Auction closes.', 'goodbids' );
		$this->template_html  = 'emails/auction-summary-admin.php';
		$this->template_plain = 'emails/plain/auction-summary-admin.php';
		$this->admin_email    = true;

		$this->trigger_on_auction_end();
	}

	/**
	 * Trigger this email on Auction End.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function trigger_on_auction_end(): void {
		add_action(
			'goodbids_auction_end',
			function ( int $auction_id ) {
				$auction = goodbids()->auctions->get( $auction_id );
				$this->send_to_admins( $auction );
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
		$this->set_admin_vars();
	}
}
