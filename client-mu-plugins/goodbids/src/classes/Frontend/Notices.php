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
	 * @var array
	 */
	private array $notices = [];

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
	 * @since 1.0.0
	 * @var string
	 */
	const EARNED_FREE_BID = 'earned-free-bid';

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->get_notice_id();
		$this->collect_notices();
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
	 * Gather all available notices
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function collect_notices(): void {
		$this->notices = apply_filters(
			'goodbids_notices',
			[
				self::NOT_AUTHENTICATED_REWARD  => [
					'message' => __( 'You must be logged in to checkout with Reward products.', 'goodbids' ),
					'type'    => 'error',
				],

				self::AUCTION_NOT_ENDED         => [
					'message' => __( 'This auction has not ended.', 'goodbids' ),
					'type'    => 'error',
				],

				self::NOT_AUCTION_WINNER        => [
					'message' => __( 'You are not the winner of this auction.', 'goodbids' ),
					'type'    => 'error',
				],

				self::AUCTION_NOT_FOUND         => [
					'message' => __( 'This reward is not associated with an auction.', 'goodbids' ),
					'type'    => 'error',
				],

				self::GET_REWARD_COUPON_ERROR   => [
					'message' => __( 'There was a problem generating the Reward Coupon Code. Please contact support for further assistance.', 'goodbids' ),
					'type'    => 'error',
				],

				self::APPLY_REWARD_COUPON_ERROR => [
					'message' => __( 'There was a problem applying the Reward Coupon Code. Please contact support for further assistance.', 'goodbids' ),
					'type'    => 'error',
				],

				self::REWARD_ALREADY_REDEEMED   => [
					'message' => __( 'This Reward has already been redeemed. Please contact support if you feel this is an error.', 'goodbids' ),
					'type'    => 'error',
				],

				self::EARNED_FREE_BID           => [
					'message' => __( 'Congratulations! You have earned a Free Bid!', 'goodbids' ),
					'type'    => 'success',
				],
			]
		);
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

				if ( empty( $this->notices[ $this->notice_id ] ) ) {
					// TODO: Log error.
					return;
				}

				$notice = $this->notices[ $this->notice_id ];
				wc_add_notice( $notice['message'], $notice['type'] );
			}
		);
	}
}
