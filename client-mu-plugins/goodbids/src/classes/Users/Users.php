<?php
/**
 * User-specific Methods
 *
 * @package GoodBids
 * @since 1.0.0
 */

namespace GoodBids\Users;

use GoodBids\Auctions\FreeBid;

/**
 * User Class
 *
 * @since 1.0.0
 */
class Users {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const FREE_BIDS_META_KEY = '_goodbids_free_bids';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const FREE_BID_STATUS_ALL = 'all';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const FREE_BID_STATUS_UNUSED = 'unused';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const FREE_BID_STATUS_USED = 'used';

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {}

	/**
	 * Get an array of all free bids for a User, filterable by status.
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $user_id
	 * @param string $status
	 *
	 * @return FreeBid[]
	 */
	public function get_free_bids( ?int $user_id = null, string $status = self::FREE_BID_STATUS_ALL ): array {
		if ( null === $user_id ) {
			$user_id = get_current_user_id();
		}

		/** @var FreeBid[] $free_bids */
		$free_bids = get_user_meta( $user_id, self::FREE_BIDS_META_KEY, true );

		if ( ! $free_bids || ! is_array( $free_bids ) ) {
			return [];
		}

		return collect( $free_bids )
			->filter(
				fn ( $free_bid ) => (
					// When status is self::FREE_BID_STATUS_ALL, always returns true
					self::FREE_BID_STATUS_ALL === $status
					// Otherwise bid must match status.
					|| $status === $free_bid->get_status()
				)
			)
			->values()
			->all();
	}

	/**
	 * Get total available free bids for a user.
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $user_id
	 *
	 * @return int
	 */
	public function get_available_free_bid_count( ?int $user_id = null ): int {
		return count( $this->get_free_bids( $user_id, self::FREE_BID_STATUS_UNUSED ) );
	}

	/**
	 * Award a Free Bid to a User
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id
	 * @param int $auction_id
	 * @param string $description
	 *
	 * @return bool
	 */
	public function award_free_bid( int $user_id, int $auction_id, string $description = '' ): bool {
		$free_bid = new FreeBid( $auction_id );
		$free_bid->set_description( $description );

		$free_bids   = $this->get_free_bids( $user_id );
		$free_bids[] = $free_bid;
		return $this->save_free_bids( $user_id, $free_bids );
	}

	/**
	 * Save Free Bids array to User Meta
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id
	 * @param FreeBid[] $free_bids
	 *
	 * @return bool
	 */
	private function save_free_bids( int $user_id, array $free_bids ): bool {
		$original = get_user_meta( $user_id, self::FREE_BIDS_META_KEY, true );

		if ( $original === $free_bids ) {
			// Data is unchanged.
			return false;
		}

		return boolval( update_user_meta( $user_id, self::FREE_BIDS_META_KEY, $free_bids ) );
	}

	/**
	 * Redeem a Free Bid
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 * @param int $order_id
	 * @param ?int $user_id
	 *
	 * @return bool
	 */
	public function redeem_free_bid( int $auction_id, int $order_id, ?int $user_id = null ): bool {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}

		$all_free_bids    = $this->get_free_bids( $user_id );
		$unused_free_bids = $this->get_free_bids( $user_id, self::FREE_BID_STATUS_UNUSED );

		if ( ! count( $unused_free_bids ) ) {
			// TODO: Log error
			return false;
		}

		$redeemed = false;

		// Use the first available free bid.
		foreach ( $all_free_bids as $free_bid ) {
			if ( $free_bid->redeem( $auction_id, $order_id ) ) {
				$redeemed = true;
			}
			break;
		}

		if ( ! $redeemed ) {
			// TODO: Log error.
			return false;
		}

		return $this->save_free_bids( $user_id, $all_free_bids );
	}

	/**
	 * Get User Email Addresses. If no user_id is provided, the current user is used.
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $user_id
	 *
	 * @return array
	 */
	public function get_emails( int $user_id = null ): array {
		$emails = [];

		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}

		$user = get_user_by( 'id', $user_id );

		if ( ! $user ) {
			return $emails;
		}

		$emails[] = $user->user_email;

		$billing_email = get_user_meta( $user_id, 'billing_email', true );
		if ( $billing_email ) {
			$emails[] = $billing_email;
		}

		return $emails;
	}
}
