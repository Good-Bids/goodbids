<?php
/**
 * Auctions Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Auctions;

use Illuminate\Support\Collection;

use WC_Order;
use WC_Product;
use WP_Query;
use WP_User;

/**
 * Class for Auctions
 *
 * @since 1.0.0
 */
class Auctions {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const POST_TYPE = 'gb-auction';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const ARCHIVE_SLUG = 'auctions';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const SINGULAR_SLUG = 'auction';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const BID_COUNT_TRANSIENT = 'gb:bid-count:%d';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const TOTAL_RAISED_TRANSIENT = 'gb:total-raised:%d';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const GUID_META_KEY = 'gb_guid';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const REWARD_AUCTION_META_KEY = '_gb_auction_id';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const CRON_AUCTION_START_HOOK = 'goodbids_auction_start_event';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const CRON_AUCTION_CLOSE_HOOK = 'goodbids_auction_close_event';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const AUCTION_STARTED_META_KEY = '_auction_started';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const AUCTION_CLOSED_META_KEY = '_auction_closed';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const AUCTION_CLOSE_META_KEY = '_auction_close';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const AUCTION_EXTENSIONS_META_KEY = '_auction_extensions';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const REWARD_REDEEMED_META_KEY = '_goodbids_reward_redeemed';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const FREE_BIDS_META_KEY = '_goodbids_free_bids';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const ORDER_TYPE_BID = 'bids';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const ORDER_TYPE_REWARD = 'rewards';

	/**
	 * @since 1.0.0
	 */
	const STATUS_DRAFT = 'Draft';

	/**
	 * @since 1.0.0
	 */
	const STATUS_UPCOMING = 'Upcoming';

	/**
	 * @since 1.0.0
	 */
	const STATUS_LIVE = 'Live';

	/**
	 * @since 1.0.0
	 */
	const STATUS_CLOSED = 'Closed';

	/**
	 * @since 1.0.0
	 * @var Bids
	 */
	public Bids $bids;

	/**
	 * @since 1.0.0
	 * @var array
	 */
	public array $cron_intervals = [];

	/**
	 * Initialize Auctions
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Initialize Submodules.
		$this->bids = new Bids();

		// Set up Cron Schedules.
		$this->cron_intervals['1min']  = [
			'interval' => MINUTE_IN_SECONDS,
			'name'     => '1min',
			'display'  => __( 'Once Every Minute', 'goodbids' ),
		];
		$this->cron_intervals['30sec'] = [
			'interval' => 30,
			'name'     => '30sec',
			'display'  => __( 'Every 30 Seconds', 'goodbids' ),
		];

		// Register Post Type.
		$this->register_post_type();

		// Register REST API Endpoints.
		$this->setup_api_endpoints();

		// Register the Auction Single and Archive templates.
		$this->set_templates();

		// Init Rewards Category.
		$this->init_rewards_category();

		// Add Auction Meta Box to display details and metrics.
		$this->add_info_meta_box();

		// Add custom Admin Columns for Auctions.
		$this->add_admin_columns();

		// Configure some values for new Auction posts.
		$this->new_auction_post_init();

		// Update Bid Product when Auction is updated.
		$this->update_bid_product_on_auction_update();

		// Connect Reward product to Auctions.
		$this->connect_reward_product_on_auction_save();

		// Clear metric transients on new Bid Order.
		$this->maybe_clear_metric_transients();

		// Sets a default image
		$this->set_default_feature_image();

		// Override End Date/Time.
		$this->override_end_date_time();

		// Generate a unique ID for each Auction.
		$this->generate_guid_on_publish();

		// Set up 1min Cron Job Schedule.
		$this->add_one_min_cron_schedule();

		// Schedule a cron job to trigger the start of auctions.
		$this->schedule_auction_start_cron();

		// Schedule a cron job to trigger the close of auctions.
		$this->schedule_auction_close_cron();

		// Use cron action to start auctions.
		$this->check_for_starting_auctions();

		// Use cron action to close auctions.
		$this->check_for_closing_auctions();

		// Extend the Auction time after bids within extension window.
		$this->maybe_extend_auction_on_order_complete();

		// Tell Auctioneer about new Bid Order.
		$this->update_auctioneer_on_order_complete();

		// Allow admins to force update the close date to the Auction End Date.
		$this->force_update_close_date();
	}

	/**
	 * Register the Auctions post type
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function register_post_type(): void {
		add_action(
			'init',
			function () {
				$labels = [
					'name'                  => _x( 'Auctions', 'Post Type General Name', 'goodbids' ),
					'singular_name'         => _x( 'Auction', 'Post Type Singular Name', 'goodbids' ),
					'menu_name'             => __( 'Auctions', 'goodbids' ),
					'name_admin_bar'        => __( 'Auction', 'goodbids' ),
					'archives'              => __( 'Auction Archives', 'goodbids' ),
					'attributes'            => __( 'Auction Attributes', 'goodbids' ),
					'parent_item_colon'     => __( 'Parent Auction:', 'goodbids' ),
					'all_items'             => __( 'All Auctions', 'goodbids' ),
					'add_new_item'          => __( 'Add New Auction', 'goodbids' ),
					'add_new'               => __( 'Add New', 'goodbids' ),
					'new_item'              => __( 'New Auction', 'goodbids' ),
					'edit_item'             => __( 'Edit Auction', 'goodbids' ),
					'update_item'           => __( 'Update Auction', 'goodbids' ),
					'view_item'             => __( 'View Auction', 'goodbids' ),
					'view_items'            => __( 'View Auctions', 'goodbids' ),
					'search_items'          => __( 'Search Auctions', 'goodbids' ),
					'not_found'             => __( 'Not found', 'goodbids' ),
					'not_found_in_trash'    => __( 'Not found in Trash', 'goodbids' ),
					'featured_image'        => __( 'Featured Image', 'goodbids' ),
					'set_featured_image'    => __( 'Set featured image', 'goodbids' ),
					'remove_featured_image' => __( 'Remove featured image', 'goodbids' ),
					'use_featured_image'    => __( 'Use as featured image', 'goodbids' ),
					'insert_into_item'      => __( 'Insert into auction', 'goodbids' ),
					'uploaded_to_this_item' => __( 'Uploaded to this auction', 'goodbids' ),
					'items_list'            => __( 'Auctions list', 'goodbids' ),
					'items_list_navigation' => __( 'Auctions list navigation', 'goodbids' ),
					'filter_items_list'     => __( 'Filter auctions list', 'goodbids' ),
				];

				$rewrite = [
					'slug'       => self::SINGULAR_SLUG,
					'with_front' => false,
					'pages'      => true,
					'feeds'      => true,
				];

				$args = [
					'label'               => __( 'Auction', 'goodbids' ),
					'description'         => __( 'GoodBids Auction Custom Post Type', 'goodbids' ),
					'labels'              => $labels,
					'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments', 'revisions' ),
					'hierarchical'        => false,
					'public'              => true,
					'show_ui'             => true,
					'show_in_menu'        => true,
					'menu_position'       => 5,
					'menu_icon'           => 'dashicons-money-alt',
					'show_in_admin_bar'   => true,
					'show_in_nav_menus'   => true,
					'can_export'          => true,
					'has_archive'         => self::ARCHIVE_SLUG,
					'exclude_from_search' => false,
					'publicly_queryable'  => true,
					'rewrite'             => $rewrite,
					'capability_type'     => 'page',
					'show_in_rest'        => true,
					'rest_base'           => self::SINGULAR_SLUG,
					'template'            => $this->get_block_template(),
				];

				register_post_type( $this->get_post_type(), $args );
			}
		);
	}

	/**
	 * Register Auction REST API Endpoints
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function setup_api_endpoints(): void {
		add_action(
			'rest_api_init',
			function () {
				( new API\Details() )->register_routes();
				( new API\User() )->register_routes();
			}
		);
	}

	/**
	 * Returns an array of all active auctions across all sites
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_all_site_auctions(): array {
		return Collection::make( get_sites() )
			->flatMap(
				function ( $site ) {
					$site_id = get_object_vars( $site )['blog_id'];
					return $this->get_site_auctions( $site_id );
				}
			)
			->filter()
			->all();
	}

	/**
	 * Returns an array of all active auctions for a given site
	 *
	 * @since 1.0.0
	 *
	 * @param int $site_id
	 *
	 * @return array
	 */
	private function get_site_auctions( int $site_id ): array {
		// Start by switching to blog and get all auctions
		switch_to_blog( $site_id );
		$args     = [
			'post_type'      => goodbids()->auctions->get_post_type(),
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		];
		$auctions = new \WP_Query( $args );
		restore_current_blog();

		if ( ! $auctions->have_posts() ) {
			return [];
		}
		return collect( $auctions->posts )->map(
			function ( $post_id ) use ( $site_id ) {
				return [
					'post_id'      => $post_id,
					'site_id'      => $site_id,
					'bid_count'    => $this->get_bid_count( $post_id ),
					'total_raised' => $this->get_total_raised( $post_id ),
				];
			}
		)->all();
	}

