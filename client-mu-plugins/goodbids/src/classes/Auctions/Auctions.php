<?php
/**
 * Auctions Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Auctions;

use DateInterval;
use Exception;
use GoodBids\Core;
use GoodBids\Network\Sites;
use GoodBids\Plugins\WooCommerce;
use GoodBids\Utilities\Log;
use WP_Post;
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
	 * @since 1.0.0
	 * @var Wizard
	 */
	public Wizard $wizard;

	/**
	 * Initialize Auctions
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Disable Auctions on Main Site.
		if ( is_main_site() && ! Core::is_local_env() ) {
			return;
		}

		// Init Auction Wizard.
		$this->wizard = new Wizard();

		// Init Fundraising Fields class.
		new FundraisingFields();

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
		$this->cron_intervals['daily'] = [
			'interval' => DAY_IN_SECONDS,
			'name'     => 'Daily',
			'display'  => __( 'Every Day', 'goodbids' ),
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

		// Schedule a cron job to remind users to claim their rewards.
		$this->schedule_reward_claim_reminder();

		// Use cron action to start auctions.
		$this->check_for_starting_auctions();

		// Use cron action to close auctions.
		$this->check_for_closing_auctions();

		// Extend the Auction time after bids within extension window.
		$this->maybe_extend_auction_on_order_complete();

		// Tell Auctioneer about new Bid Order.
		$this->update_auctioneer_on_order_complete();

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
				( new API\End() )->register_routes();
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

				if ( empty( $handler['callback'][0] ) || ! is_object( $handler['callback'][0] ) ) {
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

		return new WP_Query( array_merge_recursive( $args, $query_args ) );
	}

	/**
	 * Get Closed Auctions where Rewards have been unclaimed.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_args
	 *
	 * @return array
	 */
	public function get_unclaimed_reward_auctions( array $query_args = [] ): array {
		$query_args = array_merge_recursive(
				[
				'meta_query' => [
					[
						'key'     => self::AUCTION_CLOSED_META_KEY,
						'value'   => '1',
						'compare' => '=',
					],
					[
						'key'     => Rewards::REDEEMED_META_KEY,
						'compare' => 'NOT EXISTS',
					],
				],
			],
			$query_args
		);

		$unclaimed = $this->get_all( $query_args );

		return $unclaimed->posts;
	}

	/**
	 * Get Auctions where the reward is unclaimed and fit within an interval window.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_unclaimed_reward_auction_emails(): array {
		$reminder_interval_days = intval( goodbids()->get_config( 'auctions.reward-claim-reminder-interval-days' ) );
		$reward_days_to_claim = intval( goodbids()->get_config( 'auctions.reward-days-to-claim' ) );

		if ( $reward_days_to_claim < $reminder_interval_days ) {
			Log::warning( 'Reminder interval is greater than allowed days to claim reward.' );
			return [];
		}

		$current_interval = $reminder_interval_days;
		$reminder_emails  = [];

		while ( $current_interval <= $reward_days_to_claim ) {
			try {
				// Use the Current Date - X days to check for Auctions that have been closed for each interval.
				$threshold_start = current_datetime()->sub( new DateInterval( 'P' . $current_interval . 'D' ) )->format( 'Y-m-d 00:00:00' );
				$threshold_end = current_datetime()->sub( new DateInterval( 'P' . $current_interval . 'D' ) )->format( 'Y-m-d 23:59:59' );
			} catch ( Exception $e ) {
				Log::error( 'Error querying reminders for unclaimed reward auctions.', [ 'error' => $e->getMessage() ] );
				return [];
			}

			$query_args = [
				'meta_query' => [
					[
						'key'     => self::AUCTION_CLOSE_META_KEY,
						'value'   => $threshold_start,
						'compare' => '>=',
					],
					[
						'key'     => self::AUCTION_CLOSE_META_KEY,
						'value'   => $threshold_end,
						'compare' => '<=',
					],
				],
			];

			$unclaimed = $this->get_unclaimed_reward_auctions( $query_args );

			if ( count( $unclaimed ) ) {
				array_push( $reminder_emails, ...$unclaimed );
			}

			if( $current_interval >= $reward_days_to_claim ) {
				break;
			}

			// Increment, but no more than the reward days to claim.
			$current_interval += $reminder_interval_days;
			if ( $current_interval > $reward_days_to_claim ) {
				$current_interval = $reward_days_to_claim;
			}
		}

		return $reminder_emails;
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

				$this->clear_transients( $auction_id );
			},
			11,
			2
		);
	}

	/**
	 * Clear Auction-related Transients
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return void
	 */
	public function clear_transients( ?int $auction_id = null ): void {
		$transients = [
			Sites::ALL_AUCTIONS_TRANSIENT,
		];

		if ( $auction_id ) {
			$transients[] = sprintf( Auction::BID_COUNT_TRANSIENT, $auction_id );
			$transients[] = sprintf( Auction::TOTAL_RAISED_TRANSIENT, $auction_id );
		}

		foreach ( $transients as $transient ) {
			delete_transient( $transient );
		}
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
	 * Schedule a cron job that runs every day to trigger unclaimed rewards reminder
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function schedule_reward_claim_reminder(): void {
		add_action(
			'init',
			function (): void {
				if ( wp_next_scheduled( Rewards::CRON_UNCLAIMED_REMINDER_HOOK ) ) {
					return;
				}

				wp_schedule_event( strtotime( current_datetime()->format( 'Y-m-d H:i:s' ) ), $this->cron_intervals['daily']['name'], Rewards::CRON_UNCLAIMED_REMINDER_HOOK );
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
						continue;
					}

					if ( $auction->trigger_start() ) {
						$starts++;
					}
				}

				$count = count( $auctions->posts );

				if ( $count !== $starts ) {
					Log::warning(
						'Not all Auctions were started',
						[
							'starts'   => $starts,
							'expected' => $count,
							'posts'    => $auctions->posts,
						]
					);
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
					$auction->trigger_close();
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
		$auction_start = current_datetime()->add( new DateInterval( 'PT1M' ) )->format( 'Y-m-d H:i:s' );
		$query_args    = [
			'meta_query' => [
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

		return $this->get_all( $query_args );
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
		$query_args   = [
			'meta_query' => [
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

		return $this->get_all( $query_args );
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

				if ( $auction->has_started() ) {
					$auction->trigger_start();
				}

				if ( $auction->has_ended() ) {
					$auction->trigger_close();
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
	 * Prevent delinquent Nonprofits from publishing Auctions.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function restrict_delinquent_sites(): void {
		add_action(
			'transition_post_status',
			function ( string $new_status, string $old_status, WP_Post $post ): void {
				if ( is_main_site() || is_super_admin() ) {
					return;
				}

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

	/**
	 * Check if Reward Product Should be hidden.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function should_hide_reward_product(): bool {
		if ( is_super_admin() ) {
			return false;
		}

		if ( function_exists( 'get_current_screen' ) ) {
			$screen = get_current_screen();
			if ( goodbids()->auctions->get_post_type() !== $screen->id ) {
				return false;
			}
		}

		$auction_id = goodbids()->auctions->get_auction_id();

		if ( ! $auction_id ) {
			return false;
		}

		if ( 'publish' !== get_post_status( $auction_id ) ) {
			return false;
		}

		return true;
	}
}
