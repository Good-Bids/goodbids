<?php
/**
 * GoodBids Bidder
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Users;

use GoodBids\Users\Referrals\Referrer;
use WP_User;

/**
 * Bidder Class
 *
 * @since 1.0.0
 */
class Bidder {

	/**
	 * @since 1.0.0
	 * @var int
	 */
	private int $user_id;

	/**
	 * @since 1.0.0
	 * @var ?WP_User
	 */
	private ?WP_User $user = null;

	/**
	 * @since 1.0.0
	 */
	public function __construct( ?int $user_id = null ) {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}

		$this->user_id = $user_id;

		$user = get_user_by( 'ID', $this->get_id() );

		if ( $user instanceof WP_User ) {
			$this->user = $user;
		}
	}

	/**
	 * Get the User ID
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_id(): int {
		return $this->user_id;
	}

	/**
	 * Get the Edit User URL
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_edit_url(): string {
		return get_edit_user_link( $this->get_id() );
	}

	/**
	 * Get User username
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_username(): string {
		return $this->user->user_login;
	}

	/**
	 * Get User email
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_email(): string {
		return $this->user->user_email;
	}

	/**
	 * Get the Date User Registered
	 *
	 * @since 1.0.0
	 *
	 * @param string $format
	 *
	 * @return string
	 */
	public function get_registered_date( string $format = 'n/j/Y' ): string {
		return mysql2date( $format, $this->user->user_registered );
	}

	/**
	 * Get the total bids placed by user.
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_total_bids(): int {
		return goodbids()->sites->get_user_total_bids( $this->get_id() );
	}

	/**
	 * Get the total donated by user.
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_total_donated(): int {
		return goodbids()->sites->get_user_total_donated( $this->get_id() );
	}

	/**
	 * Get user total free bids
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_total_free_bids(): int {
		return count( goodbids()->users->get_free_bids( $this->get_id() ) );
	}

	/**
	 * Get the total referrals by user.
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_total_referrals(): int {
		$referrer = new Referrer( $this->get_id() );
		return $referrer->get_referral_count();
	}
}
