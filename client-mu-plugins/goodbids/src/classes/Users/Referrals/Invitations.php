<?php
/**
 * Referral Invitations Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Users\Referrals;

use GoodBids\Users\Referrals;

/**
 * Class for Referral Invitations
 *
 * @since 1.0.0
 */
class Invitations {

	/**
	 * Initialize Referral Invitations
	 *
	 * @since 1.0.0
	 */
	public function __construct() {}

	/**
	 * Get Users Invitations
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id
	 *
	 * @return array
	 */
	public function get( int $user_id ): array {
		$invitations = get_user_meta( $user_id, Referrals::INVITATIONS_META_KEY, true );

		if ( empty( $invitations ) ) {
			$invitations = [];
		}

		return $invitations;
	}

	/**
	 * Track when a user has accepted the invitation
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id
	 * @param int $referrer_id
	 *
	 * @return bool|int
	 */
	public function add( int $user_id, int $referrer_id ): bool|int {
		$invitations   = $this->get( $referrer_id );
		$invitations[] = $user_id;
		return $this->update( $referrer_id, $invitations );
	}

	/**
	 * Delete a referral
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id
	 * @param int $referrer_id
	 *
	 * @return bool|int
	 */
	public function delete( int $user_id, int $referrer_id ): bool|int {
		$invitations = $this->get( $referrer_id );

		update_user_meta( $user_id, Referrals::REFERRER_ID_META_KEY, null );

		if ( empty( $invitations ) ) {
			return true;
		}

		$invitations = array_diff( $invitations, [ $user_id ] );

		return $this->update( $referrer_id, $invitations );
	}

	/**
	 * Update the user's invitations
	 *
	 * @since 1.0.0
	 *
	 * @param int   $user_id
	 * @param array $invitations
	 *
	 * @return bool|int
	 */
	private function update( int $user_id, array $invitations ): bool|int {
		$invitations = array_unique( $invitations );
		return update_user_meta( $user_id, Referrals::INVITATIONS_META_KEY, $invitations );
	}

	/**
	 * Set the referrer for a user
	 *
	 * @param int $user_id
	 * @param int $referrer_id
	 *
	 * @return bool|int
	 */
	public function set_referrer( int $user_id, int $referrer_id ): bool|int {
		return update_user_meta( $user_id, Referrals::REFERRER_ID_META_KEY, $referrer_id );
	}
}
