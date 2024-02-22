<?php
/**
 * Auction is Live: Email the Watchers when an Auction goes live.
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce\Emails;

use GoodBids\Auctions\Auction;
use GoodBids\Utilities\Log;

defined( 'ABSPATH' ) || exit;

/**
 * Auction Starting Email
 *
 * @since 1.0.0
 * @extends Email
 */
class AuctionIsLive extends Email {

	/**
	 * Set email defaults
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();

		$this->id             = 'goodbids_auction_live';
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
				Log::debug( 'Triggering Auction is Live emails for Auction: ' . $auction_id );
				$auction = goodbids()->auctions->get( $auction_id );
				$this->send_to_watchers( $auction );
			},
			10,
			2
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

	/**
	 * Trigger the Email
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $object
	 * @param ?int  $user_id
	 *
	 * @return void
	 */
	public function trigger( mixed $object = null, ?int $user_id = null ): void{
		if ( ! $object instanceof Auction ) {
			return;
		}

		parent::trigger( $object, $user_id );
	}
}
