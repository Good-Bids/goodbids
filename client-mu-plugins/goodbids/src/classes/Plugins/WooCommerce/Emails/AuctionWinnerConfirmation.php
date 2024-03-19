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
 * Auction Winner Confirmation Email
 *
 * @since 1.0.0
 * @extends Email
 */
class AuctionWinnerConfirmation extends Email {

	/**
	 * Set the unique Email ID
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $id = 'goodbids_auction_winner_confirmation';

	/**
	 * Set email defaults
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();

		$this->title          = __( 'Auction Winner Confirmation', 'goodbids' );
		$this->description    = __( 'Notification email sent to high bidder when an Auction closes.', 'goodbids' );
		$this->template_html  = 'emails/auction-winner-confirmation.php';
		$this->template_plain = 'emails/plain/auction-winner-confirmation.php';
		$this->customer_email = true;

		$this->trigger_on_auction_end();
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
				$winner  = $auction->get_winning_bidder();
				$this->trigger( $auction, $winner->ID );
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
			'{reward.title}'
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
