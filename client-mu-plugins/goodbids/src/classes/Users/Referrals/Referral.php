<?php
/**
 * Referral Instance
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Users\Referrals;

use GoodBids\Users\Referrals;

/**
 * Instance of Referral
 * @since 1.0.0
 */
class Referral {

	/**
	 * @since 1.0.0
	 * @var ?int
	 */
	private ?int $user_id = null;

	/**
	 * @since 1.0.0
	 * @var ?string
	 */
	private ?string $referral_code = null;

	/**
	 * @since 1.0.0
	 * @var ?int
	 */
	private ?int $referrer_id = null;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id
	 */
	public function __construct( int $user_id ) {
		if ( ! get_user_by( 'ID', $user_id ) ) {
			return false;
		}

		$this->user_id = $user_id;

		$this->get_code();
	}

	/**
	 * Get the referral code for the user.
	 *
	 * @since 1.0.0
	 *
	 * @return ?string
	 */
	public function get_code(): ?string {
		if ( $this->referral_code ) {
			return $this->referral_code;
		}

		$referral_code = get_user_meta( $this->user_id, Referrals::REFERRAL_CODE_META_KEY, true );

		if ( $referral_code ) {
			$this->referral_code = $referral_code;
			return $this->referral_code;
		}

		$referral_code = goodbids()->referrals->generator->create();
		update_user_meta( $this->user_id, Referrals::REFERRAL_CODE_META_KEY, $referral_code );

		$this->referral_code = $referral_code;

		return $this->referral_code;
	}

	/**
	 * Updates the user's referral code.
	 *
	 * @since 1.0.0
	 *
	 * @param string $code New custom refer code.
	 *
	 * @return bool|int
	 */
	public function update( string $code ): bool|int {
		if ( ! metadata_exists( 'user', $this->user_id, Referrals::REFERRAL_CODE_META_KEY ) ) {
			return false;
		}

		$this->referral_code = $code;

		return update_user_meta( $this->user_id, Referrals::REFERRAL_CODE_META_KEY, $code );
	}

	/**
	 * Return the id of user who referred this user, false if referred by no one
	 *
	 * @return ?int
	 */
	public function get_referrer_id(): ?int {
		if ( $this->referrer_id ) {
			return $this->referrer_id;
		}

		$referrer_id = get_user_meta( $this->user_id, Referrals::REFERRER_ID_META_KEY, true );

		if ( ! $referrer_id ) {
			return null;
		}

		$this->referrer_id = $referrer_id;

		return $this->referrer_id;
	}

	/**
	 * Returns user's specific referral link
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_link(): string {
		$code = $this->get_code();
		$base = wp_registration_url();

		return add_query_arg( [ Referrals::REFERRAL_CODE_QUERY_ARG => $code ], $base );
	}

	/**
	 * Returns array of user ids invited by this user
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_invitations(): array {
		return goodbids()->referrals->invitations->get( $this->user_id );
	}
}
