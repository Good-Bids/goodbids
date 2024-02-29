<?php
/**
 * GoodBids Nonprofit Setup Dashboard
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Nonprofits;

/**
 * Setup Class
 *
 * @since 1.0.0
 */
class Setup {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const PAGE_SLUG = 'gb-nonprofit-setup';

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->add_menu_dashboard_page();

		// Enqueue Scripts
		$this->enqueue_scripts();
	}


	/**
	 * Add the menu page for the setup dashboard
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_menu_dashboard_page(): void {
		add_action(
			'admin_menu',
			function () {
				add_menu_page(
					__( 'Nonprofit Site Onboarding', 'goodbids' ),
					__( 'Site Setup', 'goodbids' ),
					'manage_options',
					self::PAGE_SLUG,
					[ $this, 'nonprofit_setup_page' ],
					'dashicons-admin-site-alt3',
					1.1
				);
			}
		);
	}

	/**
	 * Nonprofit Setup Page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function nonprofit_setup_page(): void {
		goodbids()->load_view( 'admin/setup/site-setup.php', [ 'nonprofit_set_up_id' => self::PAGE_SLUG ] );
	}

	/**
	 * Check if the current page is the nonprofit setup page.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function is_setup_page(): bool {
		global $pagenow;

		if ( 'admin.php' !== $pagenow || empty( $_GET['page'] ) ) { // phpcs:ignore
			return false;
		}

		$page      = sanitize_text_field( wp_unslash( $_GET['page'] ) ); // phpcs:ignore

		if ( self::PAGE_SLUG !== $page ) {
			return false;
		}

		return true;
	}

	/**
	 * Enqueue Admin Scripts
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_scripts(): void {
		add_filter(
			'goodbids_admin_script_dependencies',
			function( array $dependencies ): array {
				if ( ! $this->is_setup_page() ) {
					return $dependencies;
				}

				// Include WP Media.
				wp_enqueue_media();

				// Get the asset file.
				$asset_file = GOODBIDS_PLUGIN_PATH . 'build/views/nonprofit-setup.asset.php';
				if ( file_exists( $asset_file ) ) {
					$script = require $asset_file;
				} else {
					$script = [
						'dependencies' => [ 'react', 'react-dom', 'wp-api-fetch', 'wp-element', 'wp-i18n' ],
						'version'      => goodbids()->get_version()
					];
				}

				// Register the New Site Setup script.
				wp_register_script(
					self::PAGE_SLUG,
					GOODBIDS_PLUGIN_URL . 'build/views/nonprofit-setup.js',
					$script['dependencies'],
					$script['version'],
					[ 'strategy' => 'defer' ]
				);

				// Localize Vars.
				wp_localize_script( self::PAGE_SLUG, 'gbNonprofitSetup', $this->get_js_vars() );

				// Set translations.
				wp_set_script_translations( self::PAGE_SLUG, 'goodbids' );

				// Add as a dependency.
				$dependencies[] = self::PAGE_SLUG;

				return $dependencies;
			}
		);
	}

	/**
	 * Localized JS Variables
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	private function get_js_vars(): array {
		return [
			'appID'   => self::PAGE_SLUG,
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		];
	}
}
