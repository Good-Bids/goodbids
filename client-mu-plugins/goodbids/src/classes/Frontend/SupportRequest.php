<?php
/**
 * Support Request Functionality
 *
 * @since 1.0.0
 *
 * @package GoodBids
 */

namespace GoodBids\Frontend;

use GoodBids\Users\Bidder;
use GoodBids\Utilities\Log;
use WP_Post;
use WP_Screen;

class SupportRequest {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const POST_TYPE = 'gb-support-request';

	/**
	 * Nonce action
	 *
	 * @since 1.0.0
	 * @var string
	 */
	const FORM_NONCE_ACTION = 'support-request-form';

	/**
	 * Form fields
	 *
	 * @since 1.0.0
	 * @var array
	 */
	private array $fields = [];

	/**
	 * Error message
	 *
	 * @since 1.0.0
	 *
	 * @var ?string
	 */
	private ?string $error = null;

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

		// Populate the Select Menus.
		$this->insert_auction_options();
		$this->insert_bid_options();
		$this->insert_reward_options();

		// Field Dependencies
		$this->modify_for_dependencies();

		// Initialize fields
		$this->init_fields();

		// Handle the Form Submission.
		$this->handle_form_submission();

		// Mark requests as read when opened in the admin.
		$this->handle_mark_as_read();

