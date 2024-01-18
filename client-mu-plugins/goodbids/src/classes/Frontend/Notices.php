<?php
/**
 * Front-end Notices
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Frontend;

/**
 * Display Front-end Notices
 *
 * @since 1.0.0
 */
class Notices {

	/**
	 * The notice ID from gb-notice query arg.
	 *
	 * @since 1.0.0
	 * @var ?string
	 */
	private ?string $notice_id = null;

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const NOT_AUTHENTICATED_REWARD = 'not-authenticated-reward';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const AUCTION_NOT_ENDED = 'auction-not-ended';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const NOT_AUCTION_WINNER = 'not-auction-winner';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const AUCTION_NOT_FOUND = 'auction-not-found';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const GET_REWARD_COUPON_ERROR = 'get-reward-coupon-error';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const APPLY_REWARD_COUPON_ERROR = 'apply-reward-coupon-error';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const REWARD_ALREADY_REDEEMED = 'reward-already-redeemed';

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->get_notice_id();

		$this->set_notice();
	}

	/**
	 * Grab the notice id from the query arg.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function get_notice_id(): void {
		if ( empty( $_REQUEST['gb-notice'] ) ) { // phpcs:ignore
			return;
		}

		$this->notice_id = trim( sanitize_text_field( wp_unslash( $_REQUEST['gb-notice'] ) ) ); // phpcs:ignore
	}

	/**
	 * Set the message based on the notice id.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function set_notice(): void {
		add_action(
			'init',
			function() {
				if ( ! $this->notice_id ) {
					return;
				}

				if ( self::NOT_AUTHENTICATED_REWARD === $this->notice_id ) {
					wc_add_notice( __( 'You must be logged in to checkout with Reward products.', 'goodbids' ), 'error' );
				} elseif ( self::AUCTION_NOT_ENDED === $this->notice_id ) {
					wc_add_notice( __( 'This auction has not ended.', 'goodbids' ), 'error' );
				} elseif ( self::NOT_AUCTION_WINNER === $this->notice_id ) {
					wc_add_notice( __( 'You are not the winner of this auction.', 'goodbids' ), 'error' );
				} elseif ( self::AUCTION_NOT_FOUND === $this->notice_id ) {
					wc_add_notice( __( 'This reward is not associated with an auction.', 'goodbids' ), 'error' );
				} elseif ( self::GET_REWARD_COUPON_ERROR === $this->notice_id ) {
					wc_add_notice( __( 'There was a problem generating the Reward Coupon Code. Please contact support for further assistance.', 'goodbids' ), 'error' );
				} elseif ( self::APPLY_REWARD_COUPON_ERROR === $this->notice_id ) {
					wc_add_notice( __( 'There was a problem applying the Reward Coupon Code. Please contact support for further assistance.', 'goodbids' ), 'error' );
				} elseif ( self::REWARD_ALREADY_REDEEMED === $this->notice_id ) {
					wc_add_notice( __( 'This Reward has already been redeemed. Please contact support if you feel this is an error.', 'goodbids' ), 'error' );
				}
			}
		);
	}
}
