<?php
/**
 * Support Request Functionality
 *
 * @since 1.0.0
 *
 * @package GoodBids
 */

namespace GoodBids\Frontend;

use GoodBids\Core;
use GoodBids\Utilities\Log;
use WP_Post;

class SupportRequest {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const POST_TYPE = 'gb-support-request';

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		// Disable Support Requests on Main Site.
		if ( is_main_site() ) {
			return;
		}

		// Register Post Type.
		$this->register_post_type();

		// Disable the "Private" post state on Support Requests.
		$this->clear_post_state();

		// Modify the Support Request Meta Boxes.
		$this->customize_meta_boxes();

		// Disable the Quick Edit feature
		$this->disable_quick_edit();

		// Make Post Title readonly.
		$this->disable_post_title_input();

		// Disable Bulk Actions.
		$this->disable_bulk_actions();

		// Add custom Admin Columns.
		$this->add_admin_columns();
	}

	/**
	 * Register the Support Request post type
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
					'name'                  => _x( 'Support Requests', 'Post Type General Name', 'goodbids' ),
					'singular_name'         => _x( 'Support Request', 'Post Type Singular Name', 'goodbids' ),
					'menu_name'             => __( 'Support Requests', 'goodbids' ),
					'name_admin_bar'        => __( 'Support Request', 'goodbids' ),
					'archives'              => __( 'Support Request Archives', 'goodbids' ),
					'attributes'            => __( 'Support Request Attributes', 'goodbids' ),
					'parent_item_colon'     => __( 'Parent Support Request:', 'goodbids' ),
					'all_items'             => __( 'All Support Requests', 'goodbids' ),
					'add_new_item'          => __( 'Add New Support Request', 'goodbids' ),
					'add_new'               => __( 'Add New', 'goodbids' ),
					'new_item'              => __( 'New Support Request', 'goodbids' ),
					'edit_item'             => __( 'View Support Request', 'goodbids' ),
					'update_item'           => __( 'Update Support Request', 'goodbids' ),
					'view_item'             => __( 'Support Request Details', 'goodbids' ),
					'view_items'            => __( 'View Support Requests', 'goodbids' ),
					'search_items'          => __( 'Search Support Requests', 'goodbids' ),
					'not_found'             => __( 'No Support Requests have been received yet.', 'goodbids' ),
					'not_found_in_trash'    => __( 'Not Support Request found in Trash', 'goodbids' ),
					'featured_image'        => __( 'Featured Image', 'goodbids' ),
					'set_featured_image'    => __( 'Set featured image', 'goodbids' ),
					'remove_featured_image' => __( 'Remove featured image', 'goodbids' ),
					'use_featured_image'    => __( 'Use as featured image', 'goodbids' ),
					'insert_into_item'      => __( 'Insert into support request', 'goodbids' ),
					'uploaded_to_this_item' => __( 'Uploaded to this support request', 'goodbids' ),
					'items_list'            => __( 'Support Requests list', 'goodbids' ),
					'items_list_navigation' => __( 'Support Requests list navigation', 'goodbids' ),
					'filter_items_list'     => __( 'Filter support requests list', 'goodbids' ),
				];

				$args = [
					'label'               => __( 'Support Request', 'goodbids' ),
					'description'         => __( 'GoodBids Support Request Custom Post Type', 'goodbids' ),
					'labels'              => $labels,
					'supports'            => array( 'title' ),
					'hierarchical'        => false,
					'public'              => true,
					'show_ui'             => true,
					'show_in_menu'        => true,
					'menu_position'       => 9,
					'menu_icon'           => 'dashicons-sos',
					'show_in_admin_bar'   => false,
					'show_in_nav_menus'   => false,
					'can_export'          => true,
					'has_archive'         => false,
					'exclude_from_search' => false,
					'publicly_queryable'  => false,
					'rewrite'             => false,
					'capability_type'     => 'manage_options',
					'capabilities'        => [
						'read_post'          => 'manage_options',
						'read_private_posts' => 'manage_options',
						'create_post'        => 'do_not_allow',
						'publish_posts'      => 'do_not_allow',
						'create_posts'       => 'do_not_allow',
						'edit_post'          => 'manage_options',
						'edit_posts'         => 'manage_options',
						'edit_others_posts'  => 'manage_options',
					],
					'show_in_rest'        => false,
				];

				register_post_type( self::POST_TYPE, $args );
			}
		);
	}

	/**
	 * Returns the Support Request post type slug.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_post_type(): string {
		return self::POST_TYPE;
	}

	/**
	 * Prevent users from Disable Quick Edit
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function disable_quick_edit(): void {
		add_filter(
			'post_row_actions',
			function ( array $actions, $post ) {
				if ( self::POST_TYPE !== get_post_type( $post ) ) {
					return $actions;
				}

				if ( array_key_exists( 'edit', $actions ) ) {
					$actions['edit'] = str_replace( __( 'Edit' ), __( 'View' ), $actions['edit'] );
				}

				unset( $actions['inline hide-if-no-js'] );
				unset( $actions['inline'] );
				unset( $actions['clone'] );

				return $actions;
			},
			10,
			2
		);
	}

	/**
	 * Clear the Private post state for support requests.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function clear_post_state(): void {
		add_filter(
			'display_post_states',
			function ( array $post_states, WP_Post $post ): array {
				if ( self::POST_TYPE !== get_post_type( $post ) ) {
					return $post_states;
				}

				unset( $post_states['private'] );

				return $post_states;
			},
			10,
			2
		);
	}

	/**
	 * Remove the default Aside Meta box. Add 2 new meta boxes.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function customize_meta_boxes(): void {
		add_action(
			'add_meta_boxes',
			function (): void {
				remove_meta_box(
					'submitdiv',
					self::POST_TYPE,
					'side'
				);

				add_meta_box(
					'goodbids-support-request-details',
					__( 'Support Request Details', 'goodbids' ),
					function ( WP_Post $post ): void {
						$request_id = $post->ID;
						goodbids()->load_view( 'admin/support-requests/details.php', compact( 'request_id' ) );
					},
					self::POST_TYPE,
					'normal',
					'high'
				);

				add_meta_box(
					'goodbids-support-requests-actions',
					__( 'Actions', 'goodbids' ),
					function ( WP_Post $post ): void {
						?>
						<div style="display: flex; justify-content: space-between; align-items: center;margin-top: 0.5rem;">
							Test
						</div>
						<?php
					},
					self::POST_TYPE,
					'side',
					'high'
				);
			}
		);
	}

	/**
	 * Disable the Post Title Field.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function disable_post_title_input(): void {
		add_action(
			'edit_form_before_permalink',
			function (): void {
				if ( ! function_exists( 'get_current_screen' ) ) {
					return;
				}

				$screen = get_current_screen();

				if ( ! $screen || 'post' !== $screen->base || self::POST_TYPE !== $screen->post_type ) {
					return;
				}
				?>
				<script>
					// Make the post_title input box readonly and disabled.
					jQuery('input[name="post_title"]').attr('readonly', true).attr('disabled', true);
				</script>
				<?php
			}
		);
	}

	/**
	 * Disable Edit Bulk Action
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function disable_bulk_actions(): void {
		add_filter(
			'bulk_actions-edit-' . self::POST_TYPE,
			function ( array $actions ): array {
				unset( $actions[ 'edit' ] );
				return $actions;
			}
		);
	}

	/**
	 * Insert custom admin columns
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_admin_columns(): void {
		add_filter(
			'manage_' . self::POST_TYPE . '_posts_columns',
			function ( array $columns ): array {
				$new_columns = [];
				unset( $columns['cb'] );

				foreach ( $columns as $column => $label ) {
					$new_columns[ $column ] = $label;

					// Insert Custom Columns after the Title column.
					if ( 'title' === $column ) {
						$new_columns['user_id']    = __( 'User ID', 'goodbids' );
						$new_columns['auction_id'] = __( 'Auction ID', 'goodbids' );
					}
				}

				return $new_columns;
			}
		);

		add_action(
			'manage_' . self::POST_TYPE . '_posts_custom_column',
			function ( string $column, int $post_id ) {

				if ( 'user_id' === $column ) {
					echo esc_html( get_post_meta( $post_id, '_user_id', true ) );
				} elseif ( 'auction_id' === $column ) {
					$auction_id = get_post_meta( $post_id, '_auction_id', true );

					printf(
						'<a href="%s">%s</a> (<a href="%s" target="_blank">%s</a>)',
						esc_url( get_edit_post_link( $auction_id ) ),
						esc_html( get_the_title( $auction_id ) ),
						esc_url( get_permalink( $auction_id ) ),
						esc_html__( 'View', 'goodbids' )
					);
				}
			},
			10,
			2
		);
	}

	/**
	 * Create a new support request
	 *
	 * @since 1.0.0
	 *
	 * @param array $post_data
	 *
	 * @return int|bool
	 */
	public function create_request( array $post_data ): int|bool {
		$request_id = wp_insert_post( $post_data );

		if ( is_wp_error( $request_id ) ) {
			Log::error( 'Error creating support request: ' . $request_id->get_error_message(), compact( 'post_data' ) );
			return false;
		}

		if ( ! $request_id ) {
			Log::error( 'Unknown error creating support request.', compact( 'post_data' ) );
			return false;
		}

		return $request_id;
	}
}
