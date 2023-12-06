<?php
/**
 * ACF Functionality
 *
 * @package GoodBids
 */

namespace GoodBids\Plugins;

/**
 * Class for Advanced Custom Fields Pro
 *
 * @since 1.0.0
 */
class ACF {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	private string $slug = 'advanced-custom-fields-pro/acf.php';

	/**
	 * Initialize ACF Functionality
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		if ( ! goodbids()->is_plugin_active( $this->slug ) ) {
			return;
		}

		$this->disble_acf_admin();
	}

	/**
	 * Disable ACF Admin per WP VIP Documentation
	 *
	 * @link https://docs.wpvip.com/technical-references/plugin-incompatibilities/#acf
	 *
	 * @since 1.0.0
	 */
	private function disble_acf_admin() {
		add_filter( 'acf/settings/show_admin', '__return_false' );
	}
}
