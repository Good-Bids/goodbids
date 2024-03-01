<?php
/**
 * GoodBids Nonprofit Onboarding
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Nonprofits;

use GoodBids\Network\Nonprofit;
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
	 */
	public function __construct() {
		if ( is_main_site() || is_network_admin() ) {
			return;
		}

		$this->nonprofit = new Nonprofit( get_current_blog_id() );

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
				if ( $this->onboarded() || $this->is_setup_page() || is_super_admin() ) {
					return;
				}

				$screen      = get_current_screen();
				$setup_pages = [
					'options-general',
					'site-editor',
					'edit-page',
					'user',
					'edit-comments',
					'woocommerce_page_wc-admin',
					'woocommerce_page_wc-settings',
					'toplevel_page_jetpack',
					'jetpack_page_akismet-key-config',
					'toplevel_page_accessibility_checker',
					Wizard::BASE_URL . goodbids()->invoices->get_post_type() . '&page=' . Wizard::PAGE_SLUG,
					'edit-' . goodbids()->auctions->get_post_type(),
					'edit-' . goodbids()->invoices->get_post_type(),
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
				if ( $this->onboarded() || is_super_admin() ) {
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
		// Disable for the main site.n
		if ( is_main_site() || $this->onboarded() ) {
			return;
		}

		add_action(
			'admin_menu',
			function () {
				add_menu_page(
					__( 'Nonprofit Site Onboarding', 'goodbids' ),
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
		goodbids()->load_view( 'admin/onboarding.php', [ 'nonprofit_onboarding_id' => self::PAGE_SLUG ] );
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
			'appID'                   => self::PAGE_SLUG,
			'ajaxUrl'                 => admin_url( 'admin-ajax.php' ),
			'optionsGeneralURL'       => admin_url( 'options-general.php' ),
			'createWooCommerceURL'    => admin_url( 'admin.php?page=wc-admin&path=/setup-wizard&step=skip-guided-setup' ),
			'setUpPaymentURL'         => admin_url( 'admin.php?page=wc-settings&tab=checkout&section=stripe' ),
			'configureShippingURL'    => admin_url( 'admin.php?page=wc-settings&tab=shipping' ),
			'jetpackURL'              => admin_url( 'admin.php?page=jetpack#/dashboard' ),
			'akismetURL'              => admin_url( 'admin.php?page=akismet-key-config' ),
			'woocommerceSettingsURL'  => admin_url( 'admin.php?page=wc-settings&tab=general' ),
			'styleURL'                => admin_url( 'site-editor.php?path=/wp_global_styles' ),
			'updateLogoURL'           => admin_url( 'site-editor.php?postType=wp_template_part&postId=goodbids-nonprofit//header&categoryId=header&categoryType=wp_template_part' ),
			'customizeHomepageURL'    => admin_url( 'site-editor.php?postType=wp_template_part&postId=goodbids-nonprofit//header&categoryId=header&categoryType=wp_template_part' ),
			'pagesURL'                => admin_url( 'edit.php?post_type=page' ),
			'patternsURL'             => admin_url( 'site-editor.php?path=/patterns' ),
			'auctionWizardURL'        => admin_url( Wizard::BASE_URL . goodbids()->invoices->get_post_type() . '&page=' . Wizard::PAGE_SLUG  ),
			'addUsersURL'             => admin_url( 'user-new.php' ),
			'accessibilityCheckerURL' => admin_url( 'admin.php?page=accessibility_checker' ),
			'homeURL'                 => home_url(),
			'auctionsURL'             => admin_url( 'edit.php?post_type=' . goodbids()->invoices->get_post_type() ),
			'orderMetricsURL'         => admin_url( 'admin.php?page=wc-admin&path=/analytics/categories' ),
			'revenueMetricsURL'       => admin_url( 'admin.php?page=wc-admin&path=/analytics/revenue&chart=net_revenue&orderby=net_revenue' ),
			'invoicesURL'             => admin_url( 'edit.php?post_type=' . goodbids()->invoices->get_post_type() ),
			'commentsURL'             => admin_url( 'edit-comments.php' ),
			'siteStatus' 			  => $this->nonprofit->get_status(),
			'siteStatusOptions'       => $this->nonprofit->get_site_status_options()
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
				if ( ! $this->is_setup_page() ) {
					return;
				}

				remove_all_actions( 'admin_notices' );
			}
		);
	}

	/**
	 * Check if onboarding is completed.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function onboarded(): bool {
		return boolval( get_option( 'goodbids_onboarded' ) );
	}
}
