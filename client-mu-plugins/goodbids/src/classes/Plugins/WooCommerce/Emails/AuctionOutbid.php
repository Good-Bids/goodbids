<?php
/**
 * Auction Outbid: Send an email to the user that was out bid on an auction.
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce\Emails;

defined( 'ABSPATH' ) || exit;
/**
 * Auction Outbid extend the custom BaseEmail class
 *
 * @since 1.0.0
 * @extends Email
 */
class AuctionOutbid extends Email {

	/**
	 * This email is sent to the Bidder.
	 *
	 * @var bool
	 */
	protected bool $bidder_email = true;

	/**
	 * Set email defaults
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();

		$this->id             = 'goodbids_auction_outbid';
		$this->title          = __( 'Auction Outbid', 'goodbids' );
		$this->description    = __( 'Notification email is sent when a user is out bid on an auction.', 'goodbids' );
		$this->template_html  = 'emails/auction-outbid.php';
		$this->template_plain = 'emails/plain/auction-outbid.php';
	}

	/**
	 * Get email heading.
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_default_heading(): string {
		return __( 'It’s not too late!', 'goodbids' );
	}

	/**
	 * Get button text
	 *
	 * @since   1.0.0
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
	 * Get email subject.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_default_subject(): string {
		return sprintf(
			/* translators: %s: site title */
			__( '[%s] You’ve been outbid', 'goodbids' ),
			'{site_title}',
		);
	}
}
