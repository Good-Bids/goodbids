<?php
/**
 * Auction Winner Confirmation: Email the user that won an Auction.
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
class AuctionWinnerConfirmation extends Email {

	/**
	 * This email is sent to Customers.
	 *
	 * @var bool
	 */
	protected $customer_email = true;

	/**
	 * Set email defaults
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();

		$this->id             = 'goodbids_auction_winner_confirmation';
		$this->title          = __( 'Auction Winner Confirmation', 'goodbids' );
		$this->description    = __( 'Notification email is sent when a user has won an auction.', 'goodbids' );
		$this->template_html  = 'emails/auction-winner-confirmation.php';
		$this->template_plain = 'emails/plain/auction-winner-confirmation.php';
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
			__( '[%s] Congratulations, you won!', 'goodbids' ),
			'{site_title}',
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
			/* translators: %s: reward product title */
			__( 'You generosity earned a %s!', 'goodbids' ),
			'{auction.reward_title}'
		);
	}

	/**
	 * Get button text
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_default_button_text(): string {
		return __( 'Claim Your Reward', 'goodbids' );
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
