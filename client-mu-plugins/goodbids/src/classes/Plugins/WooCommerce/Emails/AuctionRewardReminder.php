<?php
/**
 * Auction Reward Reminder: Email the user that still needs to claim their reward.
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce\Emails;

use GoodBids\Auctions\Rewards;
use GoodBids\Utilities\Log;

defined( 'ABSPATH' ) || exit;

/**
 * Auction Reward Reminder email
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
		$this->description    = __( 'Notification email sent to winner when reward is unclaimed.', 'goodbids' );
		$this->template_html  = 'emails/auction-reward-reminder.php';
		$this->template_plain = 'emails/plain/auction-reward-reminder.php';
		$this->customer_email = true;

		$this->cron_check_for_unclaimed_rewards();
	}

	/**
	 * Trigger this email when an Auction has an unclaimed reward.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function cron_check_for_unclaimed_rewards(): void {
		add_action(
			Rewards::CRON_UNCLAIMED_REMINDER_HOOK,
			function (): void {
				$auctions = goodbids()->auctions->get_unclaimed_reward_auction_emails();

				if ( ! $auctions ) {
					return;
				}

				foreach ( $auctions as $auction_id ) {
					$auction = goodbids()->auctions->get( $auction_id );
					$winner  = $auction->get_winning_bidder();
					$this->trigger( $auction, $winner->ID );
				}
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
			'{reward.title}',
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
		return '{reward.claim_url}';
	}
}
