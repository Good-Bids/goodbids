<?php
/**
 * Auction Reward Claimed: Email the users when an auction reward is claimed.
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce\Emails;

defined( 'ABSPATH' ) || exit;

/**
 * Auction Reward Claimed Email
 *
 * @since 1.0.0
 * @extends Email
 */
class AuctionRewardClaimed extends Email {

	/**
	 * Set email defaults
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();

		$this->id             = 'goodbids_auction_reward_claimed';
		$this->title          = __( 'Auction Reward Claimed', 'goodbids' );
		$this->description    = __( 'Notification email sent to participant when a reward is claimed', 'goodbids' );
		$this->template_html  = 'emails/auction-reward-claimed.php';
		$this->template_plain = 'emails/plain/auction-reward-claimed.php';
		$this->bidder_email   = true;

		$this->trigger_on_reward_claimed();
	}

	/**
	 * Trigger this email when an auction reward is claimed.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	private function trigger_on_reward_claimed(): void {
		add_action(
			'goodbids_reward_redeemed',
			function ( int $auction_id, int $order_id ) {
				$order = wc_get_order( $order_id );
				$this->trigger( $order, get_current_user_id() );
			},
			10,
			2
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
			/* translators: %1$s: site title, %2$s: auction title */
			__( '[%1$s] %2$s Claim Confirmation', 'goodbids' ),
			'{site_title}',
			'{auction.title}'
		);
	}

	/**
	 * Get email heading.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_default_heading(): string {
		return __( 'Thanks for claiming your reward!', 'goodbids' );
	}

	/**
	 * Get button text
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_default_button_text(): string {
		return __( 'See Auction Results', 'goodbids' );
	}

	/**
	 * Set Button URL
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_button_url(): string {
		return '{auction.url}';
	}
}
