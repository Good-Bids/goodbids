<?php
/**
 * Auctions Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Auctions;

use GoodBids\Plugins\WooCommerce;
use GoodBids\Utilities\Log;
use WC_Product_Variation;
use WP_Query;
use WP_REST_Response;

/**
 * Class for Auctions
 *
 * @since 1.0.0
 */
class Auctions {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const POST_TYPE = 'gb-auction';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const ARCHIVE_SLUG = 'auctions';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const SINGULAR_SLUG = 'auction';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const BID_COUNT_TRANSIENT = 'gb:bid-count:%d';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const TOTAL_RAISED_TRANSIENT = 'gb:total-raised:%d';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const PRODUCT_AUCTION_META_KEY = '_gb_auction_id';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const CRON_AUCTION_START_HOOK = 'goodbids_auction_start_event';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const CRON_AUCTION_CLOSE_HOOK = 'goodbids_auction_close_event';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const AUCTION_STARTED_META_KEY = '_auction_started';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const AUCTION_CLOSED_META_KEY = '_auction_closed';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const AUCTION_CLOSE_META_KEY = '_auction_close';

	/**
	 * @since 1.0.0
	 * @var array
	 */
	public array $cron_intervals = [];

	/**
	 * Initialize Auctions
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Disable Auctions on Main Site.
		if ( is_main_site() ) {
			return;
		}

		// Set up Cron Schedules.
		$this->cron_intervals['1min']  = [
			'interval' => MINUTE_IN_SECONDS,
			'name'     => '1min',
			'display'  => __( 'Once Every Minute', 'goodbids' ),
		];
		$this->cron_intervals['30sec'] = [
			'interval' => 30,
			'name'     => '30sec',
			'display'  => __( 'Every 30 Seconds', 'goodbids' ),
		];

		// Register Post Type.
		$this->register_post_type();

		// Register REST API Endpoints.
		$this->setup_api_endpoints();

		// Reduce the REST API cache time for Auction Requests.
		$this->adjust_rest_timeout();

		// Attempt to trigger events for opened/closed auctions.
		$this->maybe_trigger_events();

		// Register the Auction Single and Archive templates.
		$this->set_templates();

		// Add Auction Meta Box to display details and metrics.
		$this->add_info_meta_box();

		// Add custom Admin Columns for Auctions.
		$this->add_admin_columns();

		// Configure some values for new Auction posts.
		$this->new_auction_post_init();

		// Clear metric transients on new Bid Order.
		$this->maybe_clear_metric_transients();

		// Override End Date/Time.
		$this->override_end_date_time();

		// Set up 1min Cron Job Schedule.
		$this->add_one_min_cron_schedule();

		// Schedule a cron job to trigger the start of auctions.
		$this->schedule_auction_start_cron();

		// Schedule a cron job to trigger the close of auctions.
		$this->schedule_auction_close_cron();

		// Use cron action to start auctions.
		$this->check_for_starting_auctions();

		// Use cron action to close auctions.
		$this->check_for_closing_auctions();

		// Extend the Auction time after bids within extension window.
		$this->maybe_extend_auction_on_order_complete();

		// Tell Auctioneer about new Bid Order.
		$this->update_auctioneer_on_order_complete();

		// Allow admins to force update the close date to the Auction End Date.
		$this->force_update_close_date();

		// Restrict operations when a Nonprofit is delinquent.
		$this->restrict_delinquent_sites();
	}

	/**
	 * Register the Auctions post type
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
					'name'                  => _x( 'Auctions', 'Post Type General Name', 'goodbids' ),
					'singular_name'         => _x( 'Auction', 'Post Type Singular Name', 'goodbids' ),
					'menu_name'             => __( 'Auctions', 'goodbids' ),
					'name_admin_bar'        => __( 'Auction', 'goodbids' ),
					'archives'              => __( 'Auction Archives', 'goodbids' ),
					'attributes'            => __( 'Auction Attributes', 'goodbids' ),
					'parent_item_colon'     => __( 'Parent Auction:', 'goodbids' ),
					'all_items'             => __( 'All Auctions', 'goodbids' ),
					'add_new_item'          => __( 'Add New Auction', 'goodbids' ),
					'add_new'               => __( 'Add New', 'goodbids' ),
					'new_item'              => __( 'New Auction', 'goodbids' ),
					'edit_item'             => __( 'Edit Auction', 'goodbids' ),
					'update_item'           => __( 'Update Auction', 'goodbids' ),
					'view_item'             => __( 'View Auction', 'goodbids' ),
					'view_items'            => __( 'View Auctions', 'goodbids' ),
					'search_items'          => __( 'Search Auctions', 'goodbids' ),
					'not_found'             => __( 'Not found', 'goodbids' ),
					'not_found_in_trash'    => __( 'Not found in Trash', 'goodbids' ),
					'featured_image'        => __( 'Featured Image', 'goodbids' ),
					'set_featured_image'    => __( 'Set featured image', 'goodbids' ),
					'remove_featured_image' => __( 'Remove featured image', 'goodbids' ),
					'use_featured_image'    => __( 'Use as featured image', 'goodbids' ),
					'insert_into_item'      => __( 'Insert into auction', 'goodbids' ),
					'uploaded_to_this_item' => __( 'Uploaded to this auction', 'goodbids' ),
					'items_list'            => __( 'Auctions list', 'goodbids' ),
					'items_list_navigation' => __( 'Auctions list navigation', 'goodbids' ),
					'filter_items_list'     => __( 'Filter auctions list', 'goodbids' ),
				];

				$rewrite = [
					'slug'       => self::SINGULAR_SLUG,
					'with_front' => false,
					'pages'      => true,
					'feeds'      => true,
				];

				$args = [
					'label'               => __( 'Auction', 'goodbids' ),
					'description'         => __( 'GoodBids Auction Custom Post Type', 'goodbids' ),
					'labels'              => $labels,
					'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments', 'revisions' ),
					'hierarchical'        => false,
					'public'              => true,
					'show_ui'             => true,
					'show_in_menu'        => true,
					'menu_position'       => 5,
					'menu_icon'           => 'dashicons-money-alt',
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
					'template'            => $this->get_block_template(),
				];

				register_post_type( $this->get_post_type(), $args );
			}
		);
	}

	/**
	 * Register Auction REST API Endpoints
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function setup_api_endpoints(): void {
		add_action(
			'rest_api_init',
			function () {
				( new API\Details() )->register_routes();
				( new API\User() )->register_routes();
			}
		);
	}

	/**
	 * Use a shorter timeout for Auction REST API requests.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function adjust_rest_timeout(): void {
		add_filter(
			'wpcom_vip_rest_read_response_ttl',
			function ( int $ttl, WP_REST_Response $response ): int {
				$handler = $response->get_matched_handler();

				if ( empty( $handler['callback'][0] ) || ! is_object(  $handler['callback'][0] ) ) {
					return $ttl;
				}

				$class = get_class( $handler['callback'][0] );

				if ( ! str_starts_with( $class, __NAMESPACE__ ) ) {
					return $ttl;
				}

				// Cache for 10 seconds.
				return intval( goodbids()->get_config( 'auctions.rest-api.cache-timeout' ) );
			},
			10,
			2
		);
	}

	/**
	 * Get instance of an Auction
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return Auction
	 */
	public function get( ?int $auction_id = null ): Auction {
		if ( is_null( $auction_id ) ) {
			$auction_id = $this->get_auction_id();
		}

		return new Auction( $auction_id );
	}

