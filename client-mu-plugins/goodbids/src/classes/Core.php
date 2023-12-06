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
	 * @var Core|null
	 */
	private static ?Core $instance = null;

	/**
	 * @since 1.0.0
	 * @var bool
	 */
	private bool $initialized = false;

	/**
	 * @since 1.0.0
	 * @var array
	 */
	private array $config = [];

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
		if ( ! $this->load_config() ) {
			// TODO: Log error.
			return;
		}

		$this->load_plugins();

		$this->initialized = true;
	}

	/**
	 * Sets the Plugin Config.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	private function load_config() {
		$json_path = GOODBIDS_PLUGIN_PATH . 'config.json';
		if ( ! file_exists( $json_path ) ) {
			return false;
		}

		$json = json_decode( wpcom_vip_file_get_contents( $json_path ), true );

		if ( ! is_array( $json ) ) {
			return false;
		}

		$this->config = $json;

		return true;
	}

	/**
	 * Get a config value.
	 *
	 * @param string $key Config Key.
	 *
	 * @return mixed|null
	 */
	public function get_config( string $key ) {
		return $this->config[ $key ] ?? null;
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

		$plugins = $this->get_config( 'active-plugins' );

		if ( empty( $plugins ) || ! is_array( $plugins ) ) {
			return;
		}

		foreach ( $plugins as $plugin ) {
			wpcom_vip_load_plugin( $plugin );
		}
	}
}