	/**
	 * Get the default template for Auctions.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	private function get_block_template(): array {
		return apply_filters(
			'goodbids_auction_block_template',
			[
				[
					'core/pattern',
					[
						'slug' => 'goodbids/template-auction',
					],
				],
			]
		);
	}

	/**
	 * Specify templates used for Auctions with Nice names.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function set_templates(): void {
		add_filter(
			'default_template_types',
			function ( $template_types ): array {
				$template_types[ 'single-' . $this->get_post_type() ]  = array(
					'title'       => _x( 'Single Auction', 'Template Name', 'goodbids' ),
					'description' => __( 'Displays a single Auction post.', 'goodbids' ),
				);
				$template_types[ 'archive-' . $this->get_post_type() ] = array(
					'title'       => _x( 'Archive: Auction', 'Template Name', 'goodbids' ),
					'description' => __( 'Displays a the Auctions Archive.', 'goodbids' ),
				);

				return $template_types;
			}
		);
	}

	/**
	 * Initialize Rewards Category
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init_rewards_category(): void {
		add_action(
			'init',
			function () {
				$this->get_rewards_category_id();
			}
		);
	}


	/**
	 * Returns the Auction post type slug.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_post_type(): string {
		return self::POST_TYPE;
	}

	/**
	 * Returns the current Auction post ID.
	 *
	 * @since 1.0.0
	 *
	 * @return ?int
	 */
	public function get_auction_id(): ?int {
		$auction_id = is_singular( $this->get_post_type() ) ? get_queried_object_id() : get_the_ID();

		if ( ! $auction_id && is_admin() && ! empty( $_GET['post'] ) ) { // phpcs:ignore
			$auction_id = intval( sanitize_text_field( $_GET['post'] ) ); // phpcs:ignore
		}

		if ( $this->get_post_type() !== get_post_type( $auction_id ) ) {
			return null;
		}

		return $auction_id;
	}

	/**
	 * Retrieve the Rewards category ID, or create it if it doesn't exist.
	 *
	 * @since 1.0.0
	 *
	 * @return ?int
	 */
	public function get_rewards_category_id(): ?int {
		$rewards_category = get_term_by( 'slug', self::ORDER_TYPE_REWARD, 'product_cat' );

		if ( ! $rewards_category ) {
			$rewards_category = wp_insert_term( 'Rewards', 'product_cat' );

			if ( is_wp_error( $rewards_category ) ) {
				// TODO: Log error.
				return null;
			}

			return $rewards_category['term_id'];
		}

		return $rewards_category->term_id;
	}

	/**
	 * Check if an auction has a Bid product.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return bool
	 */
	public function has_bid_product( int $auction_id ): bool {
		return boolval( $this->get_bid_product_id( $auction_id ) );
	}

	/**
	 * Retrieves the Bid product ID for an Auction.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return int
	 */
	public function get_bid_product_id( int $auction_id ): int {
		return intval( get_post_meta( $auction_id, Bids::AUCTION_BID_META_KEY, true ) );
	}

