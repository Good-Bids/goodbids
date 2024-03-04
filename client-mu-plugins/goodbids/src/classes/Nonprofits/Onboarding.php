<?php
/**
 * GoodBids Nonprofit Onboarding
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Nonprofits;

use GoodBids\Auctions\Wizard;

/**
 * Setup Class
 *
 * @since 1.0.0
 */
class Onboarding {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const PAGE_SLUG = 'gb-onboarding';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const STEP_PARAM = 'step';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const STEP_CREATE_STORE = 'create-store';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const STEP_SET_UP_PAYMENTS = 'set-up-payments';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const STEP_ONBOARDING_COMPLETE = 'onboarding-complete';

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		if ( is_main_site() || is_network_admin() ) {
			return;
		}

		// Redirect to Onboarding page if not yet onboarded.
		$this->force_setup();

		// Remove any admin notices for this page.
		$this->disable_admin_notices();

		// Add the menu page for the setup dashboard
		$this->add_menu_dashboard_page();

		// Enqueue Scripts
		$this->enqueue_scripts();
	}

	/**
	 * Require Setup
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function force_setup(): void {
		add_action(
			'current_screen',
			function () {
				if ( goodbids()->network->nonprofits->is_onboarded() || $this->is_onboarding_page() || is_super_admin() ) {
					return;
				}

				$screen      = get_current_screen();
				$setup_pages = [
					'options-general',
					'woocommerce_page_wc-admin',
					'woocommerce_page_wc-settings',
				];

				if ( in_array( $screen->id, $setup_pages, true ) ) {
					return;
				}

				wp_safe_redirect( admin_url( 'admin.php?page=' . self::PAGE_SLUG ) );
				exit;
			}
		);

		add_action(
			'admin_menu',
			function () {
				if ( goodbids()->network->nonprofits->is_onboarded() || is_super_admin() ) {
					return;
				}

				global $menu;

				foreach ( $menu as &$item ) {
					if ( self::PAGE_SLUG !== $item[2] ) {
						if ( isset( $item[4] ) ) {
							$item[4] .= ' hidden';
						} else {
							$item[4] = 'hidden';
						}
					}
				}
			},
			99999
		);
	}

	/**
	 * Add the menu page for the setup dashboard
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_menu_dashboard_page(): void {
		// Disable for the main site.
		if ( is_main_site() || goodbids()->network->nonprofits->is_onboarded() ) {
			return;
		}

		add_action(
			'admin_menu',
			function () {
				add_menu_page(
					__( 'GoodBids Onboarding', 'goodbids' ),
					__( 'Onboarding', 'goodbids' ),
					'manage_options',
					self::PAGE_SLUG,
					[ $this, 'nonprofit_onboarding_page' ],
					'dashicons-admin-site-alt3',
					1.1
				);
			}
		);
	}

	/**
	 * Nonprofit Onboarding Page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function nonprofit_onboarding_page(): void {
		$id = self::PAGE_SLUG;

		// conditions

		goodbids()->load_view( 'admin/setup/onboarding.php', [ 'nonprofit_onboarding_id' => $id ] );
	}

	/**
	 * Check if the current page is the nonprofit onboarding page.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function is_onboarding_page(): bool {
		global $pagenow;

		if ( 'admin.php' !== $pagenow || empty( $_GET['page'] ) ) { // phpcs:ignore
			return false;
		}

		$page = sanitize_text_field( wp_unslash( $_GET['page'] ) ); // phpcs:ignore

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
				if ( ! $this->is_onboarding_page() ) {
					return $dependencies;
				}

				// Include WP Media.
				wp_enqueue_media();

				// Get the asset file.
				$asset_file = GOODBIDS_PLUGIN_PATH . 'build/views/nonprofit-onboarding.asset.php';
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
					GOODBIDS_PLUGIN_URL . 'build/views/nonprofit-onboarding.js',
					$script['dependencies'],
					$script['version'],
					[ 'strategy' => 'defer' ]
				);

				// Localize Vars.
				wp_localize_script( self::PAGE_SLUG, 'gbNonprofitOnboarding', $this->get_js_vars() );

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
	 * See globals.d.ts for matching TypeScript types.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	private function get_js_vars(): array {
		// TODO: Add the appropriate URLs for the onboarding steps.

		return [
			'appID'                 => self::PAGE_SLUG,
			'stepParam'             => self::STEP_PARAM,
			'stepOptions'                 => [ self::STEP_CREATE_STORE, self::STEP_SET_UP_PAYMENTS, self::STEP_ONBOARDING_COMPLETE ],
			'createStoreUrl'        => admin_url( 'admin.php?page=wc-admin&path=/setup-wizard&step=skip-guided-setup' ),
			'setUpPaymentsUrl'      => admin_url( 'admin.php?page=wc-settings&tab=checkout&section=stripe' ),
			'onboardingCompleteUrl' => admin_url(),
		];
	}

	/**
	 * Disable Admin notices for this page.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function disable_admin_notices(): void {
		add_action(
			'admin_init',
			function(): void {
				if ( ! $this->is_onboarding_page() ) {
					return;
				}

				remove_all_actions( 'admin_notices' );
			}
		);
	}
}
