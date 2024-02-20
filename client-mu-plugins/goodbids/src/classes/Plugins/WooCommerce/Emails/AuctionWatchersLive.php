<?php
/**
 * Auction Watchers Live: Email the users that are watching when an Auction goes live.
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
class AuctionWatchersLive extends Email {

	/**
	 * This email is sent to Watchers.
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	protected bool $watcher_email = true;

	/**
	 * Set email defaults
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();

		$this->id             = 'goodbids_auction_watchers_live';
		$this->title          = __( 'Auction Watchers Live', 'goodbids' );
		$this->description    = __( 'Email the users that are watching when an Auction goes live.', 'goodbids' );
		$this->template_html  = 'emails/auction-watchers-live.php';
		$this->template_plain = 'emails/plain/auction-watchers-live.php';
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
			__( '[%1$s] %2$s is live', 'goodbids' ),
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
		return '#';
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
