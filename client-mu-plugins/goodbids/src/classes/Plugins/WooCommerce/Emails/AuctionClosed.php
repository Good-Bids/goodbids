<?php
/**
 * Auction Closed: Email the users when an auction closes.
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce\Emails;

defined( 'ABSPATH' ) || exit;

/**
 * Auction Watchers Live extend the custom BaseEmail class
 *
 * @since 1.0.0
 * @extends Email
 */
class AuctionClosed extends Email {

	/**
	 * Set email defaults
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();

		$this->id             = 'goodbids_auction_closed';
		$this->title          = __( 'Auction Closed', 'goodbids' );
		$this->description    = __( 'Notification email is sent when an auction goes closes.', 'goodbids' );
		$this->template_html  = 'emails/auction-closed.php';
		$this->template_plain = 'emails/plain/auction-closed.php';
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
			__( '[%1$s] %2$s has ended', 'goodbids' ),
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
			/* translators: %1$s: site title, %2$s: auction total raised */
			__( 'You helped %1$s raise %2$s!', 'goodbids' ),
			'{site_title}',
			'{auction.total_raised}'
		);
	}

	/**
	 * Get button text
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_default_button_text(): string {
		return __( 'See Auction Results', 'goodbids' );
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
}
