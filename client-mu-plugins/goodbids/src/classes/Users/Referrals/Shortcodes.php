<?php
/**
 * Referral Shortcodes
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Users\Referrals;

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
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function register_shortcodes(): void {
		add_shortcode(
			'goodbids-referral',
			function ( array $attrs = [], string $content = '', string $shortcode = 'goodbids-referral' ): string {
				$attrs = shortcode_atts(
					[
						'return'  => 'code',
						'user_id' => get_current_user_id(),
					],
					$attrs,
					$shortcode
				);

				$return  = $attrs['return'] ?? false;
				$user_id = $attrs['user_id'] ?? false;

				// User not required for Top Referrers action.
				if ( 'top-referrers' === $return && current_user_can( 'manage_options' ) ) {
					$results = goodbids()->referrals->get_top_referrers();
					return $this->get_view( 'top-referrers', compact( 'results' ) );
				}

				// Bail early if no user ID.
				if ( ! $user_id ) {
					return '';
				}

				// Bail early if user is not allowed to view other user's referral information.
				if ( $user_id !== get_current_user_id() && ! current_user_can( 'edit_users' ) ) {
					return '';
				}

				// Bail early if user does not exist.
				if ( ! get_user_by( 'ID', $user_id ) ) {
					return '';
				}

				$referrer = new Referrer( $user_id );

				return $this->shortcode_action( $return, $referrer );
			}
		);
	}

	/**
	 * Handle Referral Shortcode Actions
	 *
	 * @since 1.0.0
	 *
	 * @param string   $return
	 * @param Referrer $referrer
	 *
	 * @return string
	 */
	private function shortcode_action( string $return, Referrer $referrer ): string {
		if ( 'code' === $return ) {
			return $referrer->get_code();
		}

		if ( 'link' === $return ) {
			return $referrer->get_link();
		}

		if ( 'referral-count' === $return ) {
			return count( $referrer->get_referral_count() );
		}

		if ( 'referrals' === $return ) {
			$referrals = $referrer->get_referrals();
			return $this->get_view( 'user-referrals', compact( 'referrals' ) );
		}

		if ( 'copy-link' === $return ) {
			return $this->get_view( 'copy-link-box', compact( 'referrer' ) );
		}

		if ( 'copy-link-btn' === $return ) {
			return $this->get_view( 'copy-link-btn', compact( 'referrer' ) );
		}

		return '';
	}

	/**
	 * Returns a view file.
	 *
	 * @since 1.0.0
	 *
	 * @param string $view
	 * @param array  $context
	 *
	 * @return string
	 */
	private function get_view( string $view, array $context = [] ): string {
		return goodbids()->get_view( 'admin/referrals/' . $view . '.php', $context );
	}
}