	/**
	 * Returns a WP_Query object for all auctions
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_args
	 *
	 * @return WP_Query
	 */
	public function get_all( array $query_args = [] ): WP_Query {
		$args = [
			'post_type'      => $this->get_post_type(),
			'post_status'    => [ 'publish' ],
			'posts_per_page' => -1,
			'fields'         => 'ids',
		];

		return new WP_Query( array_merge( $args, $query_args ) );
	}

	/**
	 * Get the default template for Auctions.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	private function get_block_template(): array {
		return apply_filters(
			'goodbids_auction_block_template',
			[
				[
					'core/pattern',
					[
						'slug' => 'goodbids/template-auction',
					],
				],
			]
		);
	}

	/**
	 * Specify templates used for Auctions with Nice names.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function set_templates(): void {
		add_filter(
			'default_template_types',
			function ( $template_types ): array {
				$template_types[ 'single-' . $this->get_post_type() ]  = array(
					'title'       => _x( 'Single Auction', 'Template Name', 'goodbids' ),
					'description' => __( 'Displays a single Auction post.', 'goodbids' ),
				);
				$template_types[ 'archive-' . $this->get_post_type() ] = array(
					'title'       => _x( 'Archive: Auction', 'Template Name', 'goodbids' ),
					'description' => __( 'Displays a the Auctions Archive.', 'goodbids' ),
				);

				return $template_types;
			}
		);
	}

	/**
	 * Returns the Auction post type slug.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_post_type(): string {
		return self::POST_TYPE;
	}

	/**
	 * Returns the current Auction post ID.
	 *
	 * @since 1.0.0
	 *
	 * @return ?int
	 */
	public function get_auction_id(): ?int {
		if ( ! did_action( 'init' ) ) {
			_doing_it_wrong( __METHOD__, 'Method should not be called before the init hook.', '1.0.0' );
		}

		$auction_id = is_singular( $this->get_post_type() ) ? get_queried_object_id() : get_the_ID();

		if ( ! $auction_id && is_admin() && ! empty( $_GET['post'] ) ) { // phpcs:ignore
			$auction_id = intval( sanitize_text_field( $_GET['post'] ) ); // phpcs:ignore
		}

		if ( $this->get_post_type() !== get_post_type( $auction_id ) ) {
			return null;
		}

		return $auction_id;
	}

