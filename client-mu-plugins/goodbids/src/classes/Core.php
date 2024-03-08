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
use GoodBids\Auctions\Bids;
use GoodBids\Auctions\Products;
use GoodBids\Auctions\Rewards;
use GoodBids\Auctions\Watchers;
use GoodBids\Frontend\Blocks;
use GoodBids\Frontend\Assets;
use GoodBids\Frontend\Notices;
use GoodBids\Frontend\Patterns;
use GoodBids\Network\Dashboard;
use GoodBids\Network\Network;
use GoodBids\Network\Settings;
use GoodBids\Network\Sites;
use GoodBids\Nonprofits\Admin as NonprofitAdmin;
use GoodBids\Nonprofits\Guide;
use GoodBids\Nonprofits\Invoices;
use GoodBids\Nonprofits\Onboarding;
use GoodBids\Nonprofits\Verification;
use GoodBids\Partners\Partners;
use GoodBids\Plugins\ACF;
use GoodBids\Plugins\EarlyHooks;
use GoodBids\Plugins\EqualizeDigital;
use GoodBids\Plugins\OneTrust;
use GoodBids\Plugins\WooCommerce;
use GoodBids\Users\Permissions;
use GoodBids\Users\Referrals;
use GoodBids\Users\Users;
use GoodBids\Utilities\Log;
use GoodBids\Utilities\Utilities;

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
	 * @var Utilities
	 */
	public Utilities $utilities;

	/**
	 * @since 1.0.0
	 * @var ACF
	 */
	public ACF $acf;

	/**
	 * @since 1.0.0
	 * @var Dashboard
	 */
	public Dashboard $dashboard;

	/**
	 * @since 1.0.0
	 * @var Settings
	 */
	public Settings $settings;

	/**
	 * @since 1.0.0
	 * @var Network
	 */
	public Network $network;

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
	 * @var Products
	 */
	public Products $products;

	/**
	 * @since 1.0.0
	 * @var Bids
	 */
	public Bids $bids;

	/**
	 * @since 1.0.0
	 * @var Rewards
	 */
	public Rewards $rewards;

	/**
	 * @since 1.0.0
	 * @var WooCommerce
	 */
	public WooCommerce $woocommerce;

	/**
	 * @since 1.0.0
	 * @var Invoices
	 */
	public Invoices $invoices;

	/**
	 * @since 1.0.0
	 * @var Verification
	 */
	public Verification $verification;

	/**
	 * @since 1.0.0
	 * @var Notices
	 */
	public Notices $notices;

	/**
	 * @since 1.0.0
	 * @var Users
	 */
	public Users $users;

	/**
	 * @since 1.0.0
	 * @var Referrals
	 */
	public Referrals $referrals;

	/**
	 * @since 1.0.0
	 * @var Watchers
	 */
	public Watchers $watchers;

	/**
	 * @since 1.0.0
	 * @var EqualizeDigital
	 */
	public EqualizeDigital $accessibility;

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
	 * Returns the current version of the GoodBids plugin.
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
			Log::error( 'Failed to load config.json' );
			return;
		}

		// Load plugin dependencies and modules.
		$this->load_dependencies();
		$this->load_plugins();
		$this->load_modules();
		$this->init_modules();

		// Load the text domain for translations.
		$this->load_text_domain();

		// Restrict REST API access.
		$this->restrict_rest_api_access();

		// Disable CSS concatenation.
		$this->disable_css_concatenation();

		$this->initialized = true;
	}

	/**
	 * Sets the Plugin Config.
	 * Override the default config with a local config file (config.local.json).
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function load_config(): bool {
		$json_path  = GOODBIDS_PLUGIN_PATH . 'config.json';
		$local_json = GOODBIDS_PLUGIN_PATH . 'config.local.json';

		if ( ! file_exists( $json_path ) && ! file_exists( $local_json ) ) {
			Log::warning( 'Missing config.json file' );
			return false;
		}

		$json = [];

		if ( file_exists( $json_path ) ) {
			$base = json_decode( file_get_contents( $json_path ), true ); // phpcs:ignore
			if ( is_array( $base ) ) {
				$json = $base;
			}
		}

		if ( file_exists( $local_json ) ) {
			$local = json_decode( file_get_contents( $local_json ), true ); // phpcs:ignore
			if ( is_array( $local ) ) {
				$json = array_merge_recursive( $json, $local );
			}
		}

		$this->config = $json;

		return true;
	}

	/**
	 * Returns the config value for the given setting. You can use dot notation to access nested values.
	 *
	 * @param string $config_key    Config Key.
	 * @param bool   $apply_filters Whether to apply_filters to the value.
	 *
	 * @return mixed
	 */
	public function get_config( string $config_key, bool $apply_filters = true ): mixed {
		$return = $this->config[ $config_key ] ?? null;

		if ( str_contains( $config_key, '.' ) ) {
			$keys  = explode( '.', $config_key );
			$value = $this->config;

			foreach ( $keys as $key ) {
				if ( ! isset( $value[ $key ] ) ) {
					return null;
				}

				$value = $value[ $key ];
			}

			$return = $value;
		}

		if ( ! $apply_filters ) {
			return $return;
		}

		return apply_filters( 'goodbids_config_var', $return, $config_key );
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

		// Init Assets.
		new Assets();

		// Run Early Hooks
		new EarlyHooks();
	}

	/**
	 * Checks if current environment is local.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function is_local_env(): bool {
		return defined( 'VIP_GO_APP_ENVIRONMENT' ) && 'local' === VIP_GO_APP_ENVIRONMENT;
	}

	/**
	 * Checks if current environment is development.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function is_dev_env(): bool {
		return defined( 'VIP_GO_APP_ENVIRONMENT' ) && 'develop' === VIP_GO_APP_ENVIRONMENT;
	}

	/**
	 * Checks if current environment is staging.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function is_staging_env(): bool {
		return defined( 'VIP_GO_APP_ENVIRONMENT' ) && 'staging' === VIP_GO_APP_ENVIRONMENT;
	}

	/**
	 * Checks if current environment is production.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function is_prod_env(): bool {
		return defined( 'VIP_GO_APP_ENVIRONMENT' ) && 'production' === VIP_GO_APP_ENVIRONMENT;
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

		$plugins              = $this->get_config( 'active-plugins' );
		$post_install_plugins = $this->get_config( 'post-install-plugins' );

		if ( ! is_network_admin() ) {
			array_push( $plugins, ...$post_install_plugins );
		}

		if ( empty( $plugins ) || ! is_array( $plugins ) ) {
			return;
		}

		foreach ( $plugins as $plugin ) {
			if ( self::is_local_env() ) {
				$plugin_slug = str_contains( $plugin, '/' ) ? $plugin : $plugin . '/' . $plugin . '.php';
				$plugin_path = WP_PLUGIN_DIR . '/' . $plugin_slug;
				if ( ! file_exists( $plugin_path ) ) {
					Log::warning(
						sprintf(
							'Attempted to load plugin %s but the file %s was not found in the plugin directory.',
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
	 * Check if a plugin is active.
	 *
	 * @since 1.0.0
	 *
	 * @param string $plugin
	 *
	 * @return bool
	 */
	public function is_plugin_active( string $plugin ): bool {
		$plugins              = $this->get_config( 'active-plugins' );
		$post_install_plugins = $this->get_config( 'post-install-plugins' );

		if ( ! is_network_admin() ) {
			array_push( $plugins, ...$post_install_plugins );
		}

		if ( in_array( $plugin, $plugins, true ) ) {
			return true;
		}

		if ( function_exists( 'is_plugin_active' ) ) {
			$plugin_file = str_contains( $plugin, '/' ) ? $plugin : $plugin . '/' . $plugin . '.php';
			return is_plugin_active( $plugin_file );
		}

		return false;
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
				$this->utilities     = new Utilities();
				$this->acf           = new ACF();
				$this->admin         = new Admin();
				$this->auctions      = new Auctions();
				$this->auctioneer    = new Auctioneer();
				$this->products      = new Products();
				$this->bids          = new Bids();
				$this->rewards       = new Rewards();
				$this->network       = new Network();
				$this->sites         = new Sites();
				$this->invoices      = new Invoices();
				$this->verification  = new Verification();
				$this->settings      = new Settings();
				$this->woocommerce   = new WooCommerce();
				$this->notices       = new Notices();
				$this->users         = new Users();
				$this->watchers      = new Watchers();
				$this->referrals     = new Referrals();
				$this->accessibility = new EqualizeDigital();

				// Init Modules not part of the API.
				new Permissions();
				new Patterns();
				new Partners();
				new Dashboard();
				new Blocks();
				new OneTrust();
				new Onboarding();
				new Guide();
				new NonprofitAdmin();
			}
		);
	}

	/**
	 * Perform any manual module initialization.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init_modules(): void {
		// Initialize Logging.
		Log::init();
	}

	/**
	 * Get path to a view file.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name
	 *
	 * @return string
	 */
	public function get_view_path( string $name ): string {
		$path = get_stylesheet_directory() . '/goodbids/' . $name;

		if ( ! file_exists( $path ) ) {
			$path = GOODBIDS_PLUGIN_PATH . 'views/' . $name;
		}

		return apply_filters( 'goodbids_view_path', $path, $name );
	}

	/**
	 * Load a view file.
	 *
	 * @since 1.0.0
	 *
	 * @param string $_name
	 * @param array  $_data
	 *
	 * @return void
	 */
	public function load_view( string $_name, array $_data = [] ): void {
		$_path = $this->get_view_path( $_name );

		if ( ! file_exists( $_path ) ) {
			return;
		}

		extract( $_data ); // phpcs:ignore

		require $_path;
	}

	/**
	 * Get the contents of a view file.
	 *
	 * @since 1.0.0
	 *
	 * @param string $_name
	 * @param array $_data
	 *
	 * @return string
	 */
	public function get_view( string $_name, array $_data = [] ): string {
		ob_start();
		$this->load_view( $_name, $_data );
		return ob_get_clean();
	}

	/**
	 * Load Text Domain
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function load_text_domain(): void {
		add_action(
			'init',
			function () {
				load_plugin_textdomain(
					'goodbids',
					false,
					GOODBIDS_PLUGIN_PATH . 'languages'
				);
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
				// Allow permission-based access to the secure REST API endpoints.
				if ( current_user_can( 'edit_posts' ) ) {
					return $endpoints;
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

	/**
	 * Disable CSS concatenation
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function disable_css_concatenation(): void {
		add_filter( 'css_do_concat', '__return_false' );
	}
}
