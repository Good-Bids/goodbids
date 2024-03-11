<?php
/**
 * Auction Reward Claimed: Email the users when an auction reward is claimed.
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce\Emails;

use GoodBids\Utilities\Log;

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
		$this->watcher_email  = true;
		$this->bidder_email   = true;

		$this->trigger_on_auction_end();
	}

	/**
	 * Add a custom footer.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function init_customizations(): void {
		add_action( 'woocommerce_email_footer', [ $this, 'all_auctions_html' ], 7, 2 );
	}

	/**
	 * Remove custom footer.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function remove_customizations(): void {
		remove_action( 'woocommerce_email_footer', [ $this, 'all_auctions_html' ], 7 );
	}

	/**
	 * Trigger this email on Auction End.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function trigger_on_auction_end(): void {
		add_action(
			'goodbids_auction_end',
			function ( int $auction_id ) {
				$auction = goodbids()->auctions->get( $auction_id );
				$this->send_to_watchers( $auction );
				$this->send_to_bidders( $auction );
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
			__( '[%1$s] %2$s auction has ended', 'goodbids' ),
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
			/* translators: %1$s: Site Title, %2$s: Total Raised by Auction */
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
		return '{auction.url}';
	}

	/**
	 * Display Link to All Auctions
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function all_auctions_html(): void {
		printf(
			'<p style="text-align:center;">%s <a href="%s">%s</a>',
			esc_html__( 'Want to support another great GOODBIDS cause?', 'goodbids' ),
			'{auctions_url}',
			esc_html__( 'View All Auctions', 'goodbids' )
		);
	}
}
