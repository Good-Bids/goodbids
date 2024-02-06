<?php
/**
 * GoodBids Nonprofit Invoices
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Nonprofits;

use GoodBids\Utilities\Log;

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
	const INVOICE_ID_META_KEY = '_invoice_id';

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		// Disable Invoices on Main Site.
		if ( is_main_site() ) {
			return;
		}

		// Register Post Type.
		$this->register_post_type();

		// Prevent Deletion of Invoices.
		$this->disable_delete();

		// Add custom Admin Columns for Watchers.
		$this->add_admin_columns();

		// Generate Invoice on Auction Close.
		$this->maybe_generate_invoice();
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
	 * Prevent users from deleting Invoices
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function disable_delete(): void {
		add_action(
			'admin_init',
			function () {
				/**
				 * Prevent Non-Super Admins from deleting Invoices
				 *
				 * @since 1.0.0
				 *
				 * @param mixed $delete
				 * @param \WP_Post $post
				 *
				 * @return mixed
				 */
				$prevent_delete = function ( mixed $delete, \WP_Post $post ): mixed {
					if ( $this->get_post_type() !== get_post_type( $post ) ) {
						return $delete;
					}

					add_action(
						'admin_notices',
						function () {
							?>
							<div class="notice notice-error">
								<p><?php esc_html_e( 'Invoices cannot be deleted.', 'goodbids' ); ?></p>
							</div>
							<?php
						}
					);

					return false;
				};

				if ( ! is_super_admin() ) {
					add_filter( 'pre_delete_post', $prevent_delete, 10, 2 );
					add_filter( 'pre_trash_post', $prevent_delete, 10, 2 );
				}

				add_filter(
					'post_row_actions',
					function ( array $actions, $post ) {
						if ( $this->get_post_type() !== get_post_type( $post ) ) {
							return $actions;
						}

						$actions['edit'] = str_replace( __( 'Edit' ), __( 'View' ), $actions['edit'] );

						unset( $actions['inline hide-if-no-js'] );
						unset( $actions['inline'] );

						if ( is_super_admin() ) {
							return $actions;
						}

						unset( $actions['trash'] );
						unset( $actions['clone'] );

						return $actions;
					},
					10,
					2
				);
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
	 * Get an Invoice by ID
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id
	 *
	 * @return ?Invoice
	 */
	public function get_invoice( int $post_id ): ?Invoice {
		return new Invoice( $post_id );
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
						$new_columns['due_date']  = __( 'Due Date', 'goodbids' );
					}
				}

				return $new_columns;
			}
		);

		add_action(
			'manage_' . $this->get_post_type() . '_posts_custom_column',
			function ( string $column, int $post_id ) {
				$invoice = $this->get_invoice( $post_id );

				if ( ! $invoice ) {
					return;
				}

				// Output the column values.
				if ( 'auction' === $column ) {
					$auction_id = $invoice->get_auction_id();

					printf(
						'<a href="%s">%s</a> (<a href="%s" target="_blank">%s</a>)',
						esc_url( get_edit_post_link( $auction_id ) ),
						esc_html( get_the_title( $auction_id ) ),
						esc_url( get_permalink( $auction_id ) ),
						esc_html__( 'View', 'goodbids' )
					);
				} elseif ( 'amount' === $column ) {
					echo wp_kses_post( wc_price( $invoice->get_amount() ) );
				} elseif ( 'stripe_id' === $column ) {
					echo '#TBD';
				} elseif ( 'status' === $column ) {
					echo esc_html( $invoice->get_status() );
				} elseif ( 'pay' === $column ) {
					printf(
						'<a href="%s" class="button button-primary">%s</a>',
						esc_url( '#' ),
						esc_html__( 'Pay Now', 'goodbids' )
					);
				} elseif ( 'due_date' === $column ) {
					echo esc_html( $invoice->get_due_date( 'n/j/Y g:i a' ) );
				}
			},
			10,
			2
		);
	}

	/**
	 * Generate a new Invoice for an Auction when Auction closes
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function maybe_generate_invoice(): void {
		add_action(
			'goodbids_auction_close',
			function ( int $auction_id ): void {
				// Bail early if invoice already exists.
				if ( goodbids()->auctions->get_invoice_id( $auction_id ) ) {
					return;
				}

				// Generate the Invoice.
				$invoice_id = wp_insert_post(
					[
						'post_title'  => sprintf(
							// translators: %s is the Auction Title.
							__( 'Invoice for %s', 'goodbids' ),
							get_the_title( $auction_id )
						),
						'post_type'   => $this->get_post_type(),
						'post_status' => 'publish',
						'post_author' => 1,
					]
				);

				if ( ! $invoice_id ) {
					Log::error( 'Error generating invoice.', compact( 'auction_id' ) );
					return;
				}

				$invoice = $this->get_invoice( $invoice_id );

				if ( ! $invoice->init( $auction_id ) ) {
					Log::error( 'Could not initialize invoice.', compact( 'auction_id', 'invoice_id' ) );
				}
			}
		);
	}
}
