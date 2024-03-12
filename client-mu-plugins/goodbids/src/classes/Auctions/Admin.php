<?php
/**
 * Auction Admin Adjustments
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Auctions;

use WC_Product_Variation;
use WP_Post;

/**
 * Auction Admin Class
 *
 * @since 1.0.0
 */
class Admin {

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		// Add Auction Meta Box to display details and metrics.
		$this->add_info_meta_box();

		// Add custom Admin Columns for Auctions.
		$this->add_admin_columns();

		// Restrict certain edits from Live Auctions
		$this->live_auction_restrictions();

		// Disable Auction functions once they have started.
		$this->maybe_disable_auction();
	}

	/**
	 * Add a meta box to show Auction metrics and other details.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_info_meta_box(): void {
		add_action(
			'current_screen',
			function (): void {
				$screen = get_current_screen();

				if ( goodbids()->auctions->get_post_type() !== $screen->id ) {
					return;
				}

				add_meta_box(
					'goodbids-auction-info',
					__( 'Auction Info', 'goodbids' ),
					[ $this, 'info_meta_box' ],
					$screen->id,
					'side'
				);
			}
		);
	}

	/**
	 * Display the Auction Metrics and other details.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function info_meta_box(): void {
		$auction_id = goodbids()->auctions->get_auction_id();

		// Display the Auction Details.
		goodbids()->load_view( 'admin/auctions/details.php', compact( 'auction_id' ) );

		echo '<hr style="margin-left:-1.5rem;width:calc(100% + 3rem);" />';

		// Display the Auction Metrics.
		goodbids()->load_view( 'admin/auctions/metrics.php', compact( 'auction_id' ) );
	}

	/**
	 * Insert custom metrics admin columns
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_admin_columns(): void {
		add_filter(
			'manage_' . goodbids()->auctions->get_post_type() . '_posts_columns',
			function ( array $columns ): array {
				$new_columns = [];

				foreach ( $columns as $column => $label ) {
					$new_columns[ $column ] = $label;

					// Insert Custom Columns after the Title column.
					if ( 'title' === $column ) {
						$new_columns['status']        = __( 'Status', 'goodbids' );
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
			'manage_' . goodbids()->auctions->get_post_type() . '_posts_custom_column',
			function ( string $column, int $post_id ) {
				// Columns that require a "published" status.
				$published_cols = [
					'starting_bid',
					'bid_increment',
					'total_bids',
					'total_raised',
					'last_bid',
					'current_bid',
				];

				// Bail early if Auction isn't published.
				if ( in_array( $column, $published_cols, true ) && 'publish' !== get_post_status( $post_id ) ) {
					echo '&mdash;';
					return;
				}

				$auction = goodbids()->auctions->get( $post_id );

				// Output the column values.
				if ( 'status' === $column ) {
					$status = $auction->get_status();
					$title  = $status;

					if ( Auction::STATUS_LIVE === $status ) {
						$title = __( 'Ends', 'goodbids' ) . ': ' . $auction->get_end_date_time();
					} elseif ( Auction::STATUS_UPCOMING === $status ) {
						$title = __( 'Starts', 'goodbids' ) . ': ' . $auction->get_start_date_time();
					} elseif ( Auction::STATUS_CLOSED === $status ) {
						$title = __( 'Ended', 'goodbids' ) . ': ' . $auction->get_end_date_time();
					}

					printf(
						'<span title="%s" class="goodbids-status status-%s">%s</span>',
						esc_attr( $title ),
						esc_attr( strtolower( $status ) ),
						esc_html( $status )
					);
				} elseif ( 'starting_bid' === $column ) {
					echo wp_kses_post( wc_price( $auction->calculate_starting_bid() ) );
				} elseif ( 'bid_increment' === $column ) {
					echo wp_kses_post( wc_price( $auction->get_bid_increment() ) );
				} elseif ( 'total_bids' === $column ) {
					echo esc_html( $auction->get_bid_count() );
				} elseif ( 'total_raised' === $column ) {
					echo wp_kses_post( wc_price( $auction->get_total_raised() ) );
				} elseif ( 'last_bid' === $column ) {
					$last_bid = $auction->get_last_bid();
					echo $last_bid ? wp_kses_post( wc_price( $last_bid->get_total() ) ) : '&mdash;';
				} elseif ( 'current_bid' === $column ) {
					/** @var WC_Product_Variation $bid_variation */
					$bid_variation = goodbids()->bids->get_variation( $post_id );
					echo $bid_variation ? wp_kses_post( wc_price( $bid_variation->get_price() ) ) : '';
				}
			},
			10,
			2
		);
	}

	/**
	 * Prevent edits to fields after an Auction has gone live.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function live_auction_restrictions(): void {
		add_action(
			'current_screen',
			function () {
				if ( ! $this->is_restricted() ) {
					return;
				}
				?>
				<style>
					#acf-group_6570c1fa76181 .acf-field,
					.edit-post-post-visibility__toggle,
					.edit-post-post-schedule__toggle,
					.edit-post-post-template__dropdown,
					.edit-post-post-url__dropdown {
						pointer-events: none;
						opacity: 0.5;
					}
					.editor-post-switch-to-draft,
					.editor-post-trash {
						display: none !important;
					}
				</style>
				<?php
			}
		);

		add_filter(
			'acf/load_field',
			function ( array $field ): array {
				if ( ! $this->is_restricted( $field ) ) {
					return $field;
				}

				$field['readonly'] = true;

				return $field;
			}
		);

		add_filter(
			'acf/update_value',
			function ( mixed $value, int $post_id, array $field ): mixed {
				if ( ! $this->is_restricted( $field ) ) {
					return $value;
				}

				// Restore original value.
				return get_field( $field['name'], $post_id );
			},
			10,
			3
		);
	}

	/**
	 * Check if post should be restricted from changes.
	 *
	 * @param array $field
	 *
	 * @return bool
	 */
	private function is_restricted( array $field = [] ): bool {
		if ( is_super_admin() ) {
			return false;
		}

		if ( function_exists( 'get_current_screen' ) ) {
			$screen = get_current_screen();

			if ( goodbids()->auctions->get_post_type() !== $screen->id ) {
				return false;
			}
		}

		if ( ! empty( $field ) ) {
			if ( 'tab' === $field['type'] ) {
				return false;
			}

			$disabled_fields = [
				'auction_product',
				'auction_start',
				'auction_end',
				'minutes',
				'seconds',
				'bid_extension',
				'estimated_value',
				'bid_increment',
				'starting_bid',
				'auction_goal',
				'expected_high_bid',
			];

			if ( ! in_array( $field['name'], $disabled_fields, true ) ) {
				return false;
			}
		}

		$auction = goodbids()->auctions->get();

		if ( ! $auction->get_id() ) {
			return false;
		}

		if ( ! in_array( $auction->get_meta( 'status' ), [ Auction::STATUS_LIVE, Auction::STATUS_CLOSED ], true ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Prevent users from deleting Auctions that have started
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function maybe_disable_auction(): void {
		/**
		 * @since 1.0.0
		 *
		 * @param mixed $delete
		 * @param WP_Post $post
		 *
		 * @return mixed
		 */
		$prevent_delete = function ( mixed $delete, WP_Post $post ): mixed {
			if ( is_super_admin() || goodbids()->auctions->get_post_type() !== get_post_type( $post ) ) {
				return $delete;
			}

			$auction = goodbids()->auctions->get( $post->ID );

			if ( ! in_array( $auction->get_status(), [ Auction::STATUS_LIVE, Auction::STATUS_CLOSED ] ) ) {
				return $delete;
			}

			goodbids()->utilities->display_admin_error( __( 'Auctions cannot be deleted once they have started.', 'goodbids' ), true );

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
			'wp_insert_post_data',
			function ( array $data, array $postarr, array $unsanitized_postarr, bool $update ): mixed {
				if ( is_super_admin() || empty( $data['ID'] ) || ! $update || goodbids()->auctions->get_post_type() !== $data['post_type'] ) {
					return $data;
				}

				$auction = goodbids()->auctions->get( $data['ID'] );

				if ( ! in_array( $auction->get_meta( 'status' ), [ Auction::STATUS_LIVE, Auction::STATUS_CLOSED ] ) ) {
					return $data;
				}

				$post_data       = get_post( $data['ID'], ARRAY_A );
				$restrict_fields = [
					'post_status',
					'date',
				];

				$err = false;
				foreach ( $restrict_fields as $field ) {
					if ( $postarr[ $field ] !== $data[ $field ] ) {
						$data[ $field ] = $post_data[ $field ];
						$err            = true;
					}
				}

				if ( 'publish' !== $data['post_status'] ) {
					$data['post_status'] = 'publish';
					$err                 = true;
				}

				if ( $err ) {
					goodbids()->utilities->display_admin_error( __( 'Auctions cannot be modified once they have started.', 'goodbids' ) );
				}

				return $data;
			},
			10,
			4
		);

		add_filter(
			'post_row_actions',
			function ( array $actions, $post ) {
				if ( is_super_admin() || goodbids()->auctions->get_post_type() !== get_post_type( $post ) ) {
					return $actions;
				}

				$auction = goodbids()->auctions->get( $post->ID );

				if ( is_super_admin() || ! in_array( $auction->get_status(), [ Auction::STATUS_LIVE, Auction::STATUS_CLOSED ] ) ) {
					return $actions;
				}

				unset( $actions['inline hide-if-no-js'] );
				unset( $actions['inline'] );
				unset( $actions['trash'] );

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

				$auction = goodbids()->auctions->get();
				if ( ! $auction->get_id() ) {
					return $all_caps;
				}

				if ( ! in_array( $auction->get_status(), [ Auction::STATUS_LIVE, Auction::STATUS_CLOSED ] ) ) {
					return $all_caps;
				}

				$screen = get_current_screen();

				if ( ! $screen || $screen->base !== 'post' || goodbids()->auctions->get_post_type() !== $screen->post_type ) {
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
}
