<?php
/**
 * Watchers Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Auctions;

use GoodBids\Utilities\Log;
use WP_Query;

/**
 * Class for Watchers
 *
 * @since 1.0.0
 */
class Watchers {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const POST_TYPE = 'gb-watcher';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const AUCTION_ID_META_KEY = '_auction_id';

	/**
	 * Initialize Watchers
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Register Post Type.
		$this->register_post_type();
	}

	/**
	 * Register the Watchers post type
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
					'name'                  => _x( 'Watchers', 'Post Type General Name', 'goodbids' ),
					'singular_name'         => _x( 'Watcher', 'Post Type Singular Name', 'goodbids' ),
					'menu_name'             => __( 'Watchers', 'goodbids' ),
					'name_admin_bar'        => __( 'Watcher', 'goodbids' ),
					'archives'              => __( 'Watcher Archives', 'goodbids' ),
					'attributes'            => __( 'Watcher Attributes', 'goodbids' ),
					'parent_item_colon'     => __( 'Parent Watcher:', 'goodbids' ),
					'all_items'             => __( 'Watchers', 'goodbids' ),
					'add_new_item'          => __( 'Add New Watcher', 'goodbids' ),
					'add_new'               => __( 'Add New', 'goodbids' ),
					'new_item'              => __( 'New Watcher', 'goodbids' ),
					'edit_item'             => __( 'Edit Watcher', 'goodbids' ),
					'update_item'           => __( 'Update Watcher', 'goodbids' ),
					'view_item'             => __( 'View Watcher', 'goodbids' ),
					'view_items'            => __( 'View Watchers', 'goodbids' ),
					'search_items'          => __( 'Search Watchers', 'goodbids' ),
					'not_found'             => __( 'Not found', 'goodbids' ),
					'not_found_in_trash'    => __( 'Not found in Trash', 'goodbids' ),
					'featured_image'        => __( 'Featured Image', 'goodbids' ),
					'set_featured_image'    => __( 'Set featured image', 'goodbids' ),
					'remove_featured_image' => __( 'Remove featured image', 'goodbids' ),
					'use_featured_image'    => __( 'Use as featured image', 'goodbids' ),
					'insert_into_item'      => __( 'Insert into watcher', 'goodbids' ),
					'uploaded_to_this_item' => __( 'Uploaded to this watcher', 'goodbids' ),
					'items_list'            => __( 'Watchers list', 'goodbids' ),
					'items_list_navigation' => __( 'Watchers list navigation', 'goodbids' ),
					'filter_items_list'     => __( 'Filter watchers list', 'goodbids' ),
				];

				$args = [
					'label'               => __( 'Watcher', 'goodbids' ),
					'description'         => __( 'GoodBids Watcher Custom Post Type', 'goodbids' ),
					'labels'              => $labels,
					'supports'            => array( 'title' ),
					'hierarchical'        => false,
					'public'              => true,
					'show_ui'             => true,
					'show_in_menu'        => 'edit.php?post_type=' . goodbids()->auctions->get_post_type(),
					'menu_position'       => 10,
					'menu_icon'           => 'dashicons-visibility',
					'show_in_admin_bar'   => false,
					'show_in_nav_menus'   => false,
					'can_export'          => true,
					'has_archive'         => false,
					'exclude_from_search' => true,
					'publicly_queryable'  => false,
					'rewrite'             => false,
					'capability_type'     => 'page',
					'show_in_rest'        => true,
				];

				register_post_type( $this->get_post_type(), $args );
			}
		);
	}

	/**
	 * Returns the Watcher post type slug.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_post_type(): string {
		return self::POST_TYPE;
	}

	/**
	 * Get the Watcher for a given User/Auction
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 * @param ?int $user_id
	 *
	 * @return ?int
	 */
	public function get_watcher( ?int $auction_id = null, ?int $user_id = null ): ?int {
		if ( is_null( $user_id ) ) {
			$user_id = get_current_user_id();
		}
		if ( is_null( $auction_id ) ) {
			$auction_id = goodbids()->auctions->get_auction_id();
		}

		if ( ! $auction_id || ! $user_id ) {
			_doing_it_wrong( __METHOD__, 'Unable to determine Auction ID or User ID.', '6.4.2' );
			return null;
		}

		$watcher = new WP_Query(
			[
				'post_type'      => $this->get_post_type(),
				'posts_per_page' => 1,
				'author'         => $user_id,
				'fields'         => 'ids',
				'meta_query'     => [
					[
						'key'   => self::AUCTION_ID_META_KEY,
						'value' => $auction_id,
					],
				],
			]
		);

		if ( ! $watcher->have_posts() ) {
			return null;
		}

		return intval( $watcher->posts[0] );
	}