	/**
	 * Retrieves the Bid product ID for an Auction.
	 *
	 * @param int $auction_id
	 *
	 * @since 1.0.0
	 *
	 * @return ?WC_Product
	 */
	public function get_bid_product( int $auction_id ): ?WC_Product {
		$bid_product_id = $this->get_bid_product_id( $auction_id );

		if ( ! $bid_product_id ) {
			return null;
		}

		return wc_get_product( $bid_product_id );
	}

	/**
	 * Returns the URL to place a bid on an Auction.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return string
	 */
	public function get_place_bid_url( int $auction_id ): string {
		$bid_product_id = $this->get_bid_product_id( $auction_id );

		if ( ! $bid_product_id ) {
			return '';
		}

		return add_query_arg( 'add-to-cart', $bid_product_id, wc_get_checkout_url() );
	}

	/**
	 * Sets the Bid product ID for an Auction.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 * @param int $bid_product_id
	 *
	 * @return void
	 */
	public function set_bid_product_id( int $auction_id, int $bid_product_id ): void {
		update_post_meta( $auction_id, Bids::AUCTION_BID_META_KEY, $bid_product_id );
		update_post_meta( $bid_product_id, Bids::BID_AUCTION_META_KEY, $auction_id );
	}

	/**
	 * Retrieves a setting for an Auction.
	 *
	 * @since 1.0.0
	 *
	 * @param string $meta_key
	 * @param ?int   $auction_id
	 *
	 * @return mixed
	 */
	public function get_setting( string $meta_key, int $auction_id = null ): mixed {
		if ( null === $auction_id ) {
			$auction_id = $this->get_auction_id();
		}

		$value = get_field( $meta_key, $auction_id );

		return apply_filters( 'goodbids_auction_setting', $value, $meta_key, $auction_id );
	}

	/**
	 * Use Auction close date/time when asked for Auction End Date/Time.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function override_end_date_time(): void {
		add_filter(
			'goodbids_auction_setting',
			function ( $value, $meta_key, $auction_id ) {
				if ( 'auction_end' !== $meta_key ) {
					return $value;
				}

				$close = get_post_meta( $auction_id, self::AUCTION_CLOSE_META_KEY, true );

				if ( $close ) {
					// Always use the latest date value.
					if ( $close > $value ) {
						$value = $close;
					} else {
						update_post_meta( $auction_id, self::AUCTION_CLOSE_META_KEY, $value );
					}
				} elseif ( $value ) {
					// Initialize the close date.
					update_post_meta( $auction_id, self::AUCTION_CLOSE_META_KEY, $value );
				}

				return $value;
			},
			10,
			3
		);
	}

	/**
	 * Get the Auction Reward Product ID.
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return int
	 */
	public function get_reward_product_id( int $auction_id = null ): int {
		return intval( $this->get_setting( 'auction_product', $auction_id ) );
	}

	/**
	 * Get the Auction Reward Product object.
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return ?WC_Product
	 */
	public function get_reward_product( int $auction_id = null ): ?WC_Product {
		$reward_product_id = $this->get_reward_product_id( $auction_id );

		if ( ! $reward_product_id ) {
			return null;
		}

		return wc_get_product( $reward_product_id );
	}

	/**
	 * Check if reward for an Auction has been redeemed
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return bool
	 */
	public function is_reward_redeemed( ?int $auction_id = null ): bool {
		$reward_id = $this->get_reward_product_id( $auction_id );
		return boolval( get_post_meta( $reward_id, self::REWARD_REDEEMED_META_KEY, true ) );
	}

	/**
	 * Returns the Add to Cart URL for the Reward Product
	 *
	 * @since 1.0.0
	 *
	 * @param ?int|null $auction_id
	 *
	 * @return string
	 */
	public function get_claim_reward_url( int $auction_id = null ): string {
		$reward_product_id = $this->get_reward_product_id( $auction_id );

		if ( ! $reward_product_id ) {
			return '';
		}

		return add_query_arg( 'add-to-cart', $reward_product_id, wc_get_checkout_url() );
	}

	/**
	 * Get the Auction Reward Estimated Value.
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return int
	 */
	public function get_estimated_value( int $auction_id = null ): int {
		return intval( $this->get_setting( 'estimated_value', $auction_id ) );
	}

	/**
	 * Get the Auction Start Date/Time
	 *
	 * @since 1.0.0
	 *
	 * @param ?int   $auction_id
	 * @param string $format
	 *
	 * @return string
	 */
	public function get_start_date_time( int $auction_id = null, string $format = '' ): string {
		$start = $this->get_setting( 'auction_start', $auction_id );

		if ( ! $start ) {
			return '';
		}

		return goodbids()->utilities->format_date_time( $start, $format );
	}

	/**
	 * Get the Auction End Date/Time
	 *
	 * @since 1.0.0
	 *
	 * @param ?int   $auction_id
	 * @param string $format
	 *
	 * @return string
	 */
	public function get_end_date_time( int $auction_id = null, string $format = '' ): string {
		$end = $this->get_setting( 'auction_end', $auction_id );

		if ( ! $end ) {
			return '';
		}

		return goodbids()->utilities->format_date_time( $end, $format );
	}

	/**
	 * Check if an Auction has started.
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return bool
	 */
	public function has_started( int $auction_id = null ): bool {
		$start_date_time = $this->get_start_date_time( $auction_id );

		if ( ! $start_date_time ) {
			// TODO: Log error.
			return false;
		}

		return $start_date_time < current_datetime()->format( 'Y-m-d H:i:s' );
	}

	/**
	 * Check if an Auction has ended.
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return bool
	 */
	public function has_ended( int $auction_id = null ): bool {
		$end_date_time = $this->get_end_date_time( $auction_id );

		if ( ! $end_date_time ) {
			// TODO: Log error.
			return false;
		}

		return $end_date_time <= current_datetime()->format( 'Y-m-d H:i:s' );
	}

