<?php
/**
 * Multisite Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Network;

use Automattic\WP\Cron_Control\Events_Store;
use GoodBids\Auctions\Auction;
use GoodBids\Auctions\Bids;
use GoodBids\Auctions\Rewards;
use GoodBids\Nonprofits\Verification;
use GoodBids\Users\Permissions;
use GoodBids\Utilities\Log;
use Illuminate\Support\Collection;
use WP_Block_Type_Registry;
use WP_Post;
use WP_Site;
use WP_Query;

/**
 * Network Sites Class
 *
 * @since 1.0.0
 */
class Sites {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const ALL_AUCTIONS_TRANSIENT = '_goodbids_all_auctions';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const ABOUT_OPTION = 'gb_about_page';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const AUCTIONS_OPTION = 'gb_auctions_page';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const NAVIGATION_ID_OPTION = 'gb_navigation_id';

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		// Add New Site Form
		$this->default_new_site_email();

		// Redirect to the Verification page after creating a new site.
		$this->redirect_on_save_new_site();

		// Show Verification Status on Edit Site page.
		$this->edit_site_form_fields();

		// Default child theme logo to Main Site logo.
		$this->default_child_theme_logo();

		// New Site Initialization
		$this->activate_child_theme_on_new_site();
		$this->create_about_page();
		$this->create_all_auctions_page();
		$this->delete_sample_page();
		$this->set_default_posts_per_page();

		// Lock down the block editor.
		$this->lock_block_editor();
		$this->disable_blocks_for_nonprofits();

		// Sites Custom Columns
		$this->customize_sites_columns();

		// Restrict BDP Admin to only specific Sites
		$this->restrict_bdp_admin_sites_table();

		// Auto-register users on new sites.
		$this->auto_register_user();

		// Refresh transients when Auctions change status.
		$this->maybe_clear_transients();

		// Setup default Nonprofit Navigation.
		$this->set_nonprofit_navigation();

