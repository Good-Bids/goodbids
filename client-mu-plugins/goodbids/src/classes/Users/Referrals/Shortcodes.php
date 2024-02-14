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
						'show' => 'code',
					],
					$attrs,
					$shortcode
				);

				// Bail early if not logged in.
				if ( ! is_user_logged_in() ) {
					return '';
				}

				// analyze shortcode parameters.
				$show     = $attrs['var'];
				$referral = new Referral( get_current_user_id() );

				if ( 'code' === $show ) {
					return $referral->get_code();
				}

				if ( 'link' === $show ) {
					return $referral->get_link();
				}

				if ( 'invitation_count' === $show ) {
					return count( $referral->get_invitations() );
				}

				if ( 'top_referrers' === $show ) {
					global $wpdb;
					$results = $wpdb->get_results(
						$wpdb->prepare(
							"SELECT COUNT(meta_value) as counted, meta_value as id FROM {$wpdb->usermeta} WHERE meta_key = %s AND meta_value IS NOT NULL GROUP BY meta_value ORDER BY counted DESC LIMIT %d",
							Referrals::REFERRER_ID_META_KEY,
							10
						),
						ARRAY_A
					);

					ob_start();
					goodbids()->load_view( 'admin/referrals/top-referrers.php', compact( 'results' ) );
					return ob_get_clean();
				}

				if ( 'invitations' === $show ) {
					$invitations = $referral->get_invitations();
					ob_start();
					goodbids()->load_view( 'admin/referrals/user-invitations.php', compact( 'invitations' ) );
					return ob_get_clean();
				}

				if ( 'copy_link' === $show ) {
					if ( ! wp_script_is( 'jquery', 'enqueued' ) ) {
						// Enqueue jquery only if not enqueued before.
						wp_enqueue_script( 'jquery' );
					}
					if ( ! wp_script_is( 'clipboard', 'enqueued' ) ) {
						// Enqueue clipboard only if not enqueued before.
						wp_enqueue_script( 'clipboard' );
					}

					wp_enqueue_script( 'goodbids-referrals-copy-link', GOODBIDS_PLUGIN_URL . 'js/wp-referral-code-public.js', array(), goodbids()->get_version(), true );
					wp_enqueue_style( 'goodbids-referrals-link-styles', GOODBIDS_PLUGIN_URL . 'css/wp-referral-code-copy-link.css', array(), goodbids()->get_version() );
					ob_start();

					goodbids()->load_view( 'admin/referrals/copy-link-box.php', compact( 'referral' ) );

					return ob_get_clean();
				}

				return '';
			}
		);
	}

}