		// Display the unread count of Support Requests.
		$this->display_unread_count();
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
					'goodbids-support-request-actions',
					__( 'Info', 'goodbids' ),
					function ( WP_Post $post ): void {
						$request = new Request( $post->ID );

						if ( ! $request->is_valid() ) {
							return;
						}
						?>
						<div style="display: flex; justify-content: space-between; align-items: center;margin-top: 0.5rem;">
							<p>
								<strong><?php esc_html_e( 'Submission Date', 'goodbids' ); ?></strong><br>
								<?php
								$date = $request->get_post_data( 'post_date' );
								echo esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $date ) ) );
								?>
							</p>
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
				foreach ( $columns as $column => $label ) {
					$new_columns[ $column ] = $label;

					// Insert Custom Columns after the Title column.
					if ( 'title' === $column ) {
						$new_columns[ Request::FIELD_USER_ID ] = __( 'User', 'goodbids' );
						$new_columns[ Request::FIELD_AUCTION ] = __( 'Auction', 'goodbids' );
						$new_columns[ Request::FIELD_TYPE ]    = __( 'Type', 'goodbids' );
						$new_columns[ Request::FIELD_NATURE ]  = __( 'Nature', 'goodbids' );
					}
				}

				return $new_columns;
			}
		);

		add_action(
			'manage_' . self::POST_TYPE . '_posts_custom_column',
			function ( string $column, int $post_id ) {
				$request = new Request( $post_id );

				if ( Request::FIELD_USER_ID === $column ) {
					echo wp_kses_post( $request->get_user_html() );
					if ( ! $request->is_unread() ) {
						printf( '<style>tr#post-%s .column-title strong a { font-weight:normal !important; }</style>', esc_html( $post_id ) );
					}
				} elseif ( Request::FIELD_AUCTION === $column ) {
					echo wp_kses_post( $request->get_auction_html() );
				} elseif ( Request::FIELD_TYPE === $column ) {
					echo esc_html( $request->get_field( Request::FIELD_TYPE ) );
				} elseif ( Request::FIELD_NATURE === $column ) {
					echo esc_html( $request->get_field( Request::FIELD_NATURE ) );
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

	/**
	 * Check if submission was processed.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function submission_processed(): bool {
		return ! empty( $_GET['success'] ); // phpcs:ignore
	}

	/**
	 * Render the success message
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function render_success(): void {
		?>
		<div class="bg-gb-green-700 rounded p-4">
			<p class="text-gb-green-100">
				<?php esc_html_e( 'Your request has been submitted. We will respond as soon as we can.', 'goodbids' ); ?>
			</p>
		</div>
		<?php
	}

	/**
	 * Check for a value.
	 *
	 * @since 1.0.0
	 *
	 * @param array $items
	 * @param array $form_data
	 *
	 * @return bool
	 */
	private function has_value( array $items, array $form_data ): bool {
		foreach ( $items as $item ) {
			if ( ! empty( $item ) ) {
				continue;
			}

			if ( in_array( $form_data[ $item ], [ '0', 0 ], true ) ) {
				continue;
			}

			return false;
		}

		return true;
	}

	/**
	 * Check all required fields.
	 *
	 * @since 1.0.0
	 *
	 * @param array $form_data
	 *
	 * @return bool
	 */
	private function passed_validation( array $form_data ): bool {
		foreach ( $this->get_fields() as $key => $field ) {
			if ( empty( $field['required'] ) ) {
				continue;
			}

			if ( 'dependencies' === $field['required'] && ! empty( $field['dependencies'] ) ) {
				$required = array_keys( $field['dependencies'] );
				if ( ! $this->has_value( $required, $form_data ) ) {
					continue;
				}
			} elseif ( is_array( $field['required'] ) ) {
				if ( ! $this->has_value( $field['required'], $form_data ) ) {
					continue;
				}
			}

			if ( $this->has_value( [ $key ], $form_data ) ) {
				continue;
			}

			$this->error = sprintf(
				/* translators: %s: Field Label */
				__( 'The %s field is required.', 'goodbids' ),
				$field['label']
			);

			break;
		}

		return empty( $this->error );
	}

	/**
	 * Get the Form Fields
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_fields(): array {
		return $this->fields;
	}

	/**
	 * Get the Error
	 *
	 * @since 1.0.0
	 *
	 * @return ?string
	 */
	public function get_error(): ?string {
		return $this->error;
	}

	/**
	 * Populate the Auctions Select with the user's auctions
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function insert_auction_options(): void {
		add_filter(
			'goodbids_support_request_form_fields',
			function ( array $fields ): array {
				if ( ! is_user_logged_in() || is_admin() ) {
					return $fields;
				}

				$options  = [];
				$auctions = goodbids()->sites->get_user_participating_auctions();

				foreach ( $auctions as $auction ) {
					goodbids()->sites->swap(
						function () use ( $auction, &$options ) {
							$options[] = [
								'value' => $auction['site_id'] . '|' . $auction['auction_id'],
								'label' => get_the_title( $auction['auction_id'] ),
							];
						},
						$auction['site_id']
					);
				}

				if ( empty( $options ) ) {
					$fields[ Request::FIELD_AUCTION ]['options'] = [
						[
							'value' => 'unknown',
							'label' => __( 'No auctions found. Select this to submit your request anyway.', 'goodbids' ),
						],
					];
					return $fields;
				}

				$options = collect( $options )
					->sortBy( 'label' )
					->values()
					->all();

				$fields[ Request::FIELD_AUCTION ]['options'] = $options;

				return $fields;
			}
		);
	}

	/**
	 * Populate the Bids Select with the user's bids
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function insert_bid_options(): void {
		add_filter(
			'goodbids_support_request_form_fields',
			function ( array $fields, array $form_data ): array {
				if ( ! is_user_logged_in() || is_admin() ) {
					return $fields;
				}

				$options = [];

				if ( empty( $form_data[ Request::FIELD_AUCTION ] ) ) {
					$fields[ Request::FIELD_BID ]['options'] = [
						[
							'value' => '',
							'label' => __( 'Select an Auction first', 'goodbids' ),
						],
					];
					return $fields;
				}

				list( $site_id, $auction_id ) = array_map( 'intval', explode( '|', $form_data[ Request::FIELD_AUCTION ] ) );
				$bids = goodbids()->sites->get_user_bid_orders( get_current_user_id() );
				$bids = collect( $bids )
					->filter(
						fn ( $bid_data ) => $bid_data['site_id'] === $site_id
					)
					->filter(
						function ( $bid_data ) use ( $auction_id ) {
							return goodbids()->sites->swap(
								function () use ( $bid_data, $auction_id ) {
									return goodbids()->woocommerce->orders->get_auction_id( $bid_data['order_id'] ) === $auction_id;
								},
								$bid_data['site_id']
							);
						}
					)
					->values()
					->all();

				if ( empty( $bids ) ) {
					$fields[ Request::FIELD_BID ]['options'] = [
						[
							'value' => '',
							'label' => __( 'No bids found. Select this to submit your request anyway.', 'goodbids' ),
						],
					];
					return $fields;
				}

				foreach ( $bids as $bid_data ) {
					goodbids()->sites->swap(
						function () use ( $bid_data, &$options ) {
							$order  = wc_get_order( $bid_data['order_id'] );
							$title  = __( 'Bid Order #', 'goodbids' ) . $bid_data['order_id'];
							$title .= $bid_data['order_id'];
							$title .= ' (' . wp_strip_all_tags( wc_price( $order->get_total( 'edit' ) ) ) . ')';

							$options[] = [
								'value' => $bid_data['site_id'] . '|' . $bid_data['order_id'],
								'label' => $title,
							];
						},
						$bid_data['site_id']
					);
				}

				$fields[ Request::FIELD_BID ]['options'] = $options;

				return $fields;
			},
			10,
			2
		);
	}

	/**
	 * Populate the Rewards Select with the user's bids
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function insert_reward_options(): void {
		add_filter(
			'goodbids_support_request_form_fields',
			function ( array $fields, array $form_data ): array {
				if ( ! \is_user_logged_in() || is_admin() ) {
					return $fields;
				}

				$options = [];

				if ( empty( $form_data[ Request::FIELD_AUCTION ] ) ) {
					$fields[ Request::FIELD_REWARD ]['options'] = [
						[
							'value' => '',
							'label' => __( 'Select an Auction first', 'goodbids' ),
						],
					];
					return $fields;
				}

				list( $site_id, $auction_id ) = array_map( 'intval', explode( '|', $form_data[ Request::FIELD_AUCTION ] ) );
				$rewards = goodbids()->sites->get_user_reward_orders( get_current_user_id() );
				$rewards = collect( $rewards )
					->filter(
						fn ( $reward_data ) => $reward_data['site_id'] === $site_id
					)
					->filter(
						function ( $reward_data ) use ( $auction_id ) {
							return goodbids()->sites->swap(
								function () use ( $reward_data, $auction_id ) {
									return goodbids()->woocommerce->orders->get_auction_id( $reward_data['order_id'] ) === $auction_id;
								},
								$reward_data['site_id']
							);
						}
					)
					->values()
					->all();

				if ( empty( $rewards ) ) {
					$fields[ Request::FIELD_REWARD ]['options'] = [
						[
							'value' => 'unknown',
							'label' => __( 'No rewards found. Select this to submit your request anyway.', 'goodbids' ),
						],
					];
					return $fields;
				}

				foreach ( $rewards as $reward_data ) {
					goodbids()->sites->swap(
						function () use ( $reward_data, &$options ) {
							$order = wc_get_order( $reward_data['order_id'] );
							$title = __( 'Reward Order #', 'goodbids' ) . $reward_data['order_id'];
							foreach ( $order->get_items() as $item ) {
								$product = wc_get_product( $item['product_id'] );

								if ( $product ) {
									$title = $product->get_title();
								}
							}

							$options[] = [
								'value' => $reward_data['site_id'] . '|' . $reward_data['order_id'],
								'label' => $title,
							];
						},
						$reward_data['site_id']
					);
				}

				$fields[ Request::FIELD_REWARD ]['options'] = $options;

				return $fields;
			},
			10,
			2
		);
	}

	/**
	 * Update fields with dependencies
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function modify_for_dependencies(): void {
		add_filter(
			'goodbids_support_request_form_fields',
			function ( array $fields, array $form_data ): array {
				return $this->handle_dependencies( $fields, $form_data );
			},
			10,
			2
		);
	}

	/**
	 * Hide fields based on dependencies
	 *
	 * @since 1.0.0
	 *
	 * @param array $fields
	 * @param array $form_data
	 *
	 * @return array
	 */
	private function handle_dependencies( array $fields, array $form_data ): array {
		foreach ( $fields as $key => &$field ) {
			if ( ! empty( $field['options'] ) ) {
				$field['options'] = $this->handle_dependencies( $field['options'], $form_data );
			}

			if ( empty( $field['dependencies'] ) ) {
				continue;
			}

			$dependencies = $field['dependencies'];

			foreach ( $dependencies as $dependency_key => $dependency_value ) {
				if ( empty( $form_data[ $dependency_key ] ) ) {
					$fields[ $key ]['hidden'] = true;
					break;
				}

				if ( is_null( $dependency_value ) ) {
					continue;
				}

				if ( is_string( $dependency_value ) && $form_data[ $dependency_key ] === $dependency_value ) {
					continue;
				}

				if ( is_array( $dependency_value ) && in_array( $form_data[ $dependency_key ], $dependency_value, true ) ) {
					continue;
				}

				$fields[ $key ]['hidden'] = true;
				break;
			}
		}

		return $fields;
	}

	/**
	 * Initialize the form fields
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init_fields(): void {
		$init_fields = function () {
			$current_url = $this->get_current_url();
			$form_data   = $this->get_form_data();

			// Dynamic Dependencies for the 2 Request Fields
			$extra_deps  = [
				Request::FIELD_TYPE => null,
			];

			$type = ! empty( $form_data[ Request::FIELD_TYPE ] ) ? $form_data[ Request::FIELD_TYPE ] : null;

			if ( in_array( $type, [ Request::TYPE_BID, Request::TYPE_REWARD ], true ) ) {
				$extra_deps[ Request::FIELD_AUCTION ] = null;

				if ( $type === Request::TYPE_BID ) {
					$extra_deps[ Request::FIELD_BID ] = null;
				} elseif ( $type === Request::TYPE_REWARD ) {
					$extra_deps[ Request::FIELD_REWARD ] = null;
				}
			}

			/**
			 * Adjust the Support Request Form Fields
			 *
			 * @since 1.0.0
			 *
			 * @param array $fields
			 * @param array $form_data
			 */
			$this->fields = apply_filters(
				'goodbids_support_request_form_fields',
				[
					Request::FIELD_TYPE => [
						'type'     => 'select',
						'label'    => __( 'What do you need help with?', 'goodbids' ),
						'required' => true,
						'options'  => [
							Request::TYPE_BID     => __( 'A bid I placed', 'goodbids' ),
							Request::TYPE_REWARD  => __( 'A reward I claimed', 'goodbids' ),
							Request::TYPE_AUCTION => __( 'An auction', 'goodbids' ),
							Request::TYPE_OTHER   => __( 'Something else', 'goodbids' ),
						],
						'attr'    => [
							'hx-trigger'   => 'change',
							'hx-get'       => $current_url,
							'hx-target'    => '#gb-support-form-target',
							'hx-select'    => '#gb-support-form-target',
							'hx-indicator' => '[data-form-spinner]',
						],
					],
					Request::FIELD_AUCTION => [
						'type'    => 'select',
						'label'   => __( 'Which Auction are you referencing?', 'goodbids' ),
						'options' => [
							'Auction 1',
							'Auction 2',
							'Auction 3',
						],
						'attr'    => [
							'hx-trigger'   => 'change',
							'hx-get'       => $current_url,
							'hx-target'    => '#gb-support-form-target',
							'hx-select'    => '#gb-support-form-target',
							'hx-indicator' => '[data-form-spinner]',
						],
						'required'     => 'dependencies',
						'dependencies' => [
							Request::FIELD_TYPE => [ Request::TYPE_BID, Request::TYPE_REWARD, Request::TYPE_AUCTION ],
						],
					],
					Request::FIELD_BID => [
						'type'    => 'select',
						'label'   => __( 'Which bid are you referencing?', 'goodbids' ),
						'options' => [
							'Bid 1',
							'Bid 2',
							'Bid 3',
						],
						'required'     => 'dependencies',
						'dependencies' => [
							Request::FIELD_TYPE    => Request::TYPE_BID,
							Request::FIELD_AUCTION => null,
						],
						'attr'    => [
							'hx-trigger'   => 'change',
							'hx-get'       => $current_url,
							'hx-target'    => '#gb-support-form-target',
							'hx-select'    => '#gb-support-form-target',
							'hx-indicator' => '[data-form-spinner]',
						],
					],
					Request::FIELD_REWARD => [
						'type'    => 'select',
						'label'   => __( 'Which reward are you referencing?', 'goodbids' ),
						'options' => [
							'Reward 1',
							'Reward 2',
							'Reward 3',
						],
						'required'     => 'dependencies',
						'dependencies' => [
							Request::FIELD_TYPE    => Request::TYPE_REWARD,
							Request::FIELD_AUCTION => null,
						],
						'attr'    => [
							'hx-trigger'   => 'change',
							'hx-get'       => $current_url,
							'hx-target'    => '#gb-support-form-target',
							'hx-select'    => '#gb-support-form-target',
							'hx-indicator' => '[data-form-spinner]',
						],
					],
					Request::FIELD_NATURE => [
						'type'    => 'select',
						'label'   => __( 'What is the nature of your request?', 'goodbids' ),
						'options' => [
							[
								'label' => __( 'Report an issue', 'goodbids' ),
								'value' => __( 'Issue', 'goodbids' ),
							],
							[
								'label' => __( 'Request a refund', 'goodbids' ),
								'value' => __( 'Refund', 'goodbids' ),
								'dependencies' => [
									Request::FIELD_TYPE => Request::TYPE_BID,
								],
							],
							[
								'label' => __( 'Ask a question', 'goodbids' ),
								'value' => __( 'Question', 'goodbids' ),
							],
						],
						'required'     => 'dependencies',
						'dependencies' => $extra_deps,
					],
					Request::FIELD_REQUEST => [
						'type'         => 'textarea',
						'label'        => __( 'Please describe your request', 'goodbids' ),
						'placeholder'  => __( 'Tell us what\'s going on', 'goodbids' ),
						'required'     => 'dependencies',
						'dependencies' => $extra_deps,
					],
				],
				$form_data
			);
		};

		add_action( 'template_redirect', $init_fields );
		add_action( 'admin_init', $init_fields );
	}

	/**
	 * Get the Current URL with Query Parameters added.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	private function get_current_url(): string {
		global $wp;
		$current_url   = home_url( $wp->request );
		$form_data     = $this->get_form_data();
		$append_fields = [
			Request::FIELD_TYPE,
			Request::FIELD_AUCTION,
			Request::FIELD_BID,
			Request::FIELD_REWARD,
		];

		foreach ( $append_fields as $data_field ) {
			if ( ! empty( $form_data[ $data_field ] ) ) {
				$current_url = add_query_arg( $data_field, urlencode( $form_data[ $data_field ] ), $current_url );
			}
		}

		return $current_url;
	}

	/**
	 * Get pre-populated field values from URL.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	private function get_url_vars(): array {
		$form_data = [];
		$query_vars = [
			'type'    => Request::FIELD_TYPE,
			'auction' => Request::FIELD_AUCTION,
			'bid'     => Request::FIELD_BID,
			'reward'  => Request::FIELD_REWARD,
		];

		// Check both values for data.
		foreach ( $query_vars as $query_var => $field_key ) {
			if ( ! empty( $_GET[ $query_var ] ) ) { // phpcs:ignore
				$form_data[ $field_key ] = sanitize_text_field( $_GET[ $query_var ] ); // phpcs:ignore
			}
			if ( ! empty( $_GET[ $field_key ] ) ) { // phpcs:ignore
				$form_data[ $field_key ] = sanitize_text_field( $_GET[ $field_key ] ); // phpcs:ignore
			}
		}

		return $form_data;
	}

	/**
	 * Grab the posted form data.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_form_data(): array {
		$form_data = $this->get_url_vars();

		if ( empty( $_POST[ self::FORM_NONCE_ACTION ] ) || empty( $this->fields ) ) { // phpcs:ignore
			return $form_data;
		}

		foreach ( $this->get_fields() as $key => $field ) {
			$form_data[ $key ] = ! empty( $_POST[ $key ] ) ? sanitize_text_field( $_POST[ $key ] ) : ''; // phpcs:ignore
		}

		return $form_data;
	}

	/**
	 * Process the Form Submission
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function handle_form_submission(): void {
		add_action(
			'template_redirect',
			function () {
				if ( empty( $_POST ) ) { // phpcs:ignore
					return;
				}

				$form_data = $this->get_form_data();

				if ( ! $this->passed_validation( $form_data ) ) {
					return;
				}

				$metadata = [
					Request::FIELD_USER_ID => get_current_user_id(),
					Request::FIELD_TYPE    => $form_data[ Request::FIELD_TYPE ] ?? '',
					Request::FIELD_AUCTION => $form_data[ Request::FIELD_AUCTION ] ?? '',
					Request::FIELD_BID     => $form_data[ Request::FIELD_BID ] ?? '',
					Request::FIELD_REWARD  => $form_data[ Request::FIELD_REWARD ] ?? '',
					Request::FIELD_NATURE  => $form_data[ Request::FIELD_NATURE ] ?? '',
					Request::FIELD_REQUEST => $form_data[ Request::FIELD_REQUEST ] ?? '',
				];

				$user  = new Bidder();
				$title = sprintf(
					'%s %s %s',
					$metadata[ Request::FIELD_TYPE ],
					__( 'Request from', 'goodbids' ),
					$user->get_username()
				);

				$support_post = [
					'post_type'   => $this->get_post_type(),
					'author'      => 1,
					'post_title'  => $title,
					'post_status' => 'private',
					'meta_input'  => $metadata,
				];

				if ( ! $this->create_request( $support_post ) ) {
					return;
				}

				global $wp;
				$redirect = add_query_arg( 'success', 1, trailingslashit( home_url( $wp->request ) ) );
				wp_safe_redirect( $redirect );
				exit;
			}
		);
	}

	/**
	 * Get the Support Request form URL
	 *
	 * @since 1.0.0
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public function get_form_url( array $args = [] ): string {
		$url = trailingslashit( home_url( 'support-request' ) );

		if ( empty( $args ) ) {
			return $url;
		}

		foreach ( $args as $arg => $val ) {
			$url = add_query_arg( $arg, $val, $url );
		}

		return $url;
	}

	/**
	 * Get the number of unread support requests
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_unread_count(): int {
		$args = [
			'post_type'      => self::POST_TYPE,
			'post_status'    => 'private',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'meta_key'       => Request::READ_DATE_META_KEY,
			'meta_compare'   =>'NOT EXISTS',
		];

		$unread = new \WP_Query( $args );
		return $unread->found_posts;
	}

	/**
	 * Mark a Request as read
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function handle_mark_as_read(): void {
		add_action(
			'current_screen',
			function ( WP_Screen $screen ) {
				if ( self::POST_TYPE !== $screen->id || empty( $_GET['post'] ) ) { // phpcs:ignore
					return;
				}

				$request_id = absint( sanitize_text_field( $_GET['post'] ) ); // phpcs:ignore
				$request    = new Request( $request_id );

				if ( ! $request->is_valid() ) {
					return;
				}

				if ( $request->is_unread() ) {
					$request->mark_as_read();
				}
			}
		);
	}

	/**
	 * Display the unread count in the WP Admin menu
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function display_unread_count(): void {
		add_action(
			'admin_menu',
			function () {
				global $menu;

				$unread_count = $this->get_unread_count();

				// Loop through the menu items
				foreach ( $menu as $key => $value ) {
					// Check if the menu item is for our post type
					if ( 'edit.php?post_type=' . self::POST_TYPE !== $value[2] ) {
						continue;
					}

					// If there are unread items, add the count to the menu item title
					if ( $unread_count > 0 ) {
						$menu[ $key ][0] .= ' <span class="update-plugins count-' . $unread_count . '"><span class="plugin-count">' . esc_html( $unread_count ) . '</span></span>'; // phpcs:ignore
					}
					break;
				}
			}
		);
	}
}
