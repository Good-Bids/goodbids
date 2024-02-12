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
	 * @var ?Stripe
	 */
	public ?Stripe $stripe = null;

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		// Initialize Submodules.
		$this->stripe = new Stripe();

		// Disable Invoices on Main Site.
		if ( is_main_site() ) {
			return;
		}

		// Register Post Type.
		$this->register_post_type();

		// Prevent Deletion of Invoices.
		$this->disable_delete();

		// Disable the "Private" post state on invoices.
		$this->clear_post_state();

		// Modify the Invoice Meta Boxes.
		$this->customize_meta_boxes();

		// Make Post Title readonly.
		$this->disable_post_title_input();

		// Add custom Admin Columns for Watchers.
		$this->add_admin_columns();

		// Generate Invoice on Auction Close.
		$this->auto_generate();
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
					'edit_item'             => __( 'View Invoice', 'goodbids' ),
					'update_item'           => __( 'Update Invoice', 'goodbids' ),
					'view_item'             => __( 'Invoice Details', 'goodbids' ),
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

		add_action(
			'admin_init',
			function () use ( $prevent_delete ): void {
				if ( is_super_admin() ) {
					return;
				}

				add_filter( 'pre_delete_post', $prevent_delete, 10, 2 );
				add_filter( 'pre_trash_post', $prevent_delete, 10, 2 );
			}
		);

		add_filter(
			'post_row_actions',
			function ( array $actions, $post ) {
				if ( $this->get_post_type() !== get_post_type( $post ) ) {
					return $actions;
				}

				if ( array_key_exists( 'edit', $actions ) ) {
					$actions['edit'] = str_replace( __( 'Edit' ), __( 'View' ), $actions['edit'] );
				}

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

		add_filter(
			'user_has_cap',
			function ( array $all_caps, array $caps ): array {
				if ( is_super_admin() || ! function_exists( 'get_current_screen' ) ) {
					return $all_caps;
				}

				$screen = get_current_screen();

				if ( ! $screen || $screen->base !== 'post' || $this->get_post_type() !== $screen->post_type ) {
					return $all_caps;
				}

				if ( ! in_array( $caps['delete_post'], $caps, true )|| ! in_array( $caps['delete_posts'], $caps, true ) ) {
					return $all_caps;
				}

				unset( $all_caps['delete_post'] );
				unset( $all_caps['delete_posts'] );

				return $all_caps;
			},
			10,
			2
		);
	}

	/**
	 * Clear the Private post state for invoices.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function clear_post_state(): void {
		add_filter(
			'display_post_states',
			function ( array $post_states, \WP_Post $post ): array {
				if ( $this->get_post_type() !== get_post_type( $post ) ) {
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
					$this->get_post_type(),
					'side'
				);

				add_meta_box(
					'goodbids-invoice-details',
					__( 'Invoice Details', 'goodbids' ),
					function ( \WP_Post $post ): void {
						$invoice = $this->get_invoice( $post->ID );

						if ( ! $invoice ) {
							return;
						}

						$auction_id = $invoice->get_auction_id();
						goodbids()->load_view( 'admin/invoices/details.php', compact( 'invoice', 'auction_id' ) );
					},
					$this->get_post_type(),
					'normal',
					'high'
				);

				add_meta_box(
					'goodbids-invoice-actions',
					__( 'Actions', 'goodbids' ),
					function ( \WP_Post $post ): void {
						$invoice = $this->get_invoice( $post->ID );

						if ( ! $invoice ) {
							return;
						}
						?>
						<div style="display: flex; justify-content: space-between; align-items: center;margin-top: 0.5rem;">
							<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=' . $this->get_post_type() ) ); ?>" class="button button-secondary">
								<?php esc_html_e( 'Back to Invoices', 'goodbids' ); ?>
							</a>

							<a href="<?php echo esc_url( $invoice->get_stripe_invoice_url() ); ?>" class="button button-primary">
								<?php esc_html_e( 'Pay Now', 'goodbids' ); ?>
							</a>
						</div>
						<?php
					},
					$this->get_post_type(),
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

				if ( ! $screen || 'post' !== $screen->base || $this->get_post_type() !== $screen->post_type ) {
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
	 * Get an Invoice by ID.
	 * Pass Auction ID to initialize a new Invoice.
	 *
	 * @since 1.0.0
	 *
	 * @param int  $post_id
	 * @param ?int $auction_id
	 *
	 * @return ?Invoice
	 */
	public function get_invoice( int $post_id, ?int $auction_id = null ): ?Invoice {
		if ( null !== $auction_id && goodbids()->auctions->get_invoice_id( $auction_id ) ) {
			_doing_it_wrong( __METHOD__, 'Invoice already exists for Auction.', '1.0.0' );
			return null;
		}

		return new Invoice( $post_id, $auction_id );
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
						$new_columns['auction']     = __( 'Auction', 'goodbids' );
						$new_columns['amount']      = __( 'Amount', 'goodbids' );
						$new_columns['invoice_num'] = __( 'Invoice #', 'goodbids' );
						$new_columns['status']      = __( 'Status', 'goodbids' );
						$new_columns['pay']         = __( 'Pay', 'goodbids' );
						$new_columns['due_date']    = __( 'Due Date', 'goodbids' );
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
				} elseif ( 'invoice_num' === $column ) {
					if ( ! $invoice->get_stripe_invoice_number() ) {
						echo '&mdash;';
					}

					printf(
						'<code title="%1$s" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;display: block;">%1$s</code>',
						esc_attr( $invoice->get_stripe_invoice_number() )
					);
				} elseif ( 'status' === $column ) {
					echo esc_html( $invoice->get_status() );
				} elseif ( 'pay' === $column ) {
					printf(
						'<a href="%s" class="button button-primary" target="_blank" rel="noopener noreferrer">%s</a>',
						esc_url( $invoice->get_stripe_invoice_url() ),
						esc_html__( 'Pay Now', 'goodbids' )
					);
				} elseif ( 'due_date' === $column ) {
					echo esc_html( $invoice->get_due_date( 'n/j/Y' ) );
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
	private function auto_generate(): void {
		add_action(
			'goodbids_auction_close',
			function ( int $auction_id ): void {
				$this->generate( $auction_id );
			}
		);
	}

	/**
	 * Generate the invoice.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return void
	 */
	public function generate( int $auction_id ): void {
		// Bail early if invoice already exists.
		if ( goodbids()->auctions->get_invoice_id( $auction_id ) ) {
			Log::warning( 'Invoice already exists for Auction.', compact( 'auction_id' ) );
			return;
		}

		if ( ! goodbids()->auctions->get_total_raised( $auction_id ) ) {
			Log::warning( 'No funds were raised for Auction.', compact( 'auction_id' ) );
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
				'post_status' => 'private',
				'post_author' => 1,
			]
		);

		if ( is_wp_error( $invoice_id ) ) {
			Log::error( 'Error generating invoice: ' . $invoice_id->get_error_message(), compact( 'auction_id' ) );
			return;
		}

		if ( ! $invoice_id ) {
			Log::error( 'Unknown error generating invoice.', compact( 'auction_id' ) );
			return;
		}

		// This initializes the invoice.
		$invoice = $this->get_invoice( $invoice_id, $auction_id );

		if ( ! $invoice ) {
			Log::error( 'Could not initialize invoice.', compact( 'auction_id', 'invoice_id' ) );
			return;
		}

		$this->stripe->create_invoice( $invoice );

		if ( ! $invoice->get_stripe_invoice_id() ) {
			Log::error( 'There was a problem creating the Stripe Invoice.', compact( 'auction_id', 'invoice' ) );
		}
	}
}
