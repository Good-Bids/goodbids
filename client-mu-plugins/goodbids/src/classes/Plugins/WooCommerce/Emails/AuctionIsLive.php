<?php
/**
 * Auction is Live: Email the Watchers when an Auction goes live.
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce\Emails;

defined( 'ABSPATH' ) || exit;

/**
 * Auction Starting Email
 *
 * @since 1.0.0
 * @extends Email
 */
class AuctionIsLive extends Email {

	/**
	 * Set the unique Email ID
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $id = 'goodbids_auction_live';

	/**
	 * Set email defaults
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();

		$this->title          = __( 'Auction is Live', 'goodbids' );
		$this->description    = __( 'Notification email sent to all Watchers when an Auction goes live.', 'goodbids' );
		$this->template_html  = 'emails/auction-is-live.php';
		$this->template_plain = 'emails/plain/auction-is-live.php';
		$this->watcher_email  = true;

		$this->trigger_on_auction_start();
	}

	/**
	 * Trigger send when Auction Starts
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function trigger_on_auction_start(): void {
		add_action(
			'goodbids_auction_start',
			function ( int $auction_id ) {
				$auction = goodbids()->auctions->get( $auction_id );
				$this->send_to_watchers( $auction );
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
			__( '[%1$s] %2$s auction is live', 'goodbids' ),
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
		return __( 'Ready to GOODBID?', 'goodbids' );
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
