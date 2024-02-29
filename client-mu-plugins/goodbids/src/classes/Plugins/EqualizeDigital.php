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
	private string $slug = 'accessibility-checker';

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		if ( ! goodbids()->is_plugin_active( $this->slug ) ) {
			return;
		}

		$this->set_default_settings();
		$this->set_license_key();
	}

	/**
	 * Set which Post Types should use Equalize Digital Accessibility
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	private function set_default_settings(): void {
		add_action(
			'init',
			function (): void {
				update_option( 'edac_post_types', [ 'post', 'page', 'gb-auction' ] );
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
		add_action(
			'goodbids_nonprofit_verified',
			function (): void {
				$license_key = vip_get_env_var( 'GOODBIDS_EDACP_LICENSE_KEY' );
				if ( ! $license_key ) {
					return;
				}

				update_option( 'edacp_license_key', $license_key );
			}
		);
	}
}
