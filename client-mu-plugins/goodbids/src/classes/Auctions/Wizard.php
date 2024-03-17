<?php
/**
 * Auction Creation Wizard
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Auctions;

use GoodBids\Users\FreeBids;
use WP_Admin_Bar;

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
	 * @since 1.0.0
	 * @var string
	 */
	const MODE_PARAM = 'mode';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const CREATE_MODE_OPTION = 'create';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const CLONE_MODE_OPTION = 'clone';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const EDIT_MODE_OPTION = 'edit';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const AUCTION_ID_PARAM = 'auction_id';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const REWARD_ID_PARAM = 'reward_id';

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
		// Prevent access to the wizard if the Auction is in progress.
		$this->restrict_started_auctions();

		// Remove any admin notices for this page.
		$this->disable_admin_notices();

		// Init Admin Menu Page.
		$this->init_admin_page();

		// Enqueue Scripts
		$this->enqueue_scripts();

		// Modify the "Add New" button URL.
		$this->adjust_add_new_url();
	}

	/**
	 * Disable the Wizard for started Auctions.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function restrict_started_auctions(): void {
		add_action(
			'admin_init',
			function(): void {
				if ( ! $this->is_wizard_page() ) {
					return;
				}

				if ( empty( $_GET[ self::AUCTION_ID_PARAM ] ) ) { // phpcs:ignore
					return;
				}

				$auction_id = intval( sanitize_text_field( $_GET[ self::AUCTION_ID_PARAM ] ) ); // phpcs:ignore

				$auction_id = new Auction( $auction_id );
				if ( ! $auction_id->get_id() ) {
					return;
				}

				if ( ! in_array( $auction_id->get_status(), [ Auction::STATUS_LIVE, Auction::STATUS_CLOSED ], true ) || is_super_admin() ) {
					return;
				}

				// Redirect to Auctions page.
				wp_safe_redirect( admin_url( 'edit.php?post_type=' . goodbids()->auctions->get_post_type() ) );
				exit;
			}
		);
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
					'publish_posts',
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
	 * @param ?string $mode Wizard mode.
	 * @param ?int $auction_id Auction ID.
	 * @param ?int $reward_id  Reward ID.
	 *
	 * @return string
	 */
	public function get_wizard_url(?string $wizard_mode = null, ?int $auction_id = null, ?int $reward_id = null ): string {
		$wizard_url = admin_url( self::BASE_URL . goodbids()->auctions->get_post_type() );
		$wizard_url = add_query_arg( 'page', self::PAGE_SLUG, $wizard_url );

		if ( $wizard_mode ) {
			$wizard_url = add_query_arg( 'mode', $wizard_mode, $wizard_url );
		}
		if ( $auction_id ) {
			$wizard_url = add_query_arg( self::AUCTION_ID_PARAM, $auction_id, $wizard_url );
		}
		if ( $reward_id ) {
			$wizard_url = add_query_arg( self::REWARD_ID_PARAM, $reward_id, $wizard_url );
		}

		return $wizard_url;
	}

	/**
	 * Enqueue Admin Scripts
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function enqueue_scripts(): void {
		add_filter(
			'goodbids_admin_script_dependencies',
			function( array $dependencies ): array {
				if ( ! $this->is_wizard_page() ) {
					return $dependencies;
				}

				// Include WP Media.
				wp_enqueue_media();

				// Get the asset file.
				$asset_file = GOODBIDS_PLUGIN_PATH . 'build/views/auction-wizard.asset.php';
				if ( file_exists( $asset_file ) ) {
					$script = require $asset_file;
				} else {
					$script = [
						'dependencies' => [ 'react', 'react-dom', 'wp-api-fetch', 'wp-element', 'wp-i18n' ],
						'version'      => goodbids()->get_version()
					];
				}

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
	 * See globals.d.ts for equivalent TypeScript types.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	private function get_js_vars(): array {
		return [
			// General.
			'baseURL'          => $this->get_wizard_url(),
			'appID'            => self::PAGE_SLUG,
			'ajaxUrl'          => admin_url( 'admin-ajax.php' ),
			'adminURL'         => admin_url(),
			'auctionsIndexURL' => admin_url( 'edit.php?post_type=' . goodbids()->auctions->get_post_type() ),

			// URL Params
			'modeParam'        => self::MODE_PARAM,
			'modeParamOptions' => [
				self::CREATE_MODE_OPTION,
				self::CLONE_MODE_OPTION,
				self::EDIT_MODE_OPTION,
			],
			'auctionIdParam'   => self::AUCTION_ID_PARAM,
			'rewardIdParam'    => self::REWARD_ID_PARAM,
			'useFreeBidParam'  => FreeBids::USE_FREE_BID_PARAM,

			// Flags.
			'metricsEnabled' => false,

			// WP/WC Variables.
			'rewardCategorySlug' => Rewards::ITEM_TYPE,
		];
	}

	/**
	 * Adjust the Add New button for Auctions.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function adjust_add_new_url(): void {
		add_action(
			'wp_before_admin_bar_render',
			function(): void {
				/**	@var WP_Admin_Bar $wp_admin_bar */
				global $wp_admin_bar;
				$node = $wp_admin_bar->get_node( 'new-' . goodbids()->auctions->get_post_type() );

				if ( $node ) {
					$node->href = $this->get_wizard_url();
					$wp_admin_bar->add_node( (array) $node );
				}
			},
			30
		);

		add_action(
			'admin_footer',
			function () {
				global $pagenow;

				if ( 'edit.php' !== $pagenow || get_post_type() !== goodbids()->auctions->get_post_type() ) {
					return;
				}

				$url = $this->get_wizard_url();
				$url = esc_js( $url );
				$url = str_replace( '&amp;', '&', $url );
				?>
				<script>
					(function() {
						var addNewButton = document.querySelector('.page-title-action');
						if (addNewButton) {
							addNewButton.href = '<?php echo addslashes( $url ); // phpcs:ignore ?>';
						}
					})();
				</script>
				<?php
			}
		);
	}
}