		// Add a cancel button on Network Admin Confirm screen.
		$this->inject_confirm_cancel();
	}

	/**
	 * Add Verification Status to the edit site form
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function edit_site_form_fields(): void {
		add_action(
			'network_site_info_form',
			function ( $site_id ) {
				if ( get_main_site_id() === $site_id ) {
					return;
				}

				$verified = goodbids()->verification->is_verified( $site_id );

				goodbids()->load_view(
					'network/edit-site-fields.php',
					compact( 'verified' )
				);
			}
		);
	}

	/**
	 * Default new site email addresses to the current user's email.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function default_new_site_email(): void {
		add_action(
			'network_site_new_form',
			function () {
				?>
				<style>
					tr.form-field:has(#admin-email),
					tr.form-field:has(#site-admin-email){
						display:none;
					}
				</style>
				<?php
			}
		);

		add_action(
			'admin_init',
			function () {
				if ( ! is_network_admin() ) {
					return;
				}

				if ( isset( $_REQUEST['action'] ) && 'add-site' === $_REQUEST['action'] ) { // phpcs:ignore
					$_POST['blog']['email'] = wp_get_current_user()->user_email;

					if ( class_exists( 'Events_Store' ) ) {
						remove_action( 'shutdown', [ Events_Store::instance(), 'maybe_install_during_shutdown' ] );
					}
				}
			}
		);
	}

	/**
	 * Redirect to the Verification page after creating a new site.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function redirect_on_save_new_site(): void {
		add_action(
			'wp_initialize_site',
			/**
			 * @param WP_Site $new_site New site object.
			 */
			function ( WP_Site $new_site ) {
				global $pagenow;

				if ( 'site-new.php' !== $pagenow || empty( $_POST ) ) { // phpcs:ignore
					return;
				}

				$this->swap(
					fn () => do_action( 'goodbids_initialize_site', $new_site->blog_id ),
					$new_site->blog_id
				);

				if ( ! headers_sent() ) {
					$redirect_url = network_admin_url( Verification::PARENT_PAGE );
					$redirect_url = add_query_arg( 'page', Verification::PAGE_SLUG, $redirect_url );
					$redirect_url = add_query_arg( 'id', $new_site->blog_id, $redirect_url );

					header( 'Location: ' . $redirect_url );
					exit;
				}
			},
			200 // Make sure DB tables are created.
		);
	}

	/**
	 * Activate nonprofit child theme.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function activate_child_theme_on_new_site(): void {
		add_action(
			'goodbids_initialize_site',
			function (): void {
				$stylesheet = 'goodbids-nonprofit';

				// Check if the Goodbids child theme exists first.
				if ( wp_get_theme( $stylesheet )->exists() ) {
					switch_theme( $stylesheet );
				}
			},
			80
		);
	}

	/**
	 * Set the GoodBids logo on the child theme.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function default_child_theme_logo(): void {
		add_filter(
			'get_custom_logo',
			function ( string $html ) {
				if ( $html ) {
					return $html;
				}

				if (
					$this->main(
						fn () => get_theme_mod( 'custom_logo' )
					)
				) {
					return get_custom_logo( get_main_site_id() );
				}

				return '<!-- No Custom Logo -->';
			}
		);
	}

	/**
	 * Set the archive default to show 9 posts per page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function set_default_posts_per_page(): void {
		add_action(
			'goodbids_initialize_site',
			function (): void {
				update_option(
					'posts_per_page',
					goodbids()->get_config( 'sites.default-posts-per-page' )
				);
			}
		);
	}

	/**
	 * Only allow super admins to lock and unlock blocks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function lock_block_editor(): void {
		add_filter(
			'block_editor_settings_all',
			static function ( $settings ) {
				if ( ! is_super_admin() ) {
					$settings['canLockBlocks'] = false;
				}
				return $settings;
			}
		);
	}

	/**
	 * Hide blocks on nonprofits sites
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function disable_blocks_for_nonprofits(): void {
		add_filter(
			'allowed_block_types_all',
			function ( $allowed_block_types ) {
				if ( is_main_site() ) {
					return $allowed_block_types;
				}

				$disabled = [
					'acf/site-directory',
				];

				if ( ! is_array( $allowed_block_types ) ) {
					$allowed_block_types = array_keys( WP_Block_Type_Registry::get_instance()->get_all_registered() );
				}

				// Remove the block from the allowed blocks.
				return array_values( array_diff( $allowed_block_types, $disabled ) );
			},
		);
	}

	/**
	 * Loop through all Nonprofit sites with a callback function
	 *
	 * @since 1.0.0
	 *
	 * @param callable|array $callback
	 * @param array          $site_args
	 *
	 * @return array
	 */
	public function loop( callable|array $callback, array $site_args = [] ): array {
		if ( ! is_callable( $callback ) ) {
			return [];
		}

		return collect( get_sites( $site_args ) )
			->flatMap(
				fn ( WP_Site $site ) => $this->swap(
					fn ( int $site_id ) => call_user_func( $callback, $site_id ),
					$site->blog_id
				)
			)
			->all();
	}

	/**
	 * Swap between sites with a callback function
	 *
	 * @since 1.0.0
	 *
	 * @param callable|array $callback
	 * @param int            $site_id
	 *
	 * @return mixed
	 */
	public function swap( callable|array $callback, int $site_id ): mixed {
		if ( ! is_callable( $callback ) ) {
			return false;
		}

		if ( get_current_blog_id() === $site_id ) {
			return call_user_func( $callback, $site_id );
		}

		switch_to_blog( $site_id );
		$return = call_user_func( $callback, $site_id );
		restore_current_blog();

		return $return;
	}

	/**
	 * Swap to the main site with a callback function
	 *
	 * @since 1.0.0
	 *
	 * @param callable|array $callback
	 *
	 * @return mixed
	 */
	public function main( callable|array $callback ): mixed {
		if ( ! is_callable( $callback ) && ! is_array( $callback ) ) {
			return false;
		}

		return $this->swap( $callback, get_main_site_id() );
	}

	/**
	 * Automatically register users as a customer when they visit a new Nonprofit site.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function auto_register_user(): void {
		add_action(
			'template_redirect',
			function (): void {
				$user_id = get_current_user_id();

				if ( ! $user_id ) {
					return;
				}

				$this->add_user_to_site( $user_id );
			}
		);
	}

	/**
	 * Adds a user to a site.
	 *
	 * @since 1.0.0
	 *
	 * @param int  $user_id
	 * @param ?int $site_id
	 *
	 * @return void
	 */
	public function add_user_to_site( int $user_id, ?int $site_id = null ): void {
		if ( null === $site_id ) {
			$site_id = get_current_blog_id();
		}

		// Check if the user is already registered on the site.
		if ( is_user_member_of_blog( $user_id, $site_id ) ) {
			return;
		}

		// Add the user to the site.
		add_user_to_blog( $site_id, $user_id, 'customer' );
	}

	/**
	 * Creates the GOODBIDS About page and sets the pattern template
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function create_about_page(): void {
		add_action(
			'goodbids_initialize_site',
			function (): void {
				$about_slug = 'about';
				$existing   = get_option( self::ABOUT_OPTION );

				// Make sure it doesn't already exist.
				if ( $existing || goodbids()->utilities->get_page_by_path( $about_slug ) ) {
					return;
				}

				$about_args = [
					'post_title'   => __( 'About GOODBIDS', 'goodbids' ),
					'post_content' => goodbids()->get_view( 'patterns/template-about-page.php' ),
					'post_type'    => 'page',
					'post_status'  => 'publish',
					'post_author'  => 1,
					'post_name'    => $about_slug,
				];

				$about_id = wp_insert_post( $about_args );

				if ( is_wp_error( $about_id ) ) {
					Log::error( $about_id->get_error_message() );
					return;
				}

				update_option( self::ABOUT_OPTION, $about_id );
			},
			100
		);
	}

	/**
	 * Create the Explore Auctions page and sets the pattern template
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function create_all_auctions_page(): void {
		add_action(
			'goodbids_initialize_site',
			function (): void {
				$auctions_slug = 'explore-auctions';
				$existing      = get_option( self::AUCTIONS_OPTION );

				// Make sure it doesn't already exist.
				if ( $existing || goodbids()->utilities->get_page_by_path( $auctions_slug ) ) {
					return;
				}

				$auctions_args = [
					'post_title'   => __( 'Explore Auctions', 'goodbids' ),
					'post_content' => goodbids()->get_view( 'patterns/template-archive-auction.php' ),
					'post_type'    => 'page',
					'post_status'  => 'publish',
					'post_author'  => 1,
					'post_name'    => $auctions_slug,
				];

				$auctions_id = wp_insert_post( $auctions_args );

				if ( is_wp_error( $auctions_id ) ) {
					Log::error( $auctions_id->get_error_message() );
					return;
				}

				update_option( self::AUCTIONS_OPTION, $auctions_id );
			},
			110
		);
	}

	/**
	 * Attempt to delete the Sample Page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function delete_sample_page(): void {
		add_action(
			'goodbids_initialize_site',
			function (): void {
				$page = goodbids()->utilities->get_page_by_path( 'sample-page' );

				if ( ! $page ) {
					return;
				}

				if ( intval( get_option( 'page_on_front' ) ) === $page->ID ) {
					return;
				}

				// Disable third-party plugin hook.
				remove_action( 'wp_trash_post', 'edac_delete_post' );

				if ( ! wp_delete_post( $page->ID ) ) {
					Log::warning( 'There was a problem deleting the Sample Page' );
				}
			},
			120
		);
	}

	/**
	 * Get the privacy policy link for the site.
	 *
	 * @since 1.0.0
	 *
	 * @return ?string
	 */
	public function get_privacy_policy_link(): ?string {
		if ( ! is_multisite() ) {
			return null;
		}

		return $this->main(
			function (): string {
				$privacy_policy_link = '';
				$privacy_policy_id   = get_option( 'wp_page_for_privacy_policy' );

				if ( $privacy_policy_id ) {
					$privacy_policy_link = sprintf(
						'<a href="%s">%s</a>',
						get_privacy_policy_url(),
						get_the_title( $privacy_policy_id ),
					);
				}

				return $privacy_policy_link;
			}
		);
	}

	/**
	 * Get the terms and conditions link for the site.
	 *
	 * @since 1.0.0
	 *
	 * @return ?string
	 */
	public function get_terms_conditions_link(): ?string {
		if ( ! is_multisite() ) {
			return null;
		}

		return $this->main(
			function (): string {
				$terms_conditions_link = '';
				$terms_conditions_id   = wc_terms_and_conditions_page_id();

				if ( $terms_conditions_id ) {
					$terms_conditions_link = sprintf(
						'<a href="%s">%s</a>',
						get_page_link( $terms_conditions_id ),
						get_the_title( $terms_conditions_id ),
					);
				}

				return $terms_conditions_link;
			}
		);
	}

	/**
	 * Get the Report an issue link.
	 *
	 * @since 1.0.0
	 *
	 * @return ?string
	 */
	public function get_report_issue_link(): ?string {
		return $this->main(
			function (): string {
				$report_issue_page = goodbids()->utilities->get_page_by_path( 'report-an-issue' );

				if ( ! $report_issue_page ) {
					return '';
				}

				return sprintf(
					'<a href="%s">%s</a>',
					esc_url( get_page_link( $report_issue_page ) ),
					esc_html( get_the_title( $report_issue_page ) ),
				);
			}
		);
	}

	/**
	 * Returns an array of all published auctions across all sites
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_args
	 *
	 * @return array
	 */
	public function get_all_auctions( array $query_args = [] ): array {
		$auctions = empty( $query_args ) ? get_transient( self::ALL_AUCTIONS_TRANSIENT ) : false;

		if ( $auctions ) {
			return $auctions;
		}

		$auctions = $this->loop(
			fn ( int $site_id ) => collect( ( goodbids()->auctions->get_all( $query_args ) )->posts )
				->map(
					fn ( int $post_id ) => [
						'post_id' => $post_id,
						'site_id' => $site_id,
					]
				)
				->all()
		);

		if ( empty( $query_args ) ) {
			set_transient( self::ALL_AUCTIONS_TRANSIENT, $auctions, DAY_IN_SECONDS );
		}

		return $auctions;
	}

	/**
	 * Get Users that have placed bids.
	 *
	 * @since 1.0.0
	 *
	 * @return int[]
	 */
	public function get_bidding_users(): array {
		$users = [];

		$this->loop(
			function () use ( &$users ) {
				$orders = goodbids()->woocommerce->orders->get_all_bid_order_id();

				foreach ( $orders as $order_id ) {
					$order   = wc_get_order( $order_id );
					$user_id = $order->get_user_id();

					if ( $user_id && ! in_array( $user_id, $users, true ) ) {
						$users[] = $user_id;
					}
				}
			}
		);

		return array_unique( $users );
	}

	/**
	 * Get all open auctions from all sites.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_args
	 *
	 * @return array
	 */
	public function get_all_open_auctions( array $query_args = [] ): array {
		return collect( $this->get_all_auctions( $query_args ) )
			->filter(
				fn ( array $auction_data ) => goodbids()->sites->swap(
					function () use ( $auction_data ) {
						$auction = goodbids()->auctions->get( $auction_data['post_id'] );
						return ! $auction->has_ended();
					},
					$auction_data['site_id']
				)
			)
			->sortByDesc(
				fn ( array $auction_data ) => [
					'bid_count'    => $this->swap(
						function () use ( $auction_data ) {
							$auction = goodbids()->auctions->get( $auction_data['post_id'] );
							return $auction->get_bid_count();
						},
						$auction_data['site_id']
					),
					'total_raised' => $this->swap(
						function () use ( $auction_data ) {
							$auction = goodbids()->auctions->get( $auction_data['post_id'] );
							return $auction->get_total_raised();
						},
						$auction_data['site_id']
					),
				]
			)
			->all();
	}

	/**
	 * Get the top 3 featured auctions from all sites.
	 * Auctions are sorted by highest bid count, then by highest total raised.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_args
	 *
	 * @return array
	 */
	public function get_featured_auctions( array $query_args = [] ): array {
		return collect( $this->get_all_open_auctions( $query_args ) )
			->slice( 0, 3 )
			->values()
			->all();
	}

	/**
	 * Reset the Auction transients when an Auction status changes.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function maybe_clear_transients(): void {

		add_action(
			'transition_post_status',
			function ( string $new_status, string $old_status, WP_Post $post ): void {
				if ( goodbids()->auctions->get_post_type() !== get_post_type( $post ) ) {
					return;
				}

				if ( $new_status === $old_status ) {
					return;
				}

				$this->clear_all_site_transients();
			},
			10,
			3
		);

		add_action(
			'wp_trash_post',
			function ( int $post_id ): void {
				if ( goodbids()->auctions->get_post_type() !== get_post_type( $post_id ) ) {
					return;
				}

				$this->clear_all_site_transients();
			}
		);
	}

	/**
	 * Clear the transients for all sites
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function clear_all_site_transients(): void {
		$this->loop(
			fn () => goodbids()->auctions->clear_transients(),
		);
	}

	/**
	 * Returns an array of all bid orders for a user across all sites
	 *
	 * @since 1.0.0
	 *
	 * @param ?int   $user_id
	 * @param array  $status
	 * @param string $type
	 * @param ?int   $limit
	 *
	 * @return array
	 */
	public function get_user_orders( ?int $user_id = null, array $status = [], string $type = Bids::ITEM_TYPE, ?int $limit = null ): array {
		$bids = collect(
			$this->loop(
				function ( $site_id ) use ( $type, $user_id, $status ) {
					if ( Bids::ITEM_TYPE === $type ) {
						$orders = goodbids()->woocommerce->account->get_user_bid_order_ids( $user_id, -1, $status );
					} elseif ( Rewards::ITEM_TYPE ) {
						$orders = goodbids()->woocommerce->account->get_user_reward_order_ids( $user_id, -1, $status );
					} else {
						return [];
					}

					return collect( $orders )
						->map(
							fn ( $order_id ) => [
								'order_id' => $order_id,
								'site_id'  => $site_id,
							]
						)
						->all();
				}
			)
		)
		->sortByDesc(
			function ( $rewards_order ) {
				return $this->swap(
					function () use ( $rewards_order ) {
						$order = wc_get_order( $rewards_order['order_id'] );
						return $order->get_date_created( 'edit' )->date( 'Y-m-d H:i:s' );
					},
					$rewards_order['site_id']
				);
			}
		);

		if ( $limit ) {
			$bids = $bids->slice( 0, $limit );
		}

		return $bids->all();
	}

	/**
	 * Returns an array of all bid orders for a user across all sites
	 *
	 * @since 1.0.0
	 *
	 * @param ?int  $user_id
	 * @param array $status
	 * @param ?int  $limit
	 *
	 * @return array
	 */
	public function get_user_bid_orders( ?int $user_id = null, array $status = [], ?int $limit = null ): array {
		return $this->get_user_orders( $user_id, $status, Bids::ITEM_TYPE, $limit );
	}

	/**
	 * Get total number of successful bids for a user across all sites
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $user_id
	 *
	 * @return int
	 */
	public function get_user_total_bids( ?int $user_id = null ): int {
		return count( $this->get_user_bid_orders( $user_id, [ 'processing', 'completed' ] ) );
	}

	/**
	 * Get total amount donated by a user across all sites
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $user_id
	 *
	 * @return float
	 */
	public function get_user_total_donated( ?int $user_id = null ): float {
		return collect( $this->get_user_bid_orders( $user_id, [ 'processing', 'completed' ] ) )
			->sum(
				fn ( array $goodbids_order ) => $this->swap(
					function () use ( $goodbids_order ) {
						$order = wc_get_order( $goodbids_order['order_id'] );
						return $order->get_total();
					},
					$goodbids_order['site_id']
				)
			);
	}

	/**
	 * Get total number of nonprofits supported by a user across all sites
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $user_id
	 *
	 * @return int
	 */
	public function get_user_nonprofits_supported( ?int $user_id = null ): int {
		return collect( $this->get_user_bid_orders( $user_id, [ 'processing', 'completed' ] ) )
			->groupBy( 'site_id' )
			->count();
	}

	/**
	 * Get all Auctions user has participated in.
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $user_id
	 * @param ?int $limit
	 *
	 * @return array
	 */
	public function get_user_participating_auctions( ?int $user_id = null, ?int $limit = null ): array {
		$participating = collect( $this->get_user_bid_orders( $user_id, [ 'processing', 'completed' ] ) )
			->map(
				function ( array $item ) {
					return $this->swap(
						function () use ( &$item ) {
							$item['auction_id'] = goodbids()->woocommerce->orders->get_auction_id( $item['order_id'] );
							return $item;
						},
						$item['site_id']
					);
				}
			)
			->groupBy( 'auction_id' )
			->map(
				fn ( Collection $group ) => [
					'site_id'    => $group->first()['site_id'],
					'auction_id' => $group->first()['auction_id'],
					'count'      => $group->count(),
				]
			);

		if ( $limit ) {
			$participating = $participating->slice( 0, $limit );
		}

		return $participating->all();
	}

	/**
	 * Get all LiveAuctions user is participated in.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_user_live_participating_auctions(): array {
		return collect( $this->get_user_participating_auctions() )
			->filter(
				fn ( array $item ) => $this->swap(
					function () use ( &$item ) {
						$auction = goodbids()->auctions->get( $item['auction_id'] );
						return Auction::STATUS_LIVE === $auction->get_status();
					},
					$item['site_id']
				)
			)
			->all();
	}

	/**
	 * Get Auctions from all Nonprofit sites won by User
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $user_id
	 *
	 * @return array
	 */
	public function get_user_auctions_won( ?int $user_id = null ): array {
		return collect( $this->get_user_participating_auctions( $user_id ) )
			->filter(
				function ( $auction_data ) {
					return $this->swap(
						function () use ( $auction_data ) {
							$auction = goodbids()->auctions->get( $auction_data['auction_id'] );
							return $auction->is_current_user_winner();
						},
						$auction_data['site_id']
					);
				}
			)
			->all();
	}

	/**
	 * Returns an array of all reward orders for a user across all sites
	 *
	 * @since 1.0.0
	 *
	 * @param ?int  $user_id
	 * @param array $status
	 *
	 * @return array
	 */
	public function get_user_reward_orders( ?int $user_id = null, array $status = [] ): array {
		return $this->get_user_orders( $user_id, $status, Rewards::ITEM_TYPE );
	}

	/**
	 * Get all watched and bid auctions from all sites for a User ID
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $user_id
	 *
	 * @return array
	 */
	public function get_watched_bid_auctions_by_user( ?int $user_id = null ): array {
		if ( is_null( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		$goodbids_orders = goodbids()->sites->get_user_bid_orders();
		$auctions        = [];

		// Get user Bids from all sites
		foreach ( $goodbids_orders as $goodbids_order ) {
			$auction_id = goodbids()->sites->swap(
				fn () => goodbids()->woocommerce->orders->get_auction_id( $goodbids_order['order_id'] ),
				$goodbids_order['site_id']
			);

			if ( 'publish' !== get_post_status( $auction_id ) ) {
				continue;
			}

			$bid_auction = [
				'site_id' => $goodbids_order['site_id'],
				'post_id' => $auction_id,
			];
			$auctions[]  = $bid_auction;
		}

		// Get all Watchers for user from all sites
		goodbids()->sites->loop(
			function ( $site_id ) use ( &$auctions, $user_id ) {
				if ( is_main_site() ) {
					return;
				}

				$watchers = goodbids()->watchers->get_watchers_by_user( $user_id );

				foreach ( $watchers as $watcher_id ) {
					$auction_id = goodbids()->watchers->get_auction_id( $watcher_id );

					if ( 'publish' !== get_post_status( $auction_id ) ) {
						continue;
					}

					$watched_auction = [
						'site_id' => $site_id,
						'post_id' => $auction_id,
					];
					$auctions[]      = $watched_auction;
				}
			}
		);

		// Filter by started and not ended and sort by end date
		return collect( $auctions )
			->unique(
				function ( $auction_data ) {
					return $auction_data['site_id'] . '|' . $auction_data['post_id'];
				}
			)
			->filter(
				fn ( array $auction_data ) => goodbids()->sites->swap(
					function () use ( $auction_data ) {
						$auction = goodbids()->auctions->get( $auction_data['post_id'] );
						return $auction->has_started() && ! $auction->has_ended();
					},
					$auction_data['site_id']
				)
			)
			->sortBy(
				fn ( array $auction_data ) => goodbids()->sites->swap(
					function () use ( $auction_data ) {
						$auction = goodbids()->auctions->get( $auction_data['post_id'] );
						return $auction->get_end_date_time();
					},
					$auction_data['site_id']
				)
			)
			->all();
	}

	/**
	 * Display custom content on Network Sites page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function customize_sites_columns(): void {
		add_filter(
			'wpmu_blogs_columns',
			function ( $columns ) {
				$columns['verified'] = __( 'Verified?', 'goodbids' );
				$columns['status']   = __( 'Status', 'goodbids' );
				$columns['standing'] = __( 'Account Standing', 'goodbids' );
				return $columns;
			}
		);

		add_action(
			'manage_sites_custom_column',
			function ( string $column, string $site_id ) {
				$nonprofit_cols = [ 'verified', 'status', 'standing' ];

				if ( get_main_site_id() === intval( $site_id ) && in_array( $column, $nonprofit_cols, true ) ) {
					echo '&mdash;';
					return;
				}

				$nonprofit = new Nonprofit( intval( $site_id ) );

				if ( 'status' === $column ) {
					echo esc_html( ucwords( $nonprofit->get_status() ) );
					return;
				}

				if ( 'verified' === $column ) {
					if ( $nonprofit->is_verified() ) {
						printf(
							'<span class="dashicons dashicons-yes-alt" title="%s"><span class="screen-reader-text">%s</span></span>',
							esc_attr__( 'Yes', 'goodbids' ),
							esc_html__( 'Yes', 'goodbids' )
						);
						return;
					}

					printf(
						'<span class="dashicons dashicons-no-alt" title="%s"><span class="screen-reader-text">%s</span></span>',
						esc_attr__( 'Pending', 'goodbids' ),
						esc_html__( 'Pending', 'goodbids' )
					);
					return;
				}

				if ( 'standing' === $column ) {
					echo esc_html( $nonprofit->get_standing() );
				}
			},
			10,
			2
		);
	}

	private function restrict_bdp_admin_sites_table(): void {
		add_filter(
			'ms_sites_list_table_query_args',
			function ( $args ) {
				$roles = wp_get_current_user()->roles;
				if ( ! in_array( Permissions::BDP_ADMIN_ROLE, $roles, true ) ) {
					return $args;
				}

				$bdp_admin_sites = [];
				$bdp_admin_id    = get_current_user_id();
				$all_user_blogs  = get_blogs_of_user( $bdp_admin_id, true );

				foreach ( $all_user_blogs as $blog_id => $blog ) {
					// Check if the specified role exists for the user on this blog
					if ( user_can( $bdp_admin_id, Permissions::BDP_ADMIN_ROLE, $blog_id ) ) {
						$bdp_admin_sites[] = $blog_id;
					}
					if ( user_can( $bdp_admin_id, 'administrator', $blog_id ) ) {
						$bdp_admin_sites[] = $blog_id;
					}
				}

				$args['site__in'] = $bdp_admin_sites;

				return $args;
			}
		);
	}

	/**
	 * Set the nonprofit navigation
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	private function set_nonprofit_navigation(): void {
		add_action(
			'goodbids_initialize_site',
			function (): void {
				if ( get_option( self::NAVIGATION_ID_OPTION ) ) {
					return;
				}

				$nav_links = [
					intval( get_option( self::ABOUT_OPTION ) ), // About Page ID.
					intval( get_option( self::AUCTIONS_OPTION ) ), // Auctions Page ID.
				];

				if ( 2 !== count( array_filter( $nav_links ) ) ) {
					Log::warning( 'Missing one or more Nonprofit Navigation items', compact( 'nav_links' ) );
					return;
				}

				$wp_navigation = new WP_Query(
					[
						'post_type'   => 'wp_navigation',
						'post_status' => [ 'publish' ],
					]
				);

				if ( ! $wp_navigation->have_posts() ) {
					$nav_id = $this->create_navigation();

					if ( ! $nav_id ) {
						Log::error( 'Unable to update Nonprofit Navigation', compact( 'wp_navigation' ) );
						return;
					}
				} else {
					$nav_id = $wp_navigation->post->ID;
				}

				// Set the navigation content
				$nav_content = [
					'ID'           => $nav_id,
					'post_content' => goodbids()->get_view( 'parts/nonprofit-navigation.php', compact( 'nav_links' ) ),
				];

				// Update the navigation content
				$update = wp_update_post( $nav_content );

				if ( is_wp_error( $update ) ) {
					Log::error( 'Error updating Nonprofit Navigation: ' . $update->get_error_message() );
					return;
				}

				update_option( self::NAVIGATION_ID_OPTION, $nav_id );
			},
			200 // Higher priority than page creation.
		);
	}

	/**
	 * Create the Default Nonprofit Navigation.
	 *
	 * @since 1.0.0
	 *
	 * @return int|null
	 */
	private function create_navigation(): ?int {
		$id = wp_insert_post(
			[
				'post_title'   => __( 'Navigation', 'goodbids' ),
				'post_content' => '<!-- wp:page-list /-->',
				'post_status'  => 'publish',
				'post_type'    => 'wp_navigation',
				'post_name'    => 'navigation',
				'post_author'  => 1,
			]
		);

		if ( is_wp_error( $id ) ) {
			Log::error( 'Error creating Nonprofit Navigation: ' . $id->get_error_message() );
			return null;
		}

		return $id;
	}

	/**
	 * Inject a cancel button on the confirm page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function inject_confirm_cancel(): void {
		add_action(
			'wpmuadminedit',
			function () {
				if ( ! is_network_admin() ) {
					return;
				}

				if ( empty( $_GET['action'] ) || 'confirm' !== $_GET['action'] ) { // phpcs:ignore
					return;
				}

				$referrer = wp_get_referer();
				$referrer = esc_js( $referrer );
				$referrer = str_replace( '&amp;', '&', $referrer );
				?>
				<script>
					document.addEventListener('DOMContentLoaded', function () {
						const submit = document.querySelector('.wrap .submit');
						if ( ! submit ) {
							return;
						}

						const cancel = document.createElement('a');
						cancel.href = '<?php echo $referrer; // phpcs:ignore ?>';
						cancel.innerText = '<?php echo esc_js( __( 'Cancel', 'goodbids' ) ); ?>';
						cancel.className = 'button button-secondary';
						cancel.style = 'margin-left: 0.5rem;';
						submit.appendChild(cancel);
					});
				</script>
				<?php
			}
		);
	}
}
