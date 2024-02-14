<?php
/**
 * Referrals Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Users;

use GoodBids\Users\Referrals\Admin;
use GoodBids\Users\Referrals\Generator;
use GoodBids\Users\Referrals\Invitations;
use GoodBids\Users\Referrals\Registration;
use GoodBids\Users\Referrals\Shortcodes;

/**
 * Class for User Referrals
 *
 * @since 1.0.0
 */
class Referrals {

	/**
	 * @since 1.0.0
	 */
	const REFERRAL_CODE_META_KEY = 'wrc_ref_code';

	/**
	 * @since 1.0.0
	 */
	const INVITATIONS_META_KEY = 'wrc_invited_users';

	/**
	 * @since 1.0.0
	 */
	const REFERRER_ID_META_KEY = 'wrc_referrer_id';

	/**
	 * @since 1.0.0
	 */
	const REFERRER_COOKIE = 'wrc_referrer_code';

	/**
	 * @since 1.0.0
	 */
	const REFERRAL_CODE_QUERY_ARG = 'ref';

	/**
	 * @since 1.0.0
	 * @var ?Generator
	 */
	public ?Generator $generator = null;

	/**
	 * @since 1.0.0
	 * @var ?Invitations
	 */
	public ?Invitations $invitations = null;

	/**
	 * Initialize User Referrals
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Initialize Submodules
		$this->generator   = new Generator();
		$this->invitations = new Invitations();

		// Initialize Admin
		new Admin();

		// Initialize Shortcodes
		new Shortcodes();

		// Initialize Registration
		new Registration();

		// Capture Referral Code.
		$this->track_referral_code();
	}

	/**
	 * Search users for a refer code.
	 *
	 * @param string $code Refer code.
	 *
	 * @return int|false
	 */
	public function get_user_id_by_code( string $code ): int|false {
		$user = get_users(
			array(
				'meta_key'     => Referrals::REFERRAL_CODE_META_KEY,
				'meta_value'   => $code,
				'meta_compare' => '=',
				'fields'       => [ 'ID' ],
				'number'       => 1,
				'count_total'  => false,
			)
		);

		return ! empty( $user ) ? $user[0]->ID : false;
	}

	/**
	 * Track referral code from query arg
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function track_referral_code(): void {
		add_action(
			'init',
			function () {
				if ( ! isset( $_GET[ self::REFERRAL_CODE_QUERY_ARG ] ) ) {
					return;
				}

				$code = trim( sanitize_text_field( wp_unslash( $_GET[ self::REFERRAL_CODE_QUERY_ARG ] ) ) );

				if ( ! $code ) {
					return;
				}

				$this->set_cookie( $code );
			}
		);
	}

	/**
	 * Get time until cookie should expire.
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	private function get_expire_time(): int {
		$days = goodbids()->get_config( 'referrals.cookie-expire-days' );
		return $days ? time() + ( $days * DAY_IN_SECONDS ) : time() + ( 30 * DAY_IN_SECONDS );
	}

	/**
	 * Set the referral code cookie for a potential user
	 *
	 * @param string $referrer_code
	 * @param ?int   $expire
	 *
	 * @return bool
	 */
	public function set_cookie( string $referrer_code, ?int $expire = null ): bool {
		if ( headers_sent() ) {
			return false;
		}

		if ( null === $expire ) {
			$expire = $this->get_expire_time();
		}

		setcookie( self::REFERRER_COOKIE, $referrer_code, $expire, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true ); // phpcs:ignore

		if ( COOKIEPATH != SITECOOKIEPATH ) {
			setcookie( self::REFERRER_COOKIE, $referrer_code, $expire, SITECOOKIEPATH, COOKIE_DOMAIN, is_ssl(), true ); // phpcs:ignore
		}

		return true;
	}

	/**
	 * Clear referrer cookie
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function clear_cookie(): void {
		$this->set_cookie( '', time() - HOUR_IN_SECONDS );

		if ( isset( $_COOKIE[ Referrals::REFERRER_COOKIE ] ) ) {
			unset( $_COOKIE[ Referrals::REFERRER_COOKIE ] );
		}
	}
}
