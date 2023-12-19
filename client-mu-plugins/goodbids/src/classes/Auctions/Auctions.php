<?php
/**
 * Auctions Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Auctions;

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

		// Update Bid Product when Auction is updated.
		$this->update_bid_product_on_auction_update();

		// Set the auction archive page in the main navigation
		$this->set_auction_archive_posts_per_page();

		// Sets a default image
		$this->set_default_feature_image();
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
					'search_items'          => __( 'Search Auction', 'goodbids' ),
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

		if ( ! $auction_id && is_admin() && ! empty( $_GET['post'] ) ) {
			$auction_id = intval( sanitize_text_field( $_GET['post'] ) );
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
	public function get_reward_product_id( int $auction_id = null ): int {
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
	public function get_estimated_value( int $auction_id = null ): int {
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
					// Update postmeta.
					update_post_meta( $bid_product_id, '_price', $starting_bid );

					// Update current instance.
					$bid_product->set_price( $starting_bid );
					$bid_product->save();
				}
			}
		);
	}

	/**
	 * Set the Auction archive page to show nine posts per pagination
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function set_auction_archive_posts_per_page(): void {
		add_action(
			'pre_get_posts',
			function ( \WP_Query $query ): void {
				if ( ! is_admin() && is_post_type_archive( 'gb-auction' ) ) {
					$query->set( 'posts_per_page', 9 );
				}
			}
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

				$reward_id  = goodbids()->auctions->get_reward_product_id( $post_id );
				$product    = wc_get_product( $reward_id );
				$image_html = $product->get_image();
				return sprintf(
					$image_html,
				);
			},
			10,
			2
		);
	}
}
