<?php
/**
 * GoodBids Nonprofit Setup Guide
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Nonprofits;

use GoodBids\Auctions\Wizard;
use GoodBids\Network\Nonprofit;
use GoodBids\Users\Permissions;

/**
 * Guide Class
 *
 * @since 1.0.0
 */
class Guide {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const PAGE_SLUG = 'gb-setup-guide';

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		if ( is_main_site() || is_network_admin() ) {
			return;
		}

		// Remove any admin notices for this page.
		$this->disable_admin_notices();

		// Add the menu page for the site guide
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
		// Disable for the main site.
		if ( is_main_site() || ! goodbids()->network->nonprofits->is_onboarded() ) {
			return;
		}

		add_action(
			'admin_menu',
			function () {
				add_menu_page(
					__( 'Nonprofit Setup Guide', 'goodbids' ),
					__( 'Setup Guide', 'goodbids' ),
					'manage_options',
					self::PAGE_SLUG,
					[ $this, 'nonprofit_guide_page' ],
					'dashicons-welcome-learn-more',
					1.1
				);
			}
		);
	}

	/**
	 * Nonprofit Setup Guide Page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function nonprofit_guide_page(): void {
		goodbids()->load_view( 'admin/setup/guide.php', [ 'nonprofit_guide_id' => self::PAGE_SLUG ] );
	}

	/**
	 * Check if the current page is the nonprofit setup page.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function is_guide_page(): bool {
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
				if ( ! $this->is_guide_page() ) {
					return $dependencies;
				}

				// Get the asset file.
				$asset_file = GOODBIDS_PLUGIN_PATH . 'build/views/nonprofit-setup-guide.asset.php';
				if ( file_exists( $asset_file ) ) {
					$script = require $asset_file;
				} else {
					$script = [
						'dependencies' => [ 'react', 'react-dom', 'wp-api-fetch', 'wp-element', 'wp-i18n' ],
						'version'      => goodbids()->get_version()
					];
				}

				// Register the Nonprofit Setup Guide script.
				wp_register_script(
					self::PAGE_SLUG,
					GOODBIDS_PLUGIN_URL . 'build/views/nonprofit-setup-guide.js',
					$script['dependencies'],
					$script['version'],
					[ 'strategy' => 'defer' ]
				);

				// Localize Vars.
				wp_localize_script( self::PAGE_SLUG, 'gbNonprofitSetupGuide', $this->get_js_vars() );

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
		$nonprofit = new Nonprofit( get_current_blog_id() );

		return [
			'appID'   => self::PAGE_SLUG,
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'homeURL' => home_url(),

			'optionsGeneralURL'       => admin_url( 'options-general.php' ),
			'commentsURL'             => admin_url( 'edit-comments.php' ),
			'jetpackURL'              => admin_url( 'admin.php?page=jetpack#/dashboard' ),
			'akismetURL'              => admin_url( 'admin.php?page=akismet-key-config' ),
			'accessibilityCheckerURL' => admin_url( 'admin.php?page=accessibility_checker' ),

			'connectStripeURL'          => admin_url( 'admin.php?page=wc-settings&tab=checkout&section=stripe&panel=settings' ),
			'woocommerceSettingsURL'    => admin_url( 'admin.php?page=wc-settings&tab=general' ),
			'updateWoocommerceStoreURL' => admin_url( 'admin.php?page=wc-admin&path=/setup-wizard&step=skip-guided-setup' ),
			'configureShippingURL'      => admin_url( 'admin.php?page=wc-settings&tab=shipping' ),
			'orderMetricsURL'           => admin_url( 'admin.php?page=wc-admin&path=/analytics/categories' ),
			'revenueMetricsURL'         => admin_url( 'admin.php?page=wc-admin&path=/analytics/revenue&chart=net_revenue&orderby=net_revenue' ),

			'styleURL'             => admin_url( 'site-editor.php?path=/wp_global_styles' ),
			'uploadLogoURL' => admin_url( 'site-editor.php?postType=wp_template_part&postId=goodbids-nonprofit//header&categoryId=header&categoryType=wp_template_part' ),
			'customizeHomepageURL' => admin_url( 'site-editor.php?postType=wp_template&postId=goodbids-nonprofit//front-page' ),

			'pagesURL'    => admin_url( 'edit.php?post_type=page' ),
			'patternsURL' => admin_url( 'site-editor.php?path=/patterns' ),
			'usersUrl'    => admin_url( 'users.php' ),
			'addUsersURL' => admin_url( 'user-new.php' ),

			'auctionWizardURL' => admin_url( Wizard::BASE_URL . goodbids()->auctions->get_post_type() . '&page=' . Wizard::PAGE_SLUG  ),
			'auctionsURL'      => admin_url( 'edit.php?post_type=' . goodbids()->auctions->get_post_type() ),
			'invoicesURL'      => admin_url( 'edit.php?post_type=' . goodbids()->invoices->get_post_type() ),

			'siteId'            => $nonprofit->get_id(),
			'siteStatus'        => $nonprofit->get_status(),
			'siteStatusOptions' => goodbids()->network->nonprofits->get_site_status_options(),

			// Roles/Permissions.
			'isAdmin'    => current_user_can( 'administrator' ),
			'isBDPAdmin' => current_user_can( Permissions::BDP_ADMIN_ROLE ),
			'isJrAdmin'  => current_user_can( Permissions::JR_ADMIN_ROLE ),
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
				if ( ! $this->is_guide_page() ) {
					return;
				}

				remove_all_actions( 'admin_notices' );
			}
		);
	}
}
