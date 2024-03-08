<?php
/**
 * Early Hooks before Plugins are loaded.
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins;

/**
 * This class handles setting accessibility settings.
 *
 * @since 1.0.0
 */
class EarlyHooks {

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Disable until onboarding is complete.
		$this->disable_query_monitor();

		// Prevent SVG Support Errors
		$this->prevent_svg_support_errors();
	}

	/**
	 * Prevent SVG Support from producing an error on init.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function prevent_svg_support_errors(): void {
		$return_array = function( mixed $value ): array {
			if ( ! is_array( $value ) || empty( $value ) ) {
				return [];
			}

			return $value;
		};

		add_filter( 'option_bodhi_svgs_settings', $return_array );
		add_filter( 'default_option_bodhi_svgs_settings', $return_array );
	}

	/**
	 * Disable until Onboarding is complete.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function disable_query_monitor(): void {
		if ( is_main_site() || is_network_admin() ) {
			return;
		}

		if ( get_option( 'goodbids_onboarded' ) ) {
			return;
		}

		define( 'QM_DISABLED', true );
	}
}
