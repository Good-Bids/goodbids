<?php
/**
 * Auctions Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Auctions;

use WC_Order;

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
	 * @var Bids
	 */
	public Bids $bids;

	/**
	 * Initialize Auctions
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Initialize Submodules.
		$this->bids = new Bids();

		// Register Post Type.
		$this->register_post_type();

		// Init Rewards Category.
		$this->init_rewards_category();

		// Add Auction Metrics Meta Box.
		$this->add_metrics_meta_box();

		// Add custom Admin Columns for Auctions.
		$this->add_admin_columns();

		// Update Bid Product when Auction is updated.
		$this->update_bid_product_on_auction_update();

		// Clear metric transients on new Bid Order.
		$this->maybe_clear_metric_transients();
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
					'supports'            => array( 'title', 'editor', 'thumbnail', 'comments', 'revisions' ),
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
					'template'            => $this->get_template(),
				];

				register_post_type( self::POST_TYPE, $args );
			}
		);
	}

	/**
	 * Get the default template for Auctions.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	private function get_template(): array {
		return apply_filters(
			'woocommerce_auction_default_template',
			[
				[
					'acf/bid-now',
				],
			]
		);
	}

	/**
	 * Initialize Rewards Category
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init_rewards_category() : void {
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
	public function get_auction_id() : ?int {
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
	public function get_rewards_category_id() : ?int {
		$rewards_category = get_term_by( 'slug', 'rewards', 'product_cat' );

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
	 * @param string   $meta_key
	 * @param ?int $auction_id
	 *
	 * @return mixed
	 */
	public function get_setting( string $meta_key, int $auction_id = null ): mixed {
		if ( ! $auction_id ) {
			$auction_id = get_the_ID();
		}

		return get_field( $meta_key, $auction_id );
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
	public function get_reward_product_id( int $auction_id = null ) : int {
		return intval( $this->get_setting( 'auction_product', $auction_id ) );
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
	public function get_estimated_value( int $auction_id = null ) : int {
		return intval( $this->get_setting( 'estimated_value', $auction_id ) );
	}

	/**
	 * Get the Auction Start Date/Time
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return string
	 */
	public function get_start_date_time( int $auction_id = null ): string {
		return $this->get_setting( 'auction_start', $auction_id );
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
			return false;
		}

		return strtotime( $start_date_time ) < time();
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
	public function get_starting_bid( int $auction_id = null ) : int {
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
	public function get_expected_high_bid( int $auction_id = null ) : int {
		return intval( $this->get_setting( 'expected_high_bid', $auction_id ) );
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
				if ( wp_is_post_revision( $post_id ) || 'publish' !== get_post_status( $post_id ) || self::POST_TYPE !== get_post_type( $post_id ) ) {
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
	 * Get the Product Type
	 *
	 * @since 1.0.0
	 *
	 * @param int $product_id
	 *
	 * @return ?string
	 */
	public function get_product_type( int $product_id ): ?string {
		$valid      = [ 'bids', 'rewards' ];
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
	 * @param int $auction_id
	 * @param int $limit
	 *
	 * @return int[]
	 */
	public function get_bid_order_ids( int $auction_id, int $limit = -1 ): array {
		$args = [
			'limit'   => $limit,
			'status'  => [ 'processing', 'completed' ],
			'return'  => 'ids',
			'orderby' => 'date',
			'order'   => 'DESC',
		];

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
	 * Get Bid Order objects for an Auction
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 * @param int $limit
	 *
	 * @return WC_Order[]
	 */
	public function get_bid_orders( int $auction_id, int $limit = -1 ): array {
		$orders = $this->get_bid_order_ids( $auction_id, $limit );

		return array_map(
			fn ( $order ) => wc_get_order( $order ),
			$orders
		);
	}

	/**
	 * Get the Auction Bid Count
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
	 * Add a meta box to show Auction metrics.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_metrics_meta_box(): void {
		add_action(
			'current_screen',
			function (): void {
				$screen = get_current_screen();

				if ( $this->get_post_type() !== $screen->id ) {
					return;
				}

				add_meta_box(
					'goodbids-auction-metrics',
					__( 'Auction Metrics', 'goodbids' ),
					[ $this, 'metrics_meta_box' ],
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
	 * Display the Auction Metrics
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function metrics_meta_box(): void {
		$auction_id = $this->get_auction_id();

		printf(
			'<p><strong>%s</strong><br>%s</p>',
			esc_html__( 'Total Bids', 'goodbids' ),
			esc_html( $this->get_bid_count( $auction_id ) )
		);

		printf(
			'<p><strong>%s</strong><br>%s</p>',
			esc_html__( 'Total Raised', 'goodbids' ),
			wp_kses_post( wc_price( $this->get_total_raised( $auction_id ) ) )
		);

		$bid_product = wc_get_product( $this->get_bid_product_id( $auction_id ) );

		printf(
			'<p><strong>%s</strong><br>%s</p>',
			esc_html__( 'Current Bid', 'goodbids' ),
			wp_kses_post( wc_price( $bid_product->get_price() ) )
		);

		$last_bid = $this->get_last_bid( $auction_id );

		if ( $last_bid ) {
			printf(
				'<p><strong>%s</strong><br><a href="%s">%s</a></p>',
				esc_html__( 'Last Bid', 'goodbids' ),
				esc_url( get_edit_post_link( $last_bid->get_id() ) ),
				wp_kses_post( wc_price( $last_bid->get_total() ) )
			);
		}
	}

	private function add_admin_columns(): void {
		add_filter(
			'manage_' . $this->get_post_type() . '_posts_columns',
			function ( array $columns ): array {
				$new_columns = [];

				foreach ( $columns as $column => $label ) {
					$new_columns[ $column ] = $label;

					// Insert Custom Columns after the Title column.
					if ( 'title' === $column ) {
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
				$bid_cols = [
					'starting_bid',
					'bid_increment',
					'total_bids',
					'total_raised',
					'last_bid',
					'current_bid',
				];

				// Bail early if Auction isn't published.
				if ( in_array( $column, $bid_cols, true ) && 'publish' !== get_post_status( $post_id ) ) {
					echo '&mdash;';
					return;
				}

				// Output the column values.
				if ( 'starting_bid' === $column ) {
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
}
