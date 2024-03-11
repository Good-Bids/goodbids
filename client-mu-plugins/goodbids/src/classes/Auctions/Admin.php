<?php
/**
 * Auction Admin Adjustments
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Auctions;

use WC_Product_Variation;

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
				if ( is_super_admin() ) {
					return;
				}

				$screen = get_current_screen();

				if ( goodbids()->auctions->get_post_type() !== $screen->id ) {
					return;
				}

				$auction = goodbids()->auctions->get();

				if ( ! $auction->get_id() ) {
					return;
				}

				if ( ! in_array( $auction->get_status(), [ Auction::STATUS_LIVE, Auction::STATUS_CLOSED ], true ) ) {
					return;
				}
				?>
				<style>
					#acf-group_6570c1fa76181 .acf-field {
						pointer-events: none;
						opacity: 0.5;
					}
				</style>
				<?php
			}
		);

		add_filter(
			'acf/load_field',
			function ( array $field ): array {
				if ( is_super_admin() ) {
					return $field;
				}

				if ( 'tab' === $field['type'] ) {
					return $field;
				}

				$disabled = [
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

				if ( ! in_array( $field['name'], $disabled, true ) ) {
					return $field;
				}

				$auction = goodbids()->auctions->get();

				if ( ! $auction->get_id() ) {
					return $field;
				}

				if ( ! in_array( $auction->get_meta( 'status' ), [ Auction::STATUS_LIVE, Auction::STATUS_CLOSED ], true ) ) {
					return $field;
				}

				$field['disabled'] = true;

				return $field;
			}
		);
	}
}
