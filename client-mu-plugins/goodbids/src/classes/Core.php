<?php
/**
 * GoodBids Core Class
 *
 * @package GoodBids
 * @since 1.0.0
 */

namespace GoodBids;

/**
 * Core Class
 */
class Core {

	/**
	 * @since 1.0.0
	 * @var Core|null $instance
	 */
	private static ?Core $instance = null;

	private bool $initialized = false;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		if ( ! $this->initialized ) {
			$this->init();
		}
	}

	/**
	 * Get the singleton instance of this class
	 *
	 * @since 1.0.0
	 * @return Core
	 */
	public static function get_instance() : Core {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Initialize the plugin
	 *
	 * @since 1.0.0
	 */
	public function init() {
		$this->load_plugins();

		$this->initialized = true;
	}

	/**
	 * Load 3rd Party Plugins.
	 *
	 * @since 1.0.0
	 */
	private function load_plugins() {
		if ( ! function_exists( 'wpcom_vip_load_plugin' ) ) {
			return;
		}

		wpcom_vip_load_plugin( 'advanced-custom-fields-pro/acf.php' );
		wpcom_vip_load_plugin( 'woocommerce' );
		wpcom_vip_load_plugin( 'pojo-accessibility' );
		wpcom_vip_load_plugin( 'svg-support' );
		wpcom_vip_load_plugin( 'cookie-law-info' );
		wpcom_vip_load_plugin( 'user-switching' );
	}
}
