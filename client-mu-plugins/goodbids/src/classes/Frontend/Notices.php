<?php
/**
 * Front-end Notices
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Frontend;

use GoodBids\Utilities\Log;

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
	const NOT_AUTHENTICATED = 'not-authenticated';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const AUCTION_NOT_ENDED = 'auction-not-ended';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const AUCTION_HAS_ENDED = 'auction-has-ended';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const AUCTION_NOT_STARTED = 'auction-not-started';

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
	const GET_FREE_BID_COUPON_ERROR = 'get-free-bid-coupon-error';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const APPLY_COUPON_ERROR = 'apply-coupon-error';

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
	 * @since 1.0.0
	 * @var string
	 */
	const FREE_BIDS_NOT_ELIGIBLE = 'free-bids-not-eligible';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const NO_AVAILABLE_FREE_BIDS = 'no-available-free-bids';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const FREE_BID_REDEEMED = 'free-bid-redeemed';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const BID_ALREADY_PLACED = 'bid-already-placed';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const BID_ALREADY_PLACED_CART = 'bid-already-placed-cart';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const ALREADY_HIGH_BIDDER = 'already-high-bidder';

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
	 * Grab the notice id from the query arg and return it.
	 *
	 * @since 1.0.0
	 *
	 * @return ?string
	 */
	private function get_notice_id(): ?string {
		if ( empty( $_REQUEST['gb-notice'] ) ) { // phpcs:ignore
			return null;
		}

		$this->notice_id = trim( sanitize_text_field( wp_unslash( $_REQUEST['gb-notice'] ) ) ); // phpcs:ignore

		return $this->notice_id;
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
				self::NOT_AUTHENTICATED  => [
					'message' => __( 'You must be logged in before checking out.', 'goodbids' ),
					'type'    => 'error',
				],

				self::AUCTION_NOT_STARTED       => [
					'message' => __( 'This Auction has not started yet.', 'goodbids' ),
					'type'    => 'error',
				],

				self::AUCTION_NOT_ENDED         => [
					'message' => __( 'This Auction has not ended.', 'goodbids' ),
					'type'    => 'error',
				],

				self::AUCTION_HAS_ENDED         => [
					'message' => __( 'This Auction has already ended.', 'goodbids' ),
					'type'    => 'error',
				],

				self::NOT_AUCTION_WINNER        => [
					'message' => __( 'You are not the winner of this auction.', 'goodbids' ),
					'type'    => 'error',
				],

				self::AUCTION_NOT_FOUND         => [
					'message' => __( 'There was a problem locating the associated auction.', 'goodbids' ),
					'type'    => 'error',
				],

				self::GET_REWARD_COUPON_ERROR   => [
					'message' => __( 'There was a problem generating the Reward Coupon Code. Please contact support for further assistance.', 'goodbids' ),
					'type'    => 'error',
				],

				self::GET_FREE_BID_COUPON_ERROR => [
					'message' => __( 'There was a problem generating the Free Bid Coupon Code. Please contact support for further assistance.', 'goodbids' ),
					'type'    => 'error',
				],

				self::APPLY_COUPON_ERROR => [
					'message' => __( 'There was a problem applying your Coupon Code. Please contact support for further assistance.', 'goodbids' ),
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

				self::FREE_BIDS_NOT_ELIGIBLE    => [
					'message' => __( 'Sorry, this Auction is currently not eligible to use Free Bids. Please try again later.', 'goodbids' ),
					'type'    => 'error',
				],

				self::NO_AVAILABLE_FREE_BIDS    => [
					'message' => __( 'Sorry, you do not have any available free bids.', 'goodbids' ),
					'type'    => 'error',
				],

				self::FREE_BID_REDEEMED         => [
					'message' => __( 'You have successfully used a free bid!', 'goodbids' ),
					'type'    => 'success',
				],

				self::BID_ALREADY_PLACED        => [
					'message' => __( 'Uh-oh! Someone else already placed this bid. We\'ve removed the GoodBid from your cart. Please return to the Auction to place a new GoodBid.', 'goodbids' ),
					'type'    => 'error',
				],

				self::BID_ALREADY_PLACED_CART   => [
					'message' => __( 'Uh-oh, someone else has already placed a bid for this amount.', 'goodbids' ),
					'type'    => 'error',
				],

				self::ALREADY_HIGH_BIDDER       => [
					'message' => __( 'It looks like you are already the current high bidder for this Auction.', 'goodbids' ),
					'type'    => 'error',
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

				$this->add_notice( $this->notice_id );
			}
		);
	}

	/**
	 * Adds a pre-defined WC Notice
	 *
	 * @since 1.0.0
	 *
	 * @param string $notice_id
	 *
	 * @return void
	 */
	public function add_notice( string $notice_id ) : void {
		$notice = $this->get_notice( $notice_id );

		if ( ! $notice ) {
			return;
		}

		wc_add_notice( $notice['message'], $notice['type'], [ '_notice_id' => $notice_id ] );
	}

	/**
	 * Get the notice by Notice ID.
	 *
	 * @since 1.0.0
	 *
	 * @param ?string $notice_id
	 *
	 * @return ?array
	 */
	public function get_notice( string $notice_id = null ): ?array {
		if ( ! $notice_id ) {
			$notice_id = $this->get_notice_id();
		}

		if ( empty( $this->notices[ $notice_id ] ) ) {
			Log::warning( 'Notice not found.', compact( 'notice_id' ) );
			return null;
		}

		return $this->notices[ $notice_id ];
	}

	/**
	 * Check if a notice exists.
	 *
	 * @since 1.0.0
	 *
	 * @param string $notice_id
	 *
	 * @return bool
	 */
	public function notice_exists( string $notice_id ): bool {
		if ( ! did_action( 'woocommerce_init' ) ) {
			return false;
		}

		$notices = wc_get_notices();

		if ( ! $notices ) {
			return false;
		}

		foreach ( $notices as $type_notices ) {
			foreach ( $type_notices as $notice ) {
				if ( empty( $notice['data'] ) || empty( $notice['data']['_notice_id'] ) ) {
					continue;
				}

				if ( $notice['data']['_notice_id'] === $notice_id ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Remove a notice by ID
	 *
	 * @since 1.0.0
	 *
	 * @param string $notice_id
	 *
	 * @return void
	 */
	public function remove_notice( string $notice_id ): void {
		if ( ! did_action( 'woocommerce_init' ) ) {
			return;
		}

		$notices = wc_get_notices();

		if ( ! $notices ) {
			return;
		}

		foreach ( $notices as $type => $type_notices ) {
			foreach ( $type_notices as $index => $notice ) {
				if ( empty( $notice['data'] ) || empty( $notice['data']['_notice_id'] ) ) {
					continue;
				}

				if ( $notice['data']['_notice_id'] === $notice_id ) {
					unset( $notices[ $type ][ $index ] );
					break;
				}
			}
		}
	}
}