	/**
	 * Get number of auction extensions
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return int
	 */
	public function get_extensions( int $auction_id ): int {
		$extensions = get_post_meta( $auction_id, self::AUCTION_EXTENSIONS_META_KEY, true );

		if ( ! is_numeric( $extensions ) ) {
			return 0;
		}

		return intval( $extensions );
	}

	/**
	 * Check if the current Auction is in the extension window.
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return bool
	 */
	public function is_extension_window( int $auction_id = null ): bool {
		if ( $this->has_ended( $auction_id ) ) {
			return false;
		}

		// One extension = always in window.
		if ( $this->get_extensions( $auction_id ) ) {
			return true;
		}

		$end_time  = $this->get_end_date_time( $auction_id );
		$extension = $this->get_bid_extension( $auction_id );

		if ( ! $end_time || ! $extension ) {
			return false;
		}

		try {
			$end = new \DateTimeImmutable( $end_time );

			// Subtract seconds from end time to get window start.
			$window = $end->sub( new \DateInterval( 'PT' . $extension . 'S' ) );
		} catch ( \Exception $e ) {
			// TODO: Log error.
			return false;
		}

		// Check if the current time is AFTER the start of the window.
		return current_datetime()->format( 'Y-m-d H:i:s' ) >= $window->format( 'Y-m-d H:i:s' );
	}

	/**
	 * Get the Auction Bid Extension time (in seconds)
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return ?int
	 */
	public function get_bid_extension( int $auction_id = null ): ?int {
		$bid_extension = $this->get_setting( 'bid_extension', $auction_id );

		if ( ! $bid_extension ) {
			// TODO: Log error.
			return null;
		}

		$minutes = ! empty( $bid_extension['minutes'] ) ? intval( $bid_extension['minutes'] ) : 0;
		$seconds = ! empty( $bid_extension['seconds'] ) ? intval( $bid_extension['seconds'] ) : 0;

		return ( $minutes * MINUTE_IN_SECONDS ) + $seconds;
	}

	/**
	 * Get the Auction Bid Increment amount
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return int
	 */
	public function get_bid_increment( int $auction_id = null ): int {
		return intval( $this->get_setting( 'bid_increment', $auction_id ) );
	}

	/**
	 * Get the Auction Starting Bid amount
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return int
	 */
	public function get_starting_bid( int $auction_id = null ): int {
		return intval( $this->get_setting( 'starting_bid', $auction_id ) );
	}

	/**
	 * Calculate the Auction Starting Bid amount
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return int
	 */
	public function calculate_starting_bid( int $auction_id = null ): int {
		$starting_bid = $this->get_starting_bid( (int) $auction_id );
		if ( ! $starting_bid ) {
			$starting_bid = $this->get_bid_increment( (int) $auction_id );
		}

		return $starting_bid;
	}

	/**
	 * Get the Auction Goal Amount
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return int
	 */
	public function get_goal( int $auction_id = null ): int {
		return intval( $this->get_setting( 'auction_goal', $auction_id ) );
	}

	/**
	 * Get the Auction Expected High Bid Amount
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return int
	 */
	public function get_expected_high_bid( int $auction_id = null ): int {
		return intval( $this->get_setting( 'expected_high_bid', $auction_id ) );
	}

	/**
	 * Checks if Free Bids are allowed on Auction
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return bool
	 */
	public function are_free_bids_allowed( ?int $auction_id = null ): bool {
		$all_bids  = count( $this->get_bid_order_ids( $auction_id ) );
		$free_bids = count( $this->get_free_bid_order_ids( $auction_id ) );

		// Free orders must be less than 50% of all orders.
		if ( round( $free_bids / $all_bids, 2 ) > 0.5 ) {
			return false;
		}

		return true;
	}

	/**
	 * Gets the number of Available Free Bids for an Auction
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return int
	 */
	public function get_free_bids_available( ?int $auction_id = null ): int {
		if ( null === $auction_id ) {
			$auction_id = $this->get_auction_id();
		}

		$free_bids = get_post_meta( $auction_id, self::FREE_BIDS_META_KEY, true );

		// Return the default value if we have no value.
		if ( ! $free_bids && 0 !== $free_bids && '0' !== $free_bids ) {
			$free_bids = goodbids()->get_config( 'auctions.default-free-bids' );
			update_post_meta( $auction_id, self::FREE_BIDS_META_KEY, $free_bids );
		}

		return intval( $free_bids );
	}

	/**
	 * Update the Free Bids count for an Auction
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 * @param int $free_bids
	 *
	 * @return void
	 */
	public function update_free_bids( int $auction_id, int $free_bids ): void {
		update_post_meta( $auction_id, self::FREE_BIDS_META_KEY, $free_bids );
	}

	/**
	 * Maybe Award a Free Bid, if available
	 *
	 * @since 1.0.0
	 *
	 * @param ?int   $auction_id
	 * @param ?int   $user_id
	 * @param string $description
	 *
	 * @return bool
	 */
	public function maybe_award_free_bid( ?int $auction_id = null, ?int $user_id = null, string $description = '' ): bool {
		$free_bids = $this->get_free_bids_available( $auction_id );
		if ( ! $free_bids ) {
			return false;
		}

		if ( null === $user_id ) {
			$user_id = get_current_user_id();
		}

		if ( goodbids()->users->award_free_bid( $user_id, $auction_id, $description ) ) {
			--$free_bids;
			$this->update_free_bids( $auction_id, $free_bids );
			return true;
		}

		return false;
	}

