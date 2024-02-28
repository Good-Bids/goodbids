<?php
/**
 * Equalize Digital Accessibility plugin settings
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins;

use GoodBids\Core;

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
	}

	/**
	 * Default Equalize Digital Accessibility plugin settings
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	private function set_default_settings(): void {
		add_action(
			'goodbids_init_site',
			function (): void {
				update_option( 'edac_post_types', [ 'post', 'page', 'gb-auction' ] );
			}
		);
	}
}