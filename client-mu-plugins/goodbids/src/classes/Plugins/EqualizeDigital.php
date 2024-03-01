<?php
/**
 * Equalize Digital Accessibility plugin settings
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
class EqualizeDigital {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	private string $slug = 'accessibility-checker-pro';

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		if ( ! goodbids()->is_plugin_active( $this->slug ) ) {
			return;
		}

		// Set License key.
		$this->set_license_key();

		// Adjust Post Types
		$this->set_default_settings();
	}

	/**
	 * Set which Post Types should use Equalize Digital Accessibility
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	private function set_default_settings(): void {
		add_filter(
			'edac_filter_post_types',
			function ( array $post_types ): array {
				if ( ! in_array( goodbids()->auctions->get_post_type(), $post_types, true ) ) {
					$post_types[] = goodbids()->auctions->get_post_type();
				}
				return $post_types;
			}
		);
	}

	/**
	 * Set the Equalize Digital license key
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	private function set_license_key(): void {
		add_filter(
			'option_edacp_license_key',
			function ( string $value ): string {
				if ( $value ) {
					return $value;
				}

				$license_key = vip_get_env_var( 'GOODBIDS_EDACP_LICENSE_KEY' );

				if ( ! $license_key ) {
					return $value;
				}

				return $license_key;
			}
		);
	}
}
