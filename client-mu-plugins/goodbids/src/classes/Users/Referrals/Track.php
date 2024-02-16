<?php
/**
 * Referral Tracking
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Users\Referrals;

/**
 * Class for Tracking Referrals
 *
 * @since 1.0.0
 */
class Track {

	/**
	 * @since 1.0.0
	 */
	const REFERRAL_CODE_QUERY_ARG = 'gbr';

	/**
	 * @since 1.0.0
	 */
	const REFERRER_COOKIE = 'wrc_referrer_code';

	/**
	 * Initialize Referral Tracking
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Capture Referral Code.
		$this->track_referral_code();

		// Check for cookie on registration.
		$this->handle_registration();

		// Check for a Referral Code during Checkout.
		$this->convert_referrals();
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
			'template_redirect',
			function () {
				if ( ! isset( $_GET[ self::REFERRAL_CODE_QUERY_ARG ] ) ) { // phpcs:ignore
					return;
				}

				$code = trim( sanitize_text_field( wp_unslash( $_GET[ self::REFERRAL_CODE_QUERY_ARG ] ) ) ); // phpcs:ignore

				if ( ! $code ) {
					return;
				}

				$this->set_cookie(
					[
						'code'    => $code,
						'created' => current_time( 'mysql', true ),
					]
				);

				// Remove referral code from URL.
				wp_safe_redirect( remove_query_arg( self::REFERRAL_CODE_QUERY_ARG ) );
				exit;
			},
			2
		);
	}

	/**
	 * Get time until cookie should expire.
	 * Defaults to 30 days.
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
	 * @param array $data
	 * @param ?int  $expire
	 *
	 * @return void
	 */
	private function set_cookie( array $data, ?int $expire = null ): void {
		if ( headers_sent() ) {
			return;
		}

		if ( null === $expire ) {
			$expire = $this->get_expire_time();
		}

		$cookie = empty( $data ) ? '' : wp_json_encode( $data );

		setcookie( self::REFERRER_COOKIE, $cookie, $expire, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true ); // phpcs:ignore

		if ( COOKIEPATH != SITECOOKIEPATH ) {
			setcookie( self::REFERRER_COOKIE, $cookie, $expire, SITECOOKIEPATH, COOKIE_DOMAIN, is_ssl(), true ); // phpcs:ignore
		}
	}

	/**
	 * Get the Referral Code from the cookie, if it exists.
	 *
	 * @since 1.0.0
	 *
	 * @return ?string
	 */
	public function get_cookie(): ?string {
		if ( empty( $_COOKIE[ self::REFERRER_COOKIE ] ) ) {
			return null;
		}

		return sanitize_text_field( wp_unslash( $_COOKIE[ self::REFERRER_COOKIE ] ) ); // phpcs:ignore
	}

	/**
	 * Clear referrer cookie
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function clear_cookie(): void {
		$this->set_cookie( [], time() - YEAR_IN_SECONDS );

		if ( isset( $_COOKIE[ self::REFERRER_COOKIE ] ) ) {
			unset( $_COOKIE[ self::REFERRER_COOKIE ] ); // phpcs:ignore
		}
	}

	/**
	 * Check for referral cookie on registration
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function handle_registration(): void {
		add_action(
			'user_register',
			function ( int $user_id ) {
				$code = $this->get_cookie();

				if ( ! $code ) {
					return;
				}

				$referrer_id = goodbids()->referrals->get_user_id_by_referral_code( $code );

				// User no longer exists or referral code has changed.
				if ( false === $referrer_id ) {
					$this->clear_cookie();
					return;
				}

				// Create the new referral.
				goodbids()->referrals->add_referral( $user_id, $referrer_id , $code );

				$this->clear_cookie();
			},
			20
		);
	}

	/**
	 * Convert a Referral during Checkout
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function convert_referrals(): void {
		add_action(
			'goodbids_order_payment_complete',
			function ( int $order_id, int $auction_id ) {
				$referrer_id = goodbids()->referrals->get_referrer_id();

				if ( ! $referrer_id ) {
					return;
				}

				// No Free Bids for non-Paid-Bid Orders.
				if ( ! goodbids()->woocommerce->orders->is_bid_order( $order_id ) || goodbids()->woocommerce->orders->is_free_bid_order( $order_id ) ) {
					return;
				}

				// Convert the Referral
				goodbids()->referrals->convert( $referrer_id, get_current_user_id(), $auction_id, $order_id );
			},
			10,
			2
		);
	}
}
