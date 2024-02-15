<?php
/**
 * Referral Shortcodes
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Users\Referrals;

use GoodBids\Users\Referrals;

/**
 * Class for Referral Shortcodes
 *
 * @since 1.0.0
 */
class Shortcodes {

	/**
	 * Initialize Referral Shortcodes
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->register_shortcodes();
	}

	/**
	 * Register Referral Shortcodes
	 *
	 * @return void
	 */
	private function register_shortcodes(): void {
		add_shortcode(
			'goodbids-referral',
			function ( array $attrs = [], string $content = '', string $shortcode = 'goodbids-referral' ): string {
				$attrs = shortcode_atts(
					[
						'show'    => 'code',
						'user_id' => get_current_user_id(),
					],
					$attrs,
					$shortcode
				);

				// Bail early if not logged in.
				if ( ! $attrs['user_id'] ) {
					return '';
				}

				// Bail early if user is not allowed to view other user's referral information.
				if ( $attrs['user_id'] !== get_current_user_id() ) {
					if ( ! current_user_can( 'edit_users' ) ) {
						return '';
					}
				}

				// analyze shortcode parameters.
				$show     = $attrs['show'];
				$referrer = new Referrer( $attrs['user_id'] );

				if ( 'code' === $show ) {
					return $referrer->get_code();
				}

				if ( 'link' === $show ) {
					return $referrer->get_link();
				}

				if ( 'referral-count' === $show ) {
					return count( $referrer->get_referral_count() );
				}

				if ( 'top-referrers' === $show ) {
					$results = goodbids()->referrals->get_top_referrers();

					ob_start();
					goodbids()->load_view( 'admin/referrals/top-referrers.php', compact( 'results' ) );
					return ob_get_clean();
				}

				if ( 'referrals' === $show ) {
					$referrals = $referrer->get_referrals();
					ob_start();
					goodbids()->load_view( 'admin/referrals/user-referrals.php', compact( 'referrals' ) );
					return ob_get_clean();
				}

				if ( 'copy-link' === $show ) {
					ob_start();

					goodbids()->load_view( 'admin/referrals/copy-link-box.php', compact( 'referrer' ) );

					return ob_get_clean();
				}

				return '';
			}
		);
	}

}
