<?php
/**
 * Referral Registration Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Users\Referrals;

use GoodBids\Users\Referrals;

/**
 * Class for Referral Registration
 *
 * @since 1.0.0
 */
class Registration {

	/**
	 * Initialize Referral Registration
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->handle_registration();
	}

	private function handle_registration(): void {
		add_action(
			'user_register',
			function ( int $user_id ) {
				$code = '';

				if ( isset( $_COOKIE[ Referrals::REFERRER_COOKIE ] ) ) {
					$code = sanitize_text_field( wp_unslash( $_COOKIE[ Referrals::REFERRER_COOKIE ] ) );
				}

				$code = apply_filters( 'goodbids_referrals_new_user_referral_code', $code, $user_id );

				$referrer_id = goodbids()->referrals->get_user_id_by_code( $code );

				if ( false === $referrer_id ) {
					goodbids()->referrals->clear_cookie();
					return;
				}

				// set referrer as inviter of new user.
				goodbids()->referrals->invitations->set_referrer( $user_id, $referrer_id );

				// adding new user to referrer invited list.
				goodbids()->referrals->invitations->add( $user_id, $referrer_id );

				goodbids()->referrals->clear_cookie();
			},
			20
		);
	}

}