	/**
	 * Check if a User is Watching an Auction
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 * @param ?int $user_id
	 *
	 * @return bool
	 */
	public function is_watching( ?int $auction_id = null, ?int $user_id = null ): bool {
		return ! is_null( $this->get_watcher( $auction_id, $user_id ) );
	}

	/**
	 * Start Watching an Auction
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 * @param ?int $user_id
	 *
	 * @return bool
	 */
	public function start_watching( ?int $auction_id = null, ?int $user_id = null ): bool {
		$user = is_null( $user_id ) ? wp_get_current_user() : get_user_by( 'ID', $user_id );

		if ( is_null( $auction_id ) ) {
			$auction_id = goodbids()->auctions->get_auction_id();
		}

		if ( ! $auction_id || ! $user ) {
			_doing_it_wrong( __METHOD__, 'Unable to determine Auction ID or User.', '6.4.2' );
			return false;
		}

		$watch_title = sprintf(
			'%s is watching %s',
			esc_html( $user->display_name ),
			esc_html( get_the_title( $auction_id ) )
		);

		$watcher_id = wp_insert_post(
			[
				'post_type'   => $this->get_post_type(),
				'post_title'  => $watch_title,
				'post_status' => 'publish',
				'post_author' => $user_id,
			]
		);

		if ( is_wp_error( $watcher_id ) ) {
			Log::error( $watcher_id->get_error_message(), compact( 'user_id', 'auction_id' ) );
			return false;
		}

		update_post_meta( $watcher_id, self::AUCTION_ID_META_KEY, $auction_id );

		return true;
	}

	/**
	 * Stop Watching an Auction
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $user_id
	 * @param ?int $auction_id
	 *
	 * @return bool
	 */
	public function stop_watching( ?int $user_id = null, ?int $auction_id = null ): bool {
		$watcher_id = $this->get_watcher( $user_id, $auction_id );

		if ( is_null( $watcher_id ) ) {
			return false;
		}

		$deleted = wp_delete_post( $watcher_id, true );

		if ( ! $deleted ) {
			Log::error( 'There was a problem deleting an Auction Watcher', compact( 'user_id', 'auction_id' ) );
			return false;
		}

		return true;
	}


	/**
	 * Get all Watchers for a given User
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $user_id
	 *
	 * @return array
	 */
	public function get_watchers_by_user( ?int $user_id = null ): array {
		if ( is_null( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		if ( ! $user_id ) {
			_doing_it_wrong( __METHOD__, 'Unable to determine User ID.', '6.4.2' );
			return [];
		}

		$watchers = new WP_Query(
			[
				'post_type'      => $this->get_post_type(),
				'posts_per_page' => -1,
				'author'         => $user_id,
				'fields'         => 'ids',
			]
		);

		if ( ! $watchers->have_posts() ) {
			return [];
		}

		return $watchers->posts;
	}

	/**
	 * Get all Watchers for a given Auction
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return array
	 */
	public function get_watchers_by_auction( ?int $auction_id = null ): array {
		if ( is_null( $auction_id ) ) {
			$auction_id = goodbids()->auctions->get_auction_id();
		}

		if ( ! $auction_id ) {
			_doing_it_wrong( __METHOD__, 'Unable to determine Auction ID.', '6.4.2' );
			return [];
		}

		$watchers = new WP_Query(
			[
				'post_type'      => $this->get_post_type(),
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'meta_query'     => [
					[
						'key'   => self::AUCTION_ID_META_KEY,
						'value' => $auction_id,
					],
				],
			]
		);

		if ( ! $watchers->have_posts() ) {
			return [];
		}

		return $watchers->posts;
	}

	/**
	 * Get the number of Watchers for a given Auction
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return int
	 */
	public function get_watcher_count( ?int $auction_id = null ): int {
		return count( $this->get_watchers_by_auction( $auction_id ) );
	}
}