	/**
	 * Get Bid Order IDs for an Auction
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 * @param int  $limit
	 * @param ?int $user_id
	 *
	 * @return int[]
	 */
	public function get_bid_order_ids( ?int $auction_id = null, int $limit = -1, ?int $user_id = null ): array {
		if ( null === $auction_id ) {
			$auction_id = $this->get_auction_id();
		}

		$args = [
			'limit'      => $limit,
			'status'     => [ 'processing', 'completed' ],
			'return'     => 'ids',
			'orderby'    => 'date',
			'order'      => 'DESC',
			'meta_query' => [
				[
					'key'     => WooCommerce::TYPE_META_KEY,
					'compare' => '=',
					'value'   => Bids::ITEM_TYPE,
				],
			],
		];

		if ( $user_id ) {
			$args['customer_id'] = $user_id;
		}

		if ( $auction_id ) {
			$args['meta_query'][] = [
				'key'     => WooCommerce::AUCTION_META_KEY,
				'compare' => '=',
				'value'   => $auction_id,
			];
		}

		return wc_get_orders( $args );
	}

	/**
	 * Use Auction close date/time when asked for Auction End Date/Time.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function override_end_date_time(): void {
		add_filter(
			'goodbids_auction_setting',
			function ( $value, $meta_key, $auction_id ) {
				if ( 'auction_end' !== $meta_key ) {
					return $value;
				}

				$close = get_post_meta( $auction_id, self::AUCTION_CLOSE_META_KEY, true );

				if ( $close ) {
					// Always use the latest date value.
					if ( $close > $value ) {
						$value = $close;
					} else {
						update_post_meta( $auction_id, self::AUCTION_CLOSE_META_KEY, $value );
					}
				} elseif ( $value ) {
					// Initialize the close date.
					update_post_meta( $auction_id, self::AUCTION_CLOSE_META_KEY, $value );
				}

				return $value;
			},
			10,
			3
		);
	}

	/**
	 * Update Auction with some initial data when created.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function new_auction_post_init(): void {
		add_action(
			'wp_after_insert_post',
			function ( $post_id ): void {
				// Bail if this is a revision.
				if ( wp_is_post_revision( $post_id ) || 'publish' !== get_post_status( $post_id ) ) {
					return;
				}

				// Bail if not an Auction.
				if ( $this->get_post_type() !== get_post_type( $post_id ) ) {
					return;
				}

				// Set initial values for easier querying.
				update_post_meta( $post_id, self::AUCTION_STARTED_META_KEY, 0 );
				update_post_meta( $post_id, self::AUCTION_CLOSED_META_KEY, 0 );
			},
			12
		);
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

				if ( $this->get_post_type() !== $screen->id ) {
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
	 * Clear Metric Transients on new Bid Order.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function maybe_clear_metric_transients(): void {
		add_action(
			'goodbids_order_payment_complete',
			function ( int $order_id, int $auction_id ) {
				// Don't clear if this isn't a Bid order.
				if ( ! goodbids()->woocommerce->orders->is_bid_order( $order_id ) ) {
					return;
				}

				$transients = [
					sprintf( self::BID_COUNT_TRANSIENT, $auction_id ),
					sprintf( self::TOTAL_RAISED_TRANSIENT, $auction_id ),
				];

				foreach ( $transients as $transient ) {
					delete_transient( $transient );
				}
			},
			11,
			2
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
		$auction_id = $this->get_auction_id();

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
			'manage_' . $this->get_post_type() . '_posts_columns',
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
			'manage_' . $this->get_post_type() . '_posts_custom_column',
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
	 * Add a 1min Cron Job Schedule.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_one_min_cron_schedule(): void {
		add_filter(
			'cron_schedules', // phpcs:ignore
			function ( array $schedules ): array {
				foreach ( $this->cron_intervals as $id => $props ) {
					// If one is already set, confirm it matches our schedule.
					if ( ! empty( $schedules[ $props['name'] ] ) ) {
						if ( MINUTE_IN_SECONDS === $schedules[ $props['name'] ] ) {
							continue;
						}

						$this->cron_intervals[ $id ]['name'] .= '_goodbids';
					}

					// Adds every minute cron schedule.
					$schedules[ $this->cron_intervals[ $id ]['name'] ] = [
						'interval' => $this->cron_intervals[ $id ]['interval'],
						'display'  => $this->cron_intervals[ $id ]['display'],
					];
				}

				return $schedules;
			}
		);
	}

	/**
	 * Schedule a cron job that runs every minute to trigger auctions to start.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function schedule_auction_start_cron(): void {
		add_action(
			'init',
			function (): void {
				if ( wp_next_scheduled( self::CRON_AUCTION_START_HOOK ) ) {
					return;
				}

				wp_schedule_event( strtotime( current_datetime()->format( 'Y-m-d H:i:s' ) ), $this->cron_intervals['1min']['name'], self::CRON_AUCTION_START_HOOK );
			}
		);
	}

	/**
	 * Schedule a cron job that runs every half minute to trigger auctions to close.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function schedule_auction_close_cron(): void {
		add_action(
			'init',
			function (): void {
				if ( wp_next_scheduled( self::CRON_AUCTION_CLOSE_HOOK ) ) {
					return;
				}

				wp_schedule_event( strtotime( current_datetime()->format( 'Y-m-d H:i:s' ) ), $this->cron_intervals['30sec']['name'], self::CRON_AUCTION_CLOSE_HOOK );
			}
		);
	}

	/**
	 * Check for Auctions that are starting during cron hook.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function check_for_starting_auctions(): void {
		add_action(
			self::CRON_AUCTION_START_HOOK,
			function (): void {
				$auctions = $this->get_starting_auctions();
				$starts   = 0;

				if ( ! $auctions->have_posts() ) {
					return;
				}

				foreach ( $auctions->posts as $auction_id ) {
					$auction = goodbids()->auctions->get( $auction_id );
					// Skip START Action on Auctions that have ended.
					if ( $auction->has_ended() ) {
						Log::debug( 'Auction not started because it has already ended', compact( 'auction_id' ) );
						continue;
					}

					if ( $auction->trigger_start() ) {
						$starts++;
					}
				}

				$count = count( $auctions->posts );

				if ( $count !== $starts ) {
					Log::warning( 'Not all Auctions were started', [ 'starts' => $starts, 'expected' => $count, 'posts' => $auctions->posts ] );
				}
			}
		);
	}

	/**
	 * Check for Auctions that have closed during cron hook.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function check_for_closing_auctions(): void {
		add_action(
			self::CRON_AUCTION_CLOSE_HOOK,
			function (): void {
				$auctions = $this->get_closing_auctions();

				if ( ! $auctions->have_posts() ) {
					return;
				}

				foreach ( $auctions->posts as $auction_id ) {
					$auction = goodbids()->auctions->get( $auction_id );
					if ( $auction->trigger_close() ) {
						// Update the Auction meta to indicate it has closed.
						update_post_meta( $auction_id, self::AUCTION_CLOSED_META_KEY, 1 );
					}
				}
			}
		);
	}

	/**
	 * Get Auctions that are starting.
	 *
	 * @since 1.0.0
	 *
	 * @return WP_Query
	 */
	private function get_starting_auctions(): WP_Query {
		// Use the current time + 1min to get Auctions about to start.
		$auction_start = current_datetime()->add( new \DateInterval( 'PT1M' ) )->format( 'Y-m-d H:i:s' );

		$args = [
			'post_type'      => $this->get_post_type(),
			'post_status'    => [ 'publish' ],
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'meta_query'     => [
				[
					'key'     => 'auction_start',
					'value'   => $auction_start,
					'compare' => '<=',
				],
				[
					'key'   => self::AUCTION_STARTED_META_KEY,
					'value' => 0,
				],
			],
		];

		return new WP_Query( $args );
	}

