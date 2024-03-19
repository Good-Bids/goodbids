<?php
/**
 * GoodBids Nonprofit Invoices
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Nonprofits;

use DateTimeZone;
use GoodBids\Core;
use GoodBids\Utilities\Log;
use WP_Post;
use WP_Query;

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
	 * @var string
	 */
	const TAX_INVOICE_ID_META_KEY = '_tax_invoice_id';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const TYPE_META_KEY = '_invoice_type';

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
		if ( is_main_site() && ! Core::is_local_env() ) {
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

		// Disable Bulk Actions.
		$this->disable_bulk_actions();

		// Add custom Admin Columns.
		$this->add_admin_columns();

		// Generate Invoice on Auction Close.
		$this->auto_generate();

		// Generate an Invoice for tax on Rewards if applicable.
		$this->maybe_generate_reward_tax_invoice();

		// Add an Admin Notice when Nonprofit is delinquent.
		$this->alert_when_delinquent();
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
						'read_post'          => 'manage_options',
						'read_private_posts' => 'manage_options',
						'create_post'        => 'do_not_allow',
						'publish_posts'      => 'do_not_allow',
						'create_posts'       => 'do_not_allow',
						'edit_post'          => 'manage_options',
						'edit_posts'         => 'manage_options',
						'edit_others_posts'  => 'manage_options',
						'delete_post'        => 'do_not_allow',
						'delete_posts'       => 'do_not_allow',
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
		 * Prevent anyone from deleting Invoices
		 *
		 * @since 1.0.0
		 *
		 * @param mixed $delete
		 * @param WP_Post $post
		 *
		 * @return mixed
		 */
		$prevent_delete = function ( mixed $delete, WP_Post $post ): mixed {
			if ( $this->get_post_type() !== get_post_type( $post ) ) {
				return $delete;
			}

			goodbids()->utilities->display_admin_error( __( 'Invoices cannot be deleted.', 'goodbids' ), true );

			return false;
		};

		add_action(
			'admin_init',
			function () use ( $prevent_delete ): void {
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
				if ( ! function_exists( 'get_current_screen' ) ) {
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
			function ( array $post_states, WP_Post $post ): array {
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
					function ( WP_Post $post ): void {
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
					function ( WP_Post $post ): void {
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
	 * Disable Edit Bulk Action
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function disable_bulk_actions(): void {
		add_filter(
			'bulk_actions-edit-' . $this->get_post_type(),
			function ( array $actions ): array {
				unset( $actions[ 'edit' ] );
				return $actions;
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
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id
	 *
	 * @return Invoice
	 */
	public function get_invoice( int $post_id ): Invoice {
		return new Invoice( $post_id );
	}

	/**
	 * Get a Tax Invoice by ID.
	 *
	 * @since 1.0.0
	 *
	 * @param int  $post_id
	 *
	 * @return ?TaxInvoice
	 */
	public function get_tax_invoice( int $post_id ): ?TaxInvoice {
		return new TaxInvoice( $post_id );
	}

	/**
	 * Get All Invoice IDs
	 *
	 * @since 1.0.0
	 *
	 * @param array $args
	 *
	 * @return WP_Query
	 */
	public function get_all_ids( array $args = [] ): WP_Query {
		$query_args = array_merge(
			[
				'post_type'      => $this->get_post_type(),
				'posts_per_page' => -1,
				'post_status'    => [ 'publish', 'private' ],
				'order'          => 'ASC',
				'orderby'        => 'date',
				'fields'         => 'ids',
			],
			$args
		);
		return new WP_Query( $query_args );
	}

	/**
	 * Get All Overdue Invoices
	 *
	 * @since 1.0.0
	 *
	 * @return WP_Query
	 */
	public function get_overdue_invoices(): WP_Query {
		return $this->get_all_ids(
		[
				'meta_query' => [
					[
						'key'     => Invoice::DUE_DATE_META_KEY,
						'value'   => current_datetime()->setTimezone( new DateTimeZone( 'GMT' ) )->format( 'Y-m-d 23:59:59' ),
						'compare' => '<',
						'type'    => 'DATE',
					],
				],
			]
		);
	}

	/**
	 * Check if there are any overdue invoices.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function has_overdue_invoices(): bool {
		$invoices = $this->get_overdue_invoices();
		return $invoices->have_posts();
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
				unset( $columns['cb'] );

				foreach ( $columns as $column => $label ) {
					$new_columns[ $column ] = $label;

					// Insert Custom Columns after the Title column.
					if ( 'title' === $column ) {
						$new_columns['auction']     = __( 'Auction', 'goodbids' );
						$new_columns['amount']      = __( 'Amount', 'goodbids' );
						$new_columns['invoice_num'] = __( 'Invoice #', 'goodbids' );
						$new_columns['type']        = __( 'Type', 'goodbids' );
						$new_columns['status']      = __( 'Status', 'goodbids' );
						$new_columns['due_date']    = __( 'Due Date', 'goodbids' );
					}
				}

				// Insert Payment Column last.
				$new_columns['payment'] = __( 'Payment', 'goodbids' );

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
						'<code title="%s" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display:block;">%s</code>',
						esc_attr( $invoice->get_stripe_invoice_number() ),
						esc_html( $invoice->get_stripe_invoice_number() )
					);
				} elseif ( 'type' === $column ) {
					echo esc_html( $invoice->get_type() );
				} elseif ( 'status' === $column ) {
					echo esc_html( $invoice->get_status() );
				} elseif ( 'payment' === $column ) {
					if ( $invoice->is_paid() ) {
						echo esc_html__( 'Paid on', 'goodbids' ) . ' ';
						echo esc_html( $invoice->get_payment_date() );
					} else {
						printf(
							'<a href="%s" class="button button-primary" target="_blank" rel="noopener noreferrer">%s</a>',
							esc_url( $invoice->get_stripe_invoice_url() ),
							esc_html__( 'Pay Now', 'goodbids' )
						);
					}
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
			'goodbids_auction_end',
			fn ( int $auction_id ) => $this->generate( $auction_id ),
			50
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
		if ( ! $this->is_valid_auction( $auction_id ) ) {
			return;
		}

		$title = sprintf(
			// translators: %s is the Auction Title.
			__( 'Invoice for %s', 'goodbids' ),
			get_the_title( $auction_id )
		);
		$invoice_id = $this->create_invoice_post( $title );

		if ( ! $invoice_id ) {
			return;
		}

		// This initializes the invoice.
		$invoice = $this->get_invoice( $invoice_id );
		$invoice->init( $auction_id );

		if ( ! $invoice->is_valid() ) {
			Log::error( 'Could not initialize invoice.', compact( 'auction_id', 'invoice_id' ) );
			return;
		}

		// Set the initial invoice values.
		$invoice->use_default_values();

		// Create the invoice in Stripe.
		$this->stripe->create_invoice( $invoice );

		if ( ! $invoice->get_stripe_invoice_id() ) {
			Log::error( 'There was a problem creating the Stripe Invoice.', compact( 'auction_id', 'invoice' ) );
		}
	}

	/**
	 * Check if using a valid auction
	 *
	 * @since 1.0.0
	 *
	 * @param int  $auction_id
	 * @param bool $existing_check
	 *
	 * @return bool
	 */
	public function is_valid_auction( int $auction_id, bool $existing_check = true ): bool {
		if ( 'publish' !== get_post_status( $auction_id ) ) {
			Log::warning( 'Auction not published yet.', compact( 'auction_id' ) );
			return false;
		}

		$auction = goodbids()->auctions->get( $auction_id );

		// Bail early if invoice already exists.
		if ( $existing_check && $auction->get_invoice_id() ) {
			Log::warning( 'Invoice already exists for Auction.', compact( 'auction_id' ) );
			return false;
		}

		if ( ! $auction->get_total_raised() ) {
			Log::warning( 'No funds were raised for Auction.', compact( 'auction_id' ) );
			return false;
		}

		return true;
	}

	/**
	 * Create the invoice post
	 *
	 * @since 1.0.0
	 *
	 * @param string $title
	 *
	 * @return ?int
	 */
	private function create_invoice_post( string $title ): ?int {
		$invoice_id = wp_insert_post(
			[
				'post_title'  => $title,
				'post_type'   => $this->get_post_type(),
				'post_status' => 'private',
				'post_author' => 1,
			]
		);

		if ( is_wp_error( $invoice_id ) ) {
			Log::error( 'Error generating invoice: ' . $invoice_id->get_error_message(), compact( 'title' ) );
			return null;
		}

		if ( ! $invoice_id ) {
			Log::error( 'Unknown error generating invoice.', compact( 'title' ) );
			return null;
		}

		return $invoice_id;
	}

	/**
	 * Regenerate the invoice.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $auction_id
	 * @param string $stripe_invoice_id
	 *
	 * @return void
	 */
	public function regenerate( int $auction_id, string $stripe_invoice_id ): void {
		if ( ! $this->is_valid_auction( $auction_id, false ) ) {
			return;
		}

		// Generate the Invoice.
		$title     = sprintf(
			// translators: %s is the Auction Title.
			__( 'Invoice for %s', 'goodbids' ),
			get_the_title( $auction_id )
		);
		$invoice_id = $this->create_invoice_post( $title );

		if ( ! $invoice_id ) {
			return;
		}

		// This initializes the invoice.
		$invoice = $this->get_invoice( $invoice_id );

		if ( ! $invoice->is_valid() ) {
			Log::error( 'Could not initialize invoice.', compact( 'auction_id', 'invoice_id' ) );
			return;
		}

		// Set the initial invoice values.
		$invoice->use_default_values();

		// Reset the Stripe Invoice ID.
		$invoice->set_stripe_invoice_id( $stripe_invoice_id );
	}

	/**
	 * Add an Admin Notice when Nonprofit is delinquent.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function alert_when_delinquent(): void {
		add_action(
			'admin_init',
			function (): void {
				if ( is_main_site() ) {
					return;
				}

				if ( ! $this->has_overdue_invoices() ) {
					return;
				}

				goodbids()->utilities->display_admin_error( __( 'There are currently delinquent invoices on this account. Please take action to restore full site functionality.', 'goodbids' ), false );
			}
		);
	}

	/**
	 * Generate an Invoice for tax on Rewards if applicable.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function maybe_generate_reward_tax_invoice(): void {
		add_action(
			'goodbids_reward_redeemed',
			function ( int $auction_id, int $order_id ) {
				if ( ! goodbids()->woocommerce->orders->is_reward_order( $order_id ) ) {
					return;
				}

				if ( ! goodbids()->woocommerce->orders->has_tax( $order_id ) ) {
					return;
				}

				$this->generate_tax( $auction_id, $order_id );
			},
			10,
			2
		);
	}

	/**
	 * Generate the Tax Invoice.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 * @param int $order_id
	 *
	 * @return void
	 */
	public function generate_tax( int $auction_id, int $order_id ): void {
		// Skip existing check, manually check below.
		if ( ! $this->is_valid_auction( $auction_id, false ) ) {
			return;
		}

		$auction = goodbids()->auctions->get( $auction_id );

		// Bail early if invoice already exists.
		if ( $auction->get_tax_invoice_id() ) {
			Log::warning( 'Tax Invoice already exists for Auction.', compact( 'auction_id' ) );
			return;
		}

		if ( ! goodbids()->woocommerce->orders->get_tax_amount( $order_id ) ) {
			Log::info( 'This Reward Order has no tax.', compact( 'auction_id', 'order_id' ) );
			return;
		}

		// Generate the Invoice.
		$title      = sprintf(
			// translators: %s is the Auction Title.
			__( 'Tax Invoice for %s', 'goodbids' ),
			get_the_title( $auction_id )
		);
		$invoice_id = $this->create_invoice_post( $title );

		if ( ! $invoice_id ) {
			return;
		}

		// This initializes the invoice.
		$invoice = $this->get_tax_invoice( $invoice_id );
		$invoice->init_tax( $auction_id, $order_id );

		if ( ! $invoice->is_valid() ) {
			Log::error( 'Could not initialize tax invoice.', compact( 'invoice_id', 'auction_id', 'order_id' ) );
			return;
		}

		$this->stripe->create_invoice( $invoice );

		if ( ! $invoice->get_stripe_invoice_id() ) {
			Log::error( 'There was a problem creating the Stripe Tax Invoice.', compact( 'invoice', 'auction_id', 'order_id' ) );
		}
	}

	/**
	 * Get Payment Terms in Days
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_payment_terms_days(): int {
		$payment_terms = goodbids()->get_config( 'invoices.payment-terms-days' );

		// Make sure we have a valid value.
		if ( ! $payment_terms ) {
			$payment_terms = goodbids()->get_config( 'invoices.payment-terms-days', false );
		}

		return intval( $payment_terms );
	}

	/**
	 * Invoices Validation Check.
	 * This checks Stripe for any invoices that do not exist in the database.
	 * If an invoice does not exist, it will be recreated.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function validation_check(): void {
		$stripe_invoices = $this->stripe->get_invoices();

		foreach ( $stripe_invoices as $stripe_invoice ) {
			$auction_id = intval( $stripe_invoice->metadata->gb_auction_id );
			$invoice_id = intval( $stripe_invoice->metadata->gb_invoice_id );
			$site_id    = isset( $stripe_invoice->metadata->gb_site_id ) ? intval( $stripe_invoice->metadata->gb_site_id ) : null;

			if ( ! $auction_id || ! $invoice_id || ! $site_id ) {
				continue;
			}

			goodbids()->sites->swap(
				function () use ( $auction_id, $invoice_id, $stripe_invoice ) {
					// Verify Invoice exists.
					$invoice = goodbids()->invoices->get_invoice( $invoice_id );

					if ( $invoice->is_valid() ) {
						return;
					}

					// Recreate the invoice.
					Log::info( 'Regenerating Invoice for Auction ID:' . $auction_id . ' and Stripe Invoice #' . $stripe_invoice->id );
					goodbids()->invoices->regenerate( $auction_id, $stripe_invoice->id );
				},
				$site_id
			);
		}
	}
}
