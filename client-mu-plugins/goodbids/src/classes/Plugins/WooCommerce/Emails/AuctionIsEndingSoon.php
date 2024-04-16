<?php
/**
 * Auction is Ending Soon: Email the Watchers when an Auction is 1 hour from closing.
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce\Emails;

defined( 'ABSPATH' ) || exit;

/**
 * Auction Ending Soon Email
 *
 * @since 1.0.0
 * @extends Email
 */
class AuctionIsEndingSoon extends Email {

	/**
	 * Set the unique Email ID
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $id = 'goodbids_auction_ending_soon';

	/**
	 * Set email defaults
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();

		$this->title          = __( 'Auction is Ending Soon', 'goodbids' );
		$this->description    = __( 'Notification email sent to all Bidders & Watchers when an Auction is near closing.', 'goodbids' );
		$this->template_html  = 'emails/auction-is-ending-soon.php';
		$this->template_plain = 'emails/plain/auction-is-ending-soon.php';
		$this->bidder_email   = true;
		$this->watcher_email  = true;

		$this->cron_check_for_auctions_ending_soon();
	}

	/**
	 * Trigger this email when an Auction is within 4 hours of closing
	 * 
	 * @since 1.0.0
	 * 
	 * @return void
	 */
	private function cron_check_for_auctions_ending_soon(): void {
		add_action(
			Auctions::CRON_AUCTION_ENDING_SOON_CHECK_HOOK,
			function (): void {
				$auctions = goodbids()->auctions->get_auctions_ending_soon_emails();
				if (! $auctions){
					return;
				}
				foreach ($auctions as $auction_id){
					$auction = goodbids()->auctions->get( $auction_id );
					$this->send_to_bidders( $auction );
					$this->send_to_watchers( $auction );
				}
			}
		);
	}

	/**
	 * Get email subject.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_default_subject(): string {
		return sprintf(
			// translators: %1$s: Site Name, %2$s: Auction Title
			__( '[%1$s] %2$s auction is ending soon', 'goodbids' ),
			'{site_title}',
			'{auction.title}'
		);
	}

	/**
	 * Get email heading.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_default_heading(): string {
		return __( 'Don\'t miss out!', 'goodbids' );
	}

	/**
	 * Get button text
	 *
	 * @since 1.0.0
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


}