	/**
	 * Get Auctions that are closing.
	 *
	 * @since 1.0.0
	 *
	 * @return WP_Query
	 */
	private function get_closing_auctions(): WP_Query {
		$current_time = current_datetime()->format( 'Y-m-d H:i:s' );

		$args = [
			'post_type'      => $this->get_post_type(),
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'return'         => 'ids',
			'meta_query'     => [
				[
					'key'     => self::AUCTION_CLOSE_META_KEY,
					'value'   => $current_time,
					'compare' => '<=',
				],
				[
					'key'   => self::AUCTION_CLOSED_META_KEY,
					'value' => 0,
				],
			],
		];

		return new WP_Query( $args );
	}

	/**
	 * Additional attempt to trigger Auction events.
	 * Useful when Cron Jobs are slow or not firing.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function maybe_trigger_events(): void {
		add_action(
			'template_redirect',
			function (): void {
				$auction_id = $this->get_auction_id();

				if ( ! $auction_id ) {
					return;
				}

				$auction = goodbids()->auctions->get( $auction_id );

				// TODO: Move to background process.

				if ( $auction->has_started() && ! $auction->start_triggered() ) {
					$auction->trigger_start();
				}

				if ( $auction->has_ended() ) {
					if ( ! $auction->end_triggered() ) {
						$auction->trigger_close();
					}

					if ( ! $auction->get_invoice_id() ) {
						goodbids()->invoices->generate( $auction->get_id() );
					}
				}
			}
		);
	}

	/**
	 * Extend the Auction Close DateTime when a Bid is placed within the extension window.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function maybe_extend_auction_on_order_complete(): void {
		add_action(
			'goodbids_order_payment_complete',
			function ( int $order_id, int $auction_id ) {
				// Bail if this isn't a Bid order.
				if ( ! goodbids()->woocommerce->orders->is_bid_order( $order_id ) ) {
					return;
				}

				$auction = goodbids()->auctions->get( $auction_id );

				if ( ! $auction->is_extension_window() || $auction->has_ended() ) {
					return;
				}

				if ( ! $auction->extend() ) {
					Log::error( 'There was a problem extending the Auction', compact( 'auction_id' ) );
				}
			},
			10,
			2
		);
	}

	/**
	 * Update the Auctioneer when a Bid is placed.
	 * Priority must be AFTER Bid Amount has been increased.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function update_auctioneer_on_order_complete(): void {
		add_action(
			'goodbids_order_payment_complete',
			function ( int $order_id, int $auction_id ) {
				// Bail if this isn't a Bid order.
				if ( ! goodbids()->woocommerce->orders->is_bid_order( $order_id ) ) {
					return;
				}

				goodbids()->auctioneer->auctions->update( $auction_id );
			},
			50,
			2
		);
	}

	/**
	 * Admin AJAX Action from the Auction Edit screen to force update the Auction Close Date/Time.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function force_update_close_date(): void {
		add_action(
			'wp_ajax_goodbids_force_auction_close_date',
			function () {
				if ( empty( $_REQUEST['gb_nonce'] ) ) {
					wp_send_json_error( __( 'Missing nonce.', 'goodbids' ) );
				}

				if ( ! wp_verify_nonce( sanitize_text_field( $_REQUEST['gb_nonce'] ), 'gb-force-update-close-date' ) ) {
					wp_send_json_error( __( 'Invalid nonce.', 'goodbids' ) );
				}

				$auction_id = isset( $_POST['auction_id'] ) ? intval( $_POST['auction_id'] ) : 0;

				if ( ! $auction_id ) {
					wp_send_json_error( __( 'Invalid Auction ID.', 'goodbids' ) );
				}

				// Get raw End Date/Time, do not use get_setting().
				$end_date = get_field( 'auction_end', $auction_id );
				update_post_meta( $auction_id, self::AUCTION_CLOSE_META_KEY, $end_date );

				wp_send_json_success(
					[
						'closeDate' => goodbids()->utilities->format_date_time( $end_date, 'n/j/Y g:i:s a' ),
					]
				);
			}
		);
	}

	/**
	 * Prevent delinquent Nonprofits from publishing Auctions.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function restrict_delinquent_sites(): void {
		add_action(
			'transition_post_status',
			function ( string $new_status, string $old_status, \WP_Post $post ): void {
				if ( 'publish' !== $new_status ) {
					return;
				}

				if ( $this->get_post_type() !== get_post_type( $post ) ) {
					return;
				}

				if ( ! goodbids()->invoices->has_overdue_invoices() ) {
					return;
				}

				if ( 'publish' === $old_status ) {
					$old_status = 'draft';
				}

				// Revert post status to previous state.
				wp_update_post(
					[
						'ID'          => $post->ID,
						'post_status' => $old_status,
					]
				);
			},
			10,
			3
		);
	}
}
