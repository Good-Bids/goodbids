<?php
/**
 * GoodBids Core Class
 *
 * @package GoodBids
 * @since 1.0.0
 */

namespace GoodBids;

use GoodBids\Admin\Admin;
use GoodBids\Auctioneer\Auctioneer;
use GoodBids\Auctions\Auctions;
use GoodBids\Blocks\Blocks;
use GoodBids\Frontend\Patterns;
use GoodBids\Frontend\Vite;
use GoodBids\Network\Sites;
use GoodBids\Partners\Partners;
use GoodBids\Plugins\ACF;
use GoodBids\Plugins\WooCommerce;

/**
 * Core Class
 */
class Core {

	/**
	 * @since 1.0.0
	 * @var ?Core
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
	 * @since 1.0.0
	 * @var ACF
	 */
	public ACF $acf;

	/**
	 * @since 1.0.0
	 * @var Sites
	 */
	public Sites $sites;

	/**
	 * @since 1.0.0
	 * @var Admin
	 */
	public Admin $admin;

	/**
	 * @since 1.0.0
	 * @var Auctioneer
	 */
	public Auctioneer $auctioneer;

	/**
	 * @since 1.0.0
	 * @var Auctions
	 */
	public Auctions $auctions;

	/**
	 * @since 1.0.0
	 * @var WooCommerce
	 */
	public WooCommerce $woocommerce;

	/**
	 * @since 1.0.0
	 * @var Partners
	 */
	public Partners $partners;

	/**
	 * @since 1.0.0
	 * @var Patterns
	 */
	public Patterns $patterns;

	/**
	 * @since 1.0.0
	 * @var Blocks
	 */
	public Blocks $blocks;

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
	 *
	 * @return Core
	 */
	public static function get_instance(): Core {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Get the plugin version
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_version(): string {
		$data = get_plugin_data( GOODBIDS_PLUGIN_FILE );
		return $data['Version'];
	}

	/**
	 * Initialize the plugin
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init(): void {
		if ( ! $this->load_config() ) {
			// TODO: Log error.
			return;
		}

		$this->load_dependencies();
		$this->load_plugins();
		$this->load_modules();
		$this->restrict_rest_api_access();

		$this->initialized = true;
	}

	/**
	 * Sets the Plugin Config.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function load_config(): bool {
		$json_path = GOODBIDS_PLUGIN_PATH . 'config.json';
		if ( ! file_exists( $json_path ) ) {
			return false;
		}

		$json = json_decode( file_get_contents( $json_path ), true ); // phpcs:ignore

		if ( ! is_array( $json ) ) {
			return false;
		}

		$this->config = $json;

		return true;
	}

	/**
	 * Get a config value. You can use dot notation to get nested values.
	 *
	 * @param string $key Config Key.
	 *
	 * @return mixed
	 */
	public function get_config( string $key ): mixed {
		if ( str_contains( $key, '.' ) ) {
			$keys  = explode( '.', $key );
			$value = $this->config;

			foreach ( $keys as $key ) {
				if ( ! isset( $value[ $key ] ) ) {
					return null;
				}

				$value = $value[ $key ];
			}

			return $value;
		}

		return $this->config[ $key ] ?? null;
	}

	/**
	 * Load plugin dependencies.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function load_dependencies(): void {
		require_once GOODBIDS_PLUGIN_PATH . '/src/helpers.php';

		// Init vite.
		new Vite();
	}

	/**
	 * Checks if current environment is development.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_dev_env(): bool {
		return defined( 'VIP_GO_APP_ENVIRONMENT' ) && 'local' === VIP_GO_APP_ENVIRONMENT;
	}

	/**
	 * Load 3rd Party Plugins.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function load_plugins(): void {
		if ( ! function_exists( 'wpcom_vip_load_plugin' ) ) {
			return;
		}

		$plugins = $this->get_config( 'active-plugins' );

		if ( empty( $plugins ) || ! is_array( $plugins ) ) {
			return;
		}

		foreach ( $plugins as $plugin ) {
			if ( $this->is_dev_env() ) {
				$plugin_slug = str_contains( $plugin, '/' ) ? $plugin : $plugin . '/' . $plugin . '.php';
				$plugin_path = WP_PLUGIN_DIR . '/' . $plugin_slug;
				if ( ! file_exists( $plugin_path ) ) {
					error_log(
						sprintf(
							'[GB] Attempted to load plugin %s but the file %s was not found in the plugin directory.',
							$plugin,
							$plugin_slug
						)
					);
					continue;
				}
			}

			wpcom_vip_load_plugin( $plugin );
		}
	}

	/**
	 * Check if a plugin is in the active plugins list.
	 *
	 * @since 1.0.0
	 *
	 * @param string $plugin
	 *
	 * @return bool
	 */
	public function is_plugin_active( string $plugin ): bool {
		$plugins = $this->get_config( 'active-plugins' );
		return in_array( $plugin, $plugins, true );
	}

	/**
	 * Initialize Modules after GoodBids has initialized.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function load_modules(): void {
		add_action(
			'mu_plugin_loaded',
			function () {
				$this->acf         = new ACF();
				$this->admin       = new Admin();
				$this->auctioneer  = new Auctioneer();
				$this->auctions    = new Auctions();
				$this->partners    = new Partners();
				$this->patterns    = new Patterns();
				$this->sites       = new Sites();
				$this->woocommerce = new WooCommerce();
				$this->blocks      = new Blocks();
			}
		);
	}

	/**
	 * Only allow authenticated users with specific roles to access parts of the REST API.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function restrict_rest_api_access(): void {
		add_filter(
			'rest_endpoints',
			function ( $endpoints ) {
				if ( is_user_logged_in() ) {
					// Allow access to the secure REST API endpoints for the roles specified above.
					if ( current_user_can( 'edit_posts' ) ) {
						return $endpoints;
					}
				}

				$restricted = [
					'/wp/v2/users',
					'/wp/v2/users/(?P<id>[\d]+)',
				];

				foreach ( $restricted as $endpoint ) {
					if ( isset( $endpoints[ $endpoint ] ) ) {
						unset( $endpoints[ $endpoint ] );
					}
				}

				return $endpoints;
			}
		);
	}
}