	/**
	 * Update Auction with some initial data when created.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function new_auction_post_init(): void {
		add_action(
			'wp_after_insert_post',
			function ( $post_id ): void {
				// Bail if this is a revision.
				if ( wp_is_post_revision( $post_id ) || 'publish' !== get_post_status( $post_id ) ) {
					return;
				}

				// Bail if not an Auction.
				if ( $this->get_post_type() !== get_post_type( $post_id ) ) {
					return;
				}

				// Set initial values for easier querying.
				update_post_meta( $post_id, self::AUCTION_STARTED_META_KEY, 0 );
				update_post_meta( $post_id, self::AUCTION_CLOSED_META_KEY, 0 );
			},
			12
		);
	}

	/**
	 * Update the Bid Product when an Auction is updated.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function update_bid_product_on_auction_update(): void {
		add_action(
			'save_post',
			function ( int $post_id ) {
				// Bail if not an Auction and not published.
				if ( wp_is_post_revision( $post_id ) || 'publish' !== get_post_status( $post_id ) || $this->get_post_type() !== get_post_type( $post_id ) ) {
					return;
				}

				// Bail if the Auction doesn't have a Bid product.
				if ( ! $this->has_bid_product( $post_id ) ) {
					// TODO: Log error.
					return;
				}

				if ( $this->has_started( $post_id ) ) {
					// TODO: Log warning.
					return;
				}

				// Update the Bid product.
				$bid_product_id = goodbids()->auctions->get_bid_product_id( $post_id );
				$bid_product    = wc_get_product( $bid_product_id );
				$starting_bid   = $this->calculate_starting_bid( $post_id );
				$bid_price      = intval( $bid_product->get_price( 'edit' ) );

				if ( $starting_bid && $bid_price !== $starting_bid ) {
					// Update post meta.
					update_post_meta( $bid_product_id, '_price', $starting_bid );

					// Update current instance.
					$bid_product->set_price( $starting_bid );
					$bid_product->save();
				}
			}
		);
	}

	/**
	 * Update the Reward Product when an Auction is save.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function connect_reward_product_on_auction_save(): void {
		add_action(
			'save_post',
			function ( int $post_id ) {
				// Bail if not an Auction and not published.
				if ( wp_is_post_revision( $post_id ) || 'publish' !== get_post_status( $post_id ) || $this->get_post_type() !== get_post_type( $post_id ) ) {
					return;
				}

				$reward_id = $this->get_reward_product_id( $post_id );

				if ( ! $reward_id ) {
					// TODO: Log error.
					return;
				}

				update_post_meta( $reward_id, self::REWARD_AUCTION_META_KEY, $post_id );
			}
		);
	}

	/**
	 * Get the Auction ID from the Reward Product ID.
	 *
	 * @since 1.0.0
	 *
	 * @param int $reward_product_id
	 *
	 * @return ?int
	 */
	public function get_auction_id_from_reward_product_id( int $reward_product_id ): ?int {
		return intval( get_post_meta( $reward_product_id, self::REWARD_AUCTION_META_KEY, true ) );
	}

	/**
	 * Get the Product Type
	 *
	 * @since 1.0.0
	 *
	 * @param int $product_id
	 *
	 * @return ?string
	 */
	public function get_product_type( int $product_id ): ?string {
		$valid      = [ self::ORDER_TYPE_BID, self::ORDER_TYPE_REWARD ];
		$categories = get_the_terms( $product_id, 'product_cat' );

		if ( ! is_array( $categories ) ) {
			return null;
		}

		foreach ( $categories as $category ) {
			if ( in_array( $category->slug, $valid, true ) ) {
				return $category->slug;
			}
		}

		return null;
	}

	/**
	 * Get Bid Order IDs for an Auction
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 * @param int  $limit
	 * @param ?int $user_id
	 *
	 * @return int[]
	 */
	public function get_bid_order_ids( ?int $auction_id = null, int $limit = -1, ?int $user_id = null ): array {
		if ( null === $auction_id ) {
			$auction_id = $this->get_auction_id();
		}

		$args = [
			'limit'   => $limit,
			'status'  => [ 'processing', 'completed' ],
			'return'  => 'ids',
			'orderby' => 'date',
			'order'   => 'DESC',
		];

		if ( $user_id ) {
			$args['customer_id'] = $user_id;
		}

		$orders = wc_get_orders( $args );
		$return = [];

		foreach ( $orders as $order_id ) {
			// We need to filter the orders out here, for some reason.
			// meta_query doesn't seem to work with the following filter hooks:
			// - woocommerce_order_query_args
			// - woocommerce_order_data_store_cpt_get_orders_query
			if ( ! goodbids()->woocommerce->is_bid_order( $order_id ) ) {
				continue;
			}

			if ( $auction_id !== goodbids()->woocommerce->get_order_auction_id( $order_id ) ) {
				continue;
			}

			$return[] = $order_id;
		}

		return $return;
	}

	/**
	 * Get Order IDs that have been placed using a Free Bid.
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 * @param int  $limit
	 * @param ?int $user_id
	 *
	 * @return int[]
	 */
	public function get_free_bid_order_ids( ?int $auction_id = null, int $limit = -1, ?int $user_id = null ): array {
		$orders = $this->get_bid_order_ids( $auction_id, $limit, $user_id );
		$return = [];

		foreach ( $orders as $order_id ) {
			if ( ! goodbids()->woocommerce->is_free_bid_order( $order_id ) ) {
				continue;
			}

			$return[] = $order_id;
		}

		return $return;
	}

	/**
	 * Get Order Objects that have been placed using a Free Bid.
	 *
	 * @param ?int $auction_id
	 * @param int  $limit
	 * @param ?int $user_id
	 *
	 * @return WC_Order[]
	 */
	public function get_free_bid_orders( ?int $auction_id = null, int $limit = -1, ?int $user_id = null ): array {
		return array_map(
			fn ( $order ) => wc_get_order( $order ),
			$this->get_free_bid_order_ids( $auction_id, $limit, $user_id )
		);
	}

	/**
	 * Get Bid Order objects for an Auction
	 *
	 * @since 1.0.0
	 *
	 * @param int  $auction_id
	 * @param int  $limit
	 * @param ?int $user_id
	 *
	 * @return WC_Order[]
	 */
	public function get_bid_orders( int $auction_id, int $limit = -1, ?int $user_id = null ): array {
		return array_map(
			fn ( $order ) => wc_get_order( $order ),
			$this->get_bid_order_ids( $auction_id, $limit, $user_id )
		);
	}

