<?php
/**
 * Referral Referrer
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Users\Referrals;

use GoodBids\Users\Referrals;
use WP_Query;

/**
 * Class for Referral Referrer
 *
 * @since 1.0.0
 */
class Referrer {

	/**
	 * The User ID
	 *
	 * @since 1.0.0
	 * @var int
	 */
	public int $user_id;

	/**
	 * The user's referral code
	 *
	 * @since 1.0.0
	 * @var ?string
	 */
	private ?string $referral_code = null;

	/**
	 * Initialize Referral Referrer
	 *
	 * @since 1.0.0
	 */
	public function __construct( ?int $user_id = null ) {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}

		$this->user_id = $user_id;
	}

	/**
	 * Returns the User ID
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_id(): int {
		return $this->user_id;
	}

	/**
	 * Get the referral code used for the referral.
	 *
	 * @since 1.0.0
	 *
	 * @return ?string
	 */
	public function get_code(): ?string {
		if ( $this->referral_code ) {
			return $this->referral_code;
		}

		$referral_code = get_user_meta( $this->get_id(), Referrals::REFERRAL_CODE_META_KEY, true );

		if ( $referral_code ) {
			$this->referral_code = $referral_code;
			return $this->referral_code;
		}

		$referral_code = goodbids()->referrals->generator->create();

		$this->set_code( $referral_code );

		return $this->referral_code;
	}

	/**
	 * Set the user's referral code.
	 *
	 * @since 1.0.0
	 *
	 * @param string $code
	 *
	 * @return bool|int
	 */
	public function set_code( string $code ) : bool|int {
		$this->referral_code = $code;

		return update_user_meta( $this->get_id(), Referrals::REFERRAL_CODE_META_KEY, $this->referral_code );
	}

	/**
	 * Returns user's referral link
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_link(): string {
		$code = strtoupper( $this->get_code() );
		$base = wp_registration_url();

		return add_query_arg( [ Track::REFERRAL_CODE_QUERY_ARG => $code ], $base );
	}

	/**
	 * Get Users Referrals
	 *
	 * @since 1.0.0
	 *
	 * @return WP_Query
	 */
	public function get_referrals(): WP_Query {
		return new WP_Query(
			[
				'post_type'      => goodbids()->referrals->get_post_type(),
				'posts_per_page' => -1,
				'meta_query'     => [
					[
						'key'   => Referrals::REFERRER_ID_META_KEY,
						'value' => $this->get_id(),
					],
				],
			]
		);
	}

	/**
	 * Get the count of referrals
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_referral_count(): int {
		$referrals = $this->get_referrals();
		return $referrals->found_posts;
	}

	/**
	 * Get user IDs referred by the referrer
	 *
	 * @since 1.0.0
	 *
	 * @return int[]
	 */
	public function get_referred_users(): array {
		$referrals = $this->get_referrals();
		$users     = [];

		while ( $referrals->have_posts() ) {
			$referrals->the_post();
			$users[] = get_post_meta( get_the_ID(), Referrals::USER_ID_META_KEY, true );
		}

		return $users;
	}
}
