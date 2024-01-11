<?php
/**
 * Partners Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Partners;

/**
 * Class for Partners
 *
 * @since 1.0.0
 */
class Partners {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const POST_TYPE = 'gb-partner';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const ARCHIVE_SLUG = 'partners';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const SINGULAR_SLUG = 'partner';


	/**
	 * Initialize Partner
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Register Post Type only on network level.
		if ( is_main_site() ) {
			$this->register_post_type();
		}
	}

	/**
	 * Register the Partners post type
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
					'name'                  => _x( 'Partners', 'Post Type General Name', 'goodbids' ),
					'singular_name'         => _x( 'Partner', 'Post Type Singular Name', 'goodbids' ),
					'menu_name'             => __( 'Partners', 'goodbids' ),
					'name_admin_bar'        => __( 'Partner', 'goodbids' ),
					'archives'              => __( 'Partner Archives', 'goodbids' ),
					'attributes'            => __( 'Partner Attributes', 'goodbids' ),
					'parent_item_colon'     => __( 'Parent Partner:', 'goodbids' ),
					'all_items'             => __( 'All Partners', 'goodbids' ),
					'add_new_item'          => __( 'Add New Partner', 'goodbids' ),
					'add_new'               => __( 'Add New', 'goodbids' ),
					'new_item'              => __( 'New Partner', 'goodbids' ),
					'edit_item'             => __( 'Edit Partner', 'goodbids' ),
					'update_item'           => __( 'Update Partner', 'goodbids' ),
					'view_item'             => __( 'View Partner', 'goodbids' ),
					'view_items'            => __( 'View Partners', 'goodbids' ),
					'search_items'          => __( 'Search Partners', 'goodbids' ),
					'not_found'             => __( 'Not found', 'goodbids' ),
					'not_found_in_trash'    => __( 'Not found in Trash', 'goodbids' ),
					'featured_image'        => __( 'Featured Image', 'goodbids' ),
					'set_featured_image'    => __( 'Set featured image', 'goodbids' ),
					'remove_featured_image' => __( 'Remove featured image', 'goodbids' ),
					'use_featured_image'    => __( 'Use as featured image', 'goodbids' ),
					'insert_into_item'      => __( 'Insert into Partner', 'goodbids' ),
					'uploaded_to_this_item' => __( 'Uploaded to this Partner', 'goodbids' ),
					'items_list'            => __( 'Partners list', 'goodbids' ),
					'items_list_navigation' => __( 'Partners list navigation', 'goodbids' ),
					'filter_items_list'     => __( 'Filter Partners list', 'goodbids' ),
				];

				$rewrite = [
					'slug'       => self::SINGULAR_SLUG,
					'with_front' => false,
					'pages'      => true,
					'feeds'      => true,
				];

				$args = [
					'label'               => __( 'Partner', 'goodbids' ),
					'description'         => __( 'GoodBids Partner Custom Post Type', 'goodbids' ),
					'labels'              => $labels,
					'supports'            => [ 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ],
					'hierarchical'        => false,
					'public'              => true,
					'show_ui'             => true,
					'show_in_menu'        => true,
					'menu_position'       => 6,
					'menu_icon'           => 'dashicons-groups',
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
				];


				register_post_type( $this->get_post_type(), $args );
			}
		);
	}

	/**
	 * Returns the Partners post type slug.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_post_type(): string {
		return self::POST_TYPE;
	}
}