	/**
	 * Get the Auction Bid Count. If a User ID is specified, the Bid Count will be filtered by that User.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return int
	 */
	public function get_bid_count( int $auction_id ): int {
		$transient = sprintf( self::BID_COUNT_TRANSIENT, $auction_id );
		$bid_count = get_transient( $transient );

		if ( $bid_count ) {
			return $bid_count;
		}

		$orders    = $this->get_bid_order_ids( $auction_id );
		$bid_count = count( $orders );

		set_transient( $transient, $bid_count, HOUR_IN_SECONDS );

		return $bid_count;
	}

	/**
	 * Get total bids for a specific User on an Auction.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 * @param int $user_id
	 *
	 * @return int
	 */
	public function get_user_bid_count( int $auction_id, int $user_id ): int {
		$orders = $this->get_bid_order_ids( $auction_id, -1, $user_id );
		return count( $orders );
	}

	/**
	 * Get the Auction Total Raised
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return float
	 */
	public function get_total_raised( int $auction_id ): float {
		$transient = sprintf( self::TOTAL_RAISED_TRANSIENT, $auction_id );
		$total     = get_transient( $transient );

		if ( $total ) {
			return $total;
		}

		$total = collect( $this->get_bid_orders( $auction_id ) )
			->sum( fn( $order ) => $order->get_total( 'edit' ) );

		set_transient( $transient, $total, HOUR_IN_SECONDS );

		return $total;
	}

	/**
	 * Get the Auction Total Donated by User
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 * @param int $user_id
	 *
	 * @return float
	 */
	public function get_user_total_donated( int $auction_id, int $user_id ): float {
		return collect( $this->get_bid_orders( $auction_id, -1, $user_id ) )
			->sum( fn( $order ) => $order->get_total( 'edit' ) );
	}

	/**
	 * Get the last bid order for an Auction.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return ?WC_Order
	 */
	public function get_last_bid( int $auction_id ): ?WC_Order {
		$orders = $this->get_bid_orders( $auction_id, 1 );

		if ( empty( $orders ) ) {
			return null;
		}

		return $orders[0];
	}

	/**
	 * Get the user that placed the last bid.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return ?WP_User
	 */
	public function get_last_bidder( int $auction_id ): ?WP_User {
		$last_bid = $this->get_last_bid( $auction_id );
		return $last_bid?->get_user();
	}

	/**
	 * Get the Auction's winning bidder.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return ?WP_User
	 */
	public function get_winning_bidder( int $auction_id ): ?WP_User {
		if ( ! $this->has_ended( $auction_id ) ) {
			return null;
		}

		return $this->get_last_bidder( $auction_id );
	}

	/**
	 * Checks if the current user is the winning bidder of an Auction
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return bool
	 */
	public function is_current_user_winner( int $auction_id ): bool {
		$winning_bidder = $this->get_winning_bidder( $auction_id );
		return $winning_bidder?->ID === get_current_user_id();
	}

	/**
	 * Get the status of an Auction.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return string
	 */
	public function get_status( int $auction_id ): string {
		if ( 'publish' !== get_post_status( $auction_id ) ) {
			return self::STATUS_DRAFT;
		}

		$status = self::STATUS_UPCOMING;

		if ( $this->has_started( $auction_id ) ) {
			$status = self::STATUS_LIVE;
		}

		if ( $this->has_ended( $auction_id ) ) {
			$status = self::STATUS_CLOSED;

		}

		return $status;
	}

	/**
	 * Add a meta box to show Auction metrics and other details.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_info_meta_box(): void {
		add_action(
			'current_screen',
			function (): void {
				$screen = get_current_screen();

				if ( $this->get_post_type() !== $screen->id ) {
					return;
				}

				add_meta_box(
					'goodbids-auction-info',
					__( 'Auction Info', 'goodbids' ),
					[ $this, 'info_meta_box' ],
					$screen->id,
					'side'
				);
			}
		);
	}

	/**
	 * Clear Metric Transients on new Bid Order.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function maybe_clear_metric_transients(): void {
		add_action(
			'goodbids_order_payment_complete',
			function ( int $order_id, int $auction_id ) {
				// Don't clear if this isn't a Bid order.
				if ( ! goodbids()->woocommerce->is_bid_order( $order_id ) ) {
					return;
				}

				$transients = [
					sprintf( self::BID_COUNT_TRANSIENT, $auction_id ),
					sprintf( self::TOTAL_RAISED_TRANSIENT, $auction_id ),
				];

				foreach ( $transients as $transient ) {
					delete_transient( $transient );
				}
			},
			11,
			2
		);
	}

	/**
	 * Display the Auction Metrics and other details.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function info_meta_box(): void {
		$auction_id = $this->get_auction_id();

		// Display the Auction Details.
		include GOODBIDS_PLUGIN_PATH . 'views/admin/auctions/details.php';

		echo '<hr style="margin-left:-1.5rem;width:calc(100% + 3rem);" />';

		// Display the Auction Metrics.
		include GOODBIDS_PLUGIN_PATH . 'views/admin/auctions/metrics.php';
	}

	/**
	 * Insert custom metrics admin columns
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_admin_columns(): void {
		add_filter(
			'manage_' . $this->get_post_type() . '_posts_columns',
			function ( array $columns ): array {
				$new_columns = [];

				foreach ( $columns as $column => $label ) {
					$new_columns[ $column ] = $label;

					// Insert Custom Columns after the Title column.
					if ( 'title' === $column ) {
						$new_columns['status']        = __( 'Status', 'goodbids' );
						$new_columns['starting_bid']  = __( 'Starting Bid', 'goodbids' );
						$new_columns['bid_increment'] = __( 'Bid Increment', 'goodbids' );
						$new_columns['total_bids']    = __( 'Total Bids', 'goodbids' );
						$new_columns['total_raised']  = __( 'Total Raised', 'goodbids' );
						$new_columns['last_bid']      = __( 'Last Bid', 'goodbids' );
						$new_columns['current_bid']   = __( 'Current Bid', 'goodbids' );
					}
				}

				return $new_columns;
			}
		);

		add_action(
			'manage_' . $this->get_post_type() . '_posts_custom_column',
			function ( $column, $post_id ) {
				// Columns that require a "published" status.
				$published_cols = [
					'starting_bid',
					'bid_increment',
					'total_bids',
					'total_raised',
					'last_bid',
					'current_bid',
				];

				// Bail early if Auction isn't published.
				if ( in_array( $column, $published_cols, true ) && 'publish' !== get_post_status( $post_id ) ) {
					echo '&mdash;';
					return;
				}

				// Output the column values.
				if ( 'status' === $column ) {
					echo esc_html( $this->get_status( $post_id ) );
				} elseif ( 'starting_bid' === $column ) {
					echo wp_kses_post( wc_price( $this->calculate_starting_bid( $post_id ) ) );
				} elseif ( 'bid_increment' === $column ) {
					echo wp_kses_post( wc_price( $this->get_bid_increment( $post_id ) ) );
				} elseif ( 'total_bids' === $column ) {
					echo esc_html( $this->get_bid_count( $post_id ) );
				} elseif ( 'total_raised' === $column ) {
					echo wp_kses_post( wc_price( $this->get_total_raised( $post_id ) ) );
				} elseif ( 'last_bid' === $column ) {
					$last_bid = $this->get_last_bid( $post_id );
					echo $last_bid ? wp_kses_post( wc_price( $last_bid->get_total() ) ) : '&mdash;';
				} elseif ( 'current_bid' === $column ) {
					$bid_product = wc_get_product( $this->get_bid_product_id( $post_id ) );
					echo wp_kses_post( wc_price( $bid_product->get_price() ) );
				}
			},
			10,
			2
		);
	}

	/**
	 * Set the default feature image for Auction
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function set_default_feature_image(): void {
		add_filter(
			'post_thumbnail_html',
			function ( string $html, int $post_id ) {
				if ( ! is_post_type_archive( $this->get_post_type() ) ) {
					return $html;
				}

				// If the auction has a featured image
				if ( has_post_thumbnail( $post_id ) ) {
					return $html;
				}

				// Default to the WooCommerce featured image or WooCommerce default image
				$reward = $this->get_reward_product( $post_id );

				if ( ! $reward ) {
					return $html;
				}

				return $reward->get_image();
			},
			10,
			2
		);
	}

	/**
	 * Get the Auction GUID
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return string
	 */
	public function get_guid( int $auction_id = null ): string {
		if ( null === $auction_id ) {
			$auction_id = $this->get_auction_id();
		}

		return get_post_meta( $auction_id, self::GUID_META_KEY, true );
	}

