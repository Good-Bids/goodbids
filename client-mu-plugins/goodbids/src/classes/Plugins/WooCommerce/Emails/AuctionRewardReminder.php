<?php
/**
 * Auction Reward Reminder: Email the user that still needs to claim their reward.
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce\Emails;

defined( 'ABSPATH' ) || exit;

/**
 * Auction Reward Reminder extend the custom BaseEmail class
 *
 * @since 1.0.0
 * @extends Email
 */
class AuctionRewardReminder extends Email {

	/**
	 * Set email defaults
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();

		$this->id             = 'goodbids_auction_reward_reminder';
		$this->title          = __( 'Auction Reward Reminder', 'goodbids' );
		$this->description    = __( 'Notification email to remind a user they still need to claim their reward', 'goodbids' );
		$this->template_html  = 'emails/auction-reward-reminder.php';
		$this->template_plain = 'emails/plain/auction-reward-reminder.php';
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
			__( '[%s] Remember to claim your reward', 'goodbids' ),
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
			__( 'Your %s is still waiting', 'goodbids' ),
			'{auction.reward_title}',
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
