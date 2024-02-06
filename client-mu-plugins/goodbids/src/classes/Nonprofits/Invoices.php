<?php
/**
 * GoodBids Nonprofit Invoices
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Nonprofits;

/**
 * Invoices Class
 *
 * @since 1.0.0
 */
class Invoices {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const POST_TYPE = 'gb-invoice';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const AUCTION_ID_META_KEY = '_auction_id';

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		// Register Post Type.
		$this->register_post_type();

		// Add custom Admin Columns for Watchers.
		$this->add_admin_columns();
	}

	/**
	 * Register the Invoice post type
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
					'name'                  => _x( 'Invoices', 'Post Type General Name', 'goodbids' ),
					'singular_name'         => _x( 'Invoice', 'Post Type Singular Name', 'goodbids' ),
					'menu_name'             => __( 'Invoices', 'goodbids' ),
					'name_admin_bar'        => __( 'Invoice', 'goodbids' ),
					'archives'              => __( 'Invoice Archives', 'goodbids' ),
					'attributes'            => __( 'Invoice Attributes', 'goodbids' ),
					'parent_item_colon'     => __( 'Parent Invoice:', 'goodbids' ),
					'all_items'             => __( 'All Invoices', 'goodbids' ),
					'add_new_item'          => __( 'Add New Invoice', 'goodbids' ),
					'add_new'               => __( 'Add New', 'goodbids' ),
					'new_item'              => __( 'New Invoice', 'goodbids' ),
					'edit_item'             => __( 'Edit Invoice', 'goodbids' ),
					'update_item'           => __( 'Update Invoice', 'goodbids' ),
					'view_item'             => __( 'View Invoice', 'goodbids' ),
					'view_items'            => __( 'View Invoices', 'goodbids' ),
					'search_items'          => __( 'Search Invoices', 'goodbids' ),
					'not_found'             => __( 'No Invoices have been generated yet.', 'goodbids' ),
					'not_found_in_trash'    => __( 'Not Invoices found in Trash', 'goodbids' ),
					'featured_image'        => __( 'Featured Image', 'goodbids' ),
					'set_featured_image'    => __( 'Set featured image', 'goodbids' ),
					'remove_featured_image' => __( 'Remove featured image', 'goodbids' ),
					'use_featured_image'    => __( 'Use as featured image', 'goodbids' ),
					'insert_into_item'      => __( 'Insert into invoice', 'goodbids' ),
					'uploaded_to_this_item' => __( 'Uploaded to this invoice', 'goodbids' ),
					'items_list'            => __( 'Invoices list', 'goodbids' ),
					'items_list_navigation' => __( 'Invoices list navigation', 'goodbids' ),
					'filter_items_list'     => __( 'Filter invoices list', 'goodbids' ),
				];

				$args = [
					'label'               => __( 'Invoice', 'goodbids' ),
					'description'         => __( 'GoodBids Invoice Custom Post Type', 'goodbids' ),
					'labels'              => $labels,
					'supports'            => array( 'title' ),
					'hierarchical'        => false,
					'public'              => true,
					'show_ui'             => true,
					'show_in_menu'        => true,
					'menu_position'       => 8,
					'menu_icon'           => 'dashicons-clipboard',
					'show_in_admin_bar'   => false,
					'show_in_nav_menus'   => false,
					'can_export'          => true,
					'has_archive'         => false,
					'exclude_from_search' => false,
					'publicly_queryable'  => false,
					'rewrite'             => false,
					'capability_type'     => 'manage_options',
					'capabilities'        => [
						'create_posts' => 'do_not_allow',
						'delete_posts' => 'do_not_allow',
					],
					'show_in_rest'        => false,
				];

				register_post_type( $this->get_post_type(), $args );
			}
		);
	}

	/**
	 * Returns the Invoice post type slug.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_post_type(): string {
		return self::POST_TYPE;
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
			'manage_' . $this->get_post_type() . '_posts_columns',
			function ( array $columns ): array {
				$new_columns = [];

				foreach ( $columns as $column => $label ) {
					// Customize the Date column label.
					if ( 'date' === $column ) {
						$label = __( 'Date Created', 'goodbids' );
					}

					$new_columns[ $column ] = $label;

					// Insert Custom Columns after the Title column.
					if ( 'title' === $column ) {
						$new_columns['auction']   = __( 'Auction', 'goodbids' );
						$new_columns['amount']    = __( 'Amount', 'goodbids' );
						$new_columns['stripe_id'] = __( 'Invoice ID', 'goodbids' );
						$new_columns['status']    = __( 'Status', 'goodbids' );
						$new_columns['pay']       = __( 'Pay', 'goodbids' );
					}
				}

				return $new_columns;
			}
		);

		add_action(
			'manage_' . $this->get_post_type() . '_posts_custom_column',
			function ( string $column, int $post_id ) {
				// Output the column values.
				if ( 'auction' === $column ) {
					$auction_id = get_post_meta( $post_id, self::AUCTION_ID_META_KEY, true );

					printf(
						'<a href="%s">%s</a> (<a href="%s" target="_blank">%s</a>)',
						esc_url( get_edit_post_link( $auction_id ) ),
						esc_html( get_the_title( $auction_id ) ),
						esc_url( get_permalink( $auction_id ) ),
						esc_html__( 'View', 'goodbids' )
					);
				} elseif ( 'amount' === $column ) {
					echo '$TBD';
				} elseif ( 'stripe_id' === $column ) {
					echo '#TBD';
				} elseif ( 'status' === $column ) {
					esc_html_e( 'Unpaid', 'goodbids' );
				} elseif ( 'pay' === $column ) {
					printf(
						'<a href="%s" class="button button-primary">%s</a>',
						esc_url( '#' ),
						esc_html__( 'Pay Now', 'goodbids' )
					);
				}
			},
			10,
			2
		);
	}
}
