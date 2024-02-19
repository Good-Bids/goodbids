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
use WP_User;

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
	 * The User Object (or false if user does not exist)
	 *
	 * @since 1.0.0
	 * @var WP_User|false
	 */
	public WP_User|false $user = false;

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
		$this->user    = get_user_by( 'ID', $this->user_id );
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

		if ( ! $this->user ) {
			return null;
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
		if ( ! $this->user ) {
			return false;
		}

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
		if ( ! $this->user ) {
			return '';
		}

		$code = strtoupper( $this->get_code() );
		$base = wp_registration_url();

		return add_query_arg( [ Track::REFERRAL_CODE_QUERY_ARG => $code ], $base );
	}

	/**
	 * Get Users Referrals
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_referrals(): array {
		if ( ! $this->user ) {
			return [];
		}

		return goodbids()->sites->loop(
			function ( $site_id ) {
				$referrals = [];
				$query     = new WP_Query(
					[
						'post_type'      => goodbids()->referrals->get_post_type(),
						'posts_per_page' => -1,
						'fields'         => 'ids',
						'meta_query'     => [
							[
								'key'   => Referrals::REFERRER_ID_META_KEY,
								'value' => $this->get_id(),
							],
						],
					]
				);

				while ( $query->have_posts() ) {
					$query->the_post();
					$referrals[] = [
						'referral_id' => get_the_ID(),
						'site_id'     => $site_id,
					];
				}
				wp_reset_postdata();

				return $referrals;
			}
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
		if ( ! $this->user ) {
			return 0;
		}

		return( count( $this->get_referrals() ) );
	}

	/**
	 * Get user IDs referred by the referrer
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_referred_user_ids(): array {
		$users = [];

		if ( ! $this->user ) {
			return $users;
		}

		$referrals = $this->get_referrals();

		if ( ! $referrals ) {
			return $users;
		}

		foreach ( $referrals as $data ) {
			$user_id = goodbids()->sites->swap(
				fn() => get_post_meta( $data['referral_id'], Referrals::USER_ID_META_KEY, true ),
				$data['site_id']
			);

			if ( $user_id ) {
				$users[] = $user_id;
			}
		}

		return $users;
	}
}
