<?php
/**
 * Auction Creation Wizard
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Auctions;

/**
 * Auction Wizard Class
 *
 * @since 1.0.0
 */
class Wizard {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const BASE_URL = 'edit.php?post_type=';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const PAGE_SLUG = 'gb-auction-wizard';

	/**
	 * Wizard Admin Page ID
	 *
	 * @since 1.0.0
	 * @var ?string
	 */
	private ?string $page_id = null;

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		// Remove any admin notices for this page.
		$this->disable_admin_notices();

		// Init Admin Menu Page.
		$this->init_admin_page();

		// Enqueue Scripts
		$this->enqueue_scripts();
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
				if ( ! $this->is_wizard_page() ) {
					return;
				}

				remove_all_actions( 'admin_notices' );
			}
		);
	}

	/**
	 * Check if the current page is the wizard page.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function is_wizard_page(): bool {
		if ( $this->page_id && function_exists( 'get_current_screen' ) ) {
			$screen = get_current_screen();
			if ( $screen ) {
				return $this->page_id === $screen->id;
			}
		}

		global $pagenow;
		if ( 'edit.php' !== $pagenow || empty( $_GET['post_type'] ) || empty( $_GET['page'] ) ) { // phpcs:ignore
			return false;
		}

		$post_type = sanitize_text_field( wp_unslash( $_GET['post_type'] ) ); // phpcs:ignore
		$page      = sanitize_text_field( wp_unslash( $_GET['page'] ) ); // phpcs:ignore

		if ( goodbids()->auctions->get_post_type() !== $post_type || self::PAGE_SLUG !== $page ) {
			return false;
		}

		return true;
	}

	/**
	 * Initialize admin menu page.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init_admin_page(): void {
		add_action(
			'admin_menu',
			function(): void {
				$this->page_id = add_submenu_page(
					self::BASE_URL . goodbids()->auctions->get_post_type(),
					esc_html__( 'Add New', 'goodbids' ),
					esc_html__( 'Add New', 'goodbids' ),
					'create_auctions',
					self::PAGE_SLUG,
					[ $this, 'wizard_admin_page' ],
					2
				);

				// Remove the default "Add New" link.
				remove_submenu_page(
					self::BASE_URL . goodbids()->auctions->get_post_type(),
					'post-new.php?post_type=' . goodbids()->auctions->get_post_type(),
				);
			}
		);
	}

	/**
	 * Auction Wizard Admin Page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function wizard_admin_page(): void {
		goodbids()->load_view( 'admin/auctions/wizard.php', [ 'wizard_id' => self::PAGE_SLUG ] );
	}

	/**
	 * Get the URL for the wizard
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	private function get_url(): string {
		return admin_url( self::BASE_URL . goodbids()->auctions->get_post_type() . '&page=' . self::PAGE_SLUG );
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
				if ( ! $this->is_wizard_page() ) {
					return $dependencies;
				}

				// Include WP Media.
				wp_enqueue_media();

				// Get the asset file.
				$script = require GOODBIDS_PLUGIN_PATH . 'build/views/auction-wizard.asset.php';

				// Register the auction wizard script.
				wp_register_script(
					self::PAGE_SLUG,
					GOODBIDS_PLUGIN_URL . 'build/views/auction-wizard.js',
					$script['dependencies'],
					$script['version'],
					[ 'strategy' => 'defer' ]
				);

				// Localize Vars.
				wp_localize_script( self::PAGE_SLUG, 'gbAuctionWizard', $this->get_js_vars() );

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
			// General.
			'baseURL' => $this->get_url(),
			'appID'   => self::PAGE_SLUG,
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),

			// WP/WC Variables.
			'rewardCategorySlug' => Rewards::ITEM_TYPE,
		];
	}
}