	/**
	 * Create a Bid product when an Auction is created.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function generate_guid_on_publish(): void {
		add_action(
			'wp_after_insert_post',
			function ( $post_id ): void {
				// Bail if this is a revision.
				if ( wp_is_post_revision( $post_id ) || 'publish' !== get_post_status( $post_id ) ) {
					return;
				}

				// Bail if not an Auction.
				if ( $this->get_post_type() !== get_post_type( $post_id ) ) {
					return;
				}

				// Bail if the Auction already has a guid.
				if ( $this->get_guid( $post_id ) ) {
					return;
				}

				update_post_meta( $post_id, self::GUID_META_KEY, wp_generate_uuid4() );
			}
		);
	}

	/**
	 * Add a 1min Cron Job Schedule.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_one_min_cron_schedule(): void {
		add_filter(
			'cron_schedules', // phpcs:ignore
			function ( array $schedules ): array {
				foreach ( $this->cron_intervals as $id => $props ) {
					// If one is already set, confirm it matches our schedule.
					if ( ! empty( $schedules[ $props['name'] ] ) ) {
						if ( MINUTE_IN_SECONDS === $schedules[ $props['name'] ] ) {
							continue;
						}

						$this->cron_intervals[ $id ]['name'] .= '_goodbids';
					}

					// Adds every minute cron schedule.
					$schedules[ $this->cron_intervals[ $id ]['name'] ] = [
						'interval' => $this->cron_intervals[ $id ]['interval'],
						'display'  => $this->cron_intervals[ $id ]['display'],
					];
				}

				return $schedules;
			}
		);
	}

	/**
	 * Schedule a cron job that runs every minute to trigger auctions to start.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function schedule_auction_start_cron(): void {
		add_action(
			'init',
			function (): void {
				if ( wp_next_scheduled( self::CRON_AUCTION_START_HOOK ) ) {
					return;
				}

				wp_schedule_event( strtotime( current_datetime()->format( 'Y-m-d H:i:s' ) ), $this->cron_intervals['1min']['name'], self::CRON_AUCTION_START_HOOK );
			}
		);
	}

	/**
	 * Schedule a cron job that runs every half minute to trigger auctions to close.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function schedule_auction_close_cron(): void {
		add_action(
			'init',
			function (): void {
				if ( wp_next_scheduled( self::CRON_AUCTION_CLOSE_HOOK ) ) {
					return;
				}

				wp_schedule_event( strtotime( current_datetime()->format( 'Y-m-d H:i:s' ) ), $this->cron_intervals['30sec']['name'], self::CRON_AUCTION_CLOSE_HOOK );
			}
		);
	}

	/**
	 * Check for Auctions that are starting during cron hook.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function check_for_starting_auctions(): void {
		add_action(
			self::CRON_AUCTION_START_HOOK,
			function (): void {
				$auctions = $this->get_starting_auctions();
				$starts   = 0;

				if ( ! $auctions->have_posts() ) {
					return;
				}

				foreach ( $auctions->posts as $auction_id ) {
					if ( $this->trigger_auction_start( $auction_id ) ) {
						$starts++;
					}
				}

				if ( count( $auctions->posts ) !== $starts ) {
					// TODO: Log error.
				}
			}
		);
	}

	/**
	 * Check for Auctions that have closed during cron hook.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function check_for_closing_auctions(): void {
		add_action(
			self::CRON_AUCTION_CLOSE_HOOK,
			function (): void {
				$auctions = $this->get_closing_auctions();

				if ( ! $auctions->have_posts() ) {
					return;
				}

				foreach ( $auctions->posts as $auction_id ) {
					if ( $this->trigger_auction_close( $auction_id ) ) {
						// Update the Auction meta to indicate it has closed.
						update_post_meta( $auction_id, self::AUCTION_CLOSED_META_KEY, 1 );
					}
				}
			}
		);
	}

	/**
	 * Get Auctions that are starting.
	 *
	 * @since 1.0.0
	 *
	 * @return WP_Query
	 */
	private function get_starting_auctions(): WP_Query {
		// Use the current time + 1min to get Auctions about to start.
		$auction_start = current_datetime()->add( new \DateInterval( 'PT1M' ) )->format( 'Y-m-d H:i:s' );

		$args = [
			'post_type'      => $this->get_post_type(),
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'return'         => 'ids',
			'meta_query'     => [
				[
					'key'     => 'auction_start',
					'value'   => $auction_start,
					'compare' => '<=',
				],
				[
					'key'   => self::AUCTION_STARTED_META_KEY,
					'value' => 0,
				],
			],
		];

		return new WP_Query( $args );
	}

