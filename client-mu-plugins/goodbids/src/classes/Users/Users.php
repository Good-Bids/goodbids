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
	 * @return array
	 */
	public function get_free_bids( ?int $user_id = null, string $status = self::FREE_BID_STATUS_ALL ): array {
		if ( null === $user_id ) {
			$user_id = get_current_user_id();
		}

		/** @var FreeBid[] $free_bids */
		$free_bids = get_user_meta( $user_id, self::FREE_BIDS_META_KEY, true );

		if ( ! $free_bids || ! is_array( $free_bids ) || 0 >= count( $free_bids ) ) {
			return [];
		}

		if ( self::FREE_BID_STATUS_ALL === $status ) {
			return $free_bids;
		}

		$return = [];

		foreach ( $free_bids as $free_bid ) {
			if ( $status === $free_bid->get_status() ) {
				$return[] = $free_bid;
			}
		}

		return $return;
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
		return $this->save_free_bids( $free_bids, $user_id );
	}

	/**
	 * Save Free Bids array to User Meta
	 *
	 * @since 1.0.0
	 *
	 * @param array $free_bids
	 * @param int $user_id
	 *
	 * @return bool
	 */
	private function save_free_bids( array $free_bids, int $user_id ): bool {
		return boolval( update_user_meta( $user_id, self::FREE_BIDS_META_KEY, $free_bids ) );
	}
}
