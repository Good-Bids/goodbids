<?php
/**
 * Auction Admin Live: Email the site Admin when an Auction goes live.
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce\Emails;

defined( 'ABSPATH' ) || exit;

/**
 * Auction Admin Live extends the custom AuctionWatchersLive class
 *
 * @since 1.0.0
 * @extends AuctionWatchersLive
 */
class AuctionAdminLive extends AuctionWatchersLive {

	/**
	 * Set email defaults
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();

		$this->id             = 'goodbids_auction_admin_live';
		$this->title          = __( 'Auction Admin Live', 'goodbids' );
		$this->description    = __( 'This Email is sent to the site admin when an Auction goes live.', 'goodbids' );
		$this->template_html  = 'emails/auction-admin-live.php';
		$this->template_plain = 'emails/plain/auction-admin-live.php';
	}

	/**
	 * Get email heading.
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_default_heading(): string {
		return __( 'Let the GOODBIDs begin!', 'goodbids' );
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
}