	/**
	 * Get Auctions that are closing.
	 *
	 * @since 1.0.0
	 *
	 * @return WP_Query
	 */
	private function get_closing_auctions(): WP_Query {
		$current_time = current_datetime()->format( 'Y-m-d H:i:s' );

		$args = [
			'post_type'      => $this->get_post_type(),
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'return'         => 'ids',
			'meta_query'     => [
				[
					'key'     => self::AUCTION_CLOSE_META_KEY,
					'value'   => $current_time,
					'compare' => '<=',
				],
				[
					'key'   => self::AUCTION_CLOSED_META_KEY,
					'value' => 0,
				],
			],
		];

		return new WP_Query( $args );
	}

	/**
	 * Trigger to Node the start of an auction.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return bool
	 */
	private function trigger_auction_start( int $auction_id ): bool {
		$result = goodbids()->auctioneer->auctions->start( $auction_id );

		if ( true !== $result ) {
			return false;
		}

		// Update the Auction meta to indicate it has started.
		// This is used for the sole purposes of filtering started auctions from get_starting_auctions() method.
		update_post_meta( $auction_id, self::AUCTION_STARTED_META_KEY, 1 );

		do_action( 'goodbids_auction_start', $auction_id );

		return true;
	}

	/**
	 * Trigger to Node the close of an auction.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return bool
	 */
	private function trigger_auction_close( int $auction_id ): bool {
		$result = goodbids()->auctioneer->auctions->end( $auction_id );

		if ( true !== $result ) {
			return false;
		}

		return true;
	}

	/**
	 * Extend the Auction Close DateTime when a Bid is placed within the extension window.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function maybe_extend_auction_on_order_complete(): void {
		add_action(
			'goodbids_order_payment_complete',
			function ( int $order_id, int $auction_id ) {
				// Bail if this isn't a Bid order.
				if ( ! goodbids()->woocommerce->is_bid_order( $order_id ) ) {
					return;
				}

				if ( ! $this->is_extension_window( $auction_id ) || $this->has_ended( $auction_id ) ) {
					return;
				}

				if ( ! $this->extend_auction( $auction_id ) ) {
					// TODO: Log error.
				}
			},
			10,
			2
		);
	}

	/**
	 * Extend the auction
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return bool
	 */
	private function extend_auction( int $auction_id ): bool {
		if ( ! $this->is_extension_window( $auction_id ) ) {
			return false;
		}

		$extension = $this->get_bid_extension( $auction_id );

		// Bail early if missing extension value.
		if ( ! $extension ) {
			// TODO: Log error.
			return false;
		}

		try {
			$close      = current_datetime()->add( new \DateInterval( 'PT' . $extension . 'S' ) );
			$close_time = $close->format( 'Y-m-d H:i:s' );
		} catch ( \Exception $e ) {
			// TODO: Log error.
			return false;
		}

		// Be sure to extend, not shorten.
		if ( $close_time < $this->get_end_date_time( $auction_id ) ) {
			return false;
		}

		// Update the Auction Close Date/Time
		update_post_meta( $auction_id, self::AUCTION_CLOSE_META_KEY, $close_time );

		// Update Extensions
		$extensions = $this->get_extensions( $auction_id );
		++$extensions;
		update_post_meta( $auction_id, self::AUCTION_EXTENSIONS_META_KEY, $extensions );

		// Trigger Node to update the Auction.
		goodbids()->auctioneer->auctions->update( $auction_id );

		return true;
	}

	/**
	 * Update the Auctioneer when a Bid is placed.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function update_auctioneer_on_order_complete(): void {
		add_action(
			'goodbids_order_payment_complete',
			function ( int $order_id, int $auction_id ) {
				// Trigger Node to update the Auction.
				$extra_data = [
					'totalBids',
					'totalRaised',
					'lastBid',
					'lastBidder',
				];

				goodbids()->auctioneer->auctions->update( $auction_id, $extra_data );
			},
			10,
			2
		);
	}

	/**
	 * Admin AJAX Action from the Auction Edit screen to force update the Auction Close Date/Time.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function force_update_close_date(): void {
		add_action(
			'wp_ajax_goodbids_force_auction_close_date',
			function () {
				$nonce = ! empty( $_REQUEST['gb_nonce'] ) ? sanitize_text_field( $_REQUEST['gb_nonce'] ) : '';

				if ( ! wp_verify_nonce( $nonce, 'gb-force-update-close-date' ) ) {
					wp_send_json_error( __( 'Invalid nonce.', 'goodbids' ) );
				}

				$auction_id = isset( $_POST['auction_id'] ) ? intval( $_POST['auction_id'] ) : 0;

				if ( ! $auction_id ) {
					wp_send_json_error( __( 'Invalid Auction ID.', 'goodbids' ) );
				}

				// Get raw End Date/Time.
				$end_date = get_field( 'auction_end', $auction_id );
				update_post_meta( $auction_id, self::AUCTION_CLOSE_META_KEY, $end_date );

				wp_send_json_success(
					[
						'closeDate' => goodbids()->utilities->format_date_time( $end_date, 'n/j/Y g:i:s a' ),
					]
				);
			}
		);
	}
}
