<?php
/**
 * Single Auction Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Auctions;

use GoodBids\Nonprofits\Invoices;
use GoodBids\Plugins\WooCommerce;
use GoodBids\Utilities\Log;
use WC_Order;
use WP_User;

/**
 * Instance of an Auction
 *
 * @since 1.0.0
 */
class Auction {

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
	 * @var string
	 */
	const AUCTION_EXTENSIONS_META_KEY = '_auction_extensions';

	/**
	 * @since 1.0.0
	 */
	const STATUS_DRAFT = 'Draft';

	/**
	 * @since 1.0.0
	 */
	const STATUS_UPCOMING = 'Upcoming';

	/**
	 * @since 1.0.0
	 */
	const STATUS_LIVE = 'Live';

	/**
	 * @since 1.0.0
	 */
	const STATUS_CLOSED = 'Closed';

	/**
	 * Initialize Auctions
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Configure some values for new Auction posts.
		$this->new_auction_post_init();
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
	 * Get the Invoice ID for an Auction
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return ?int
	 */
	public function get_invoice_id( int $auction_id ): ?int {
		$invoice_id = get_post_meta( $auction_id, Invoices::INVOICE_ID_META_KEY, true );

		if ( $invoice_id && get_post_type( $invoice_id ) === goodbids()->invoices->get_post_type() ) {
			return $invoice_id;
		}

		return null;
	}

	/**
	 * Check if an auction has a Bid product.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return bool
	 */
	public function has_bid_product( int $auction_id ): bool {
		return boolval( $this->bids->get_product_id( $auction_id ) );
	}

	/**
	 * Sets the Bid product ID for an Auction.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 * @param int $bid_product_id
	 *
	 * @return void
	 */
	public function set_bid_product_id( int $auction_id, int $bid_product_id ): void {
		update_post_meta( $auction_id, Bids::AUCTION_BID_META_KEY, $bid_product_id );
		update_post_meta( $bid_product_id, self::PRODUCT_AUCTION_META_KEY, $auction_id );
	}

	/**
	 * Sets the Bid product Variation ID for an Auction.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 * @param int $bid_variation_id
	 *
	 * @return void
	 */
	public function set_bid_variation_id( int $auction_id, int $bid_variation_id ): void {
		update_post_meta( $auction_id, Bids::AUCTION_BID_VARIATION_META_KEY, $bid_variation_id );
	}

	/**
	 * Retrieves a setting for an Auction.
	 *
	 * @since 1.0.0
	 *
	 * @param string $meta_key
	 * @param ?int   $auction_id
	 *
	 * @return mixed
	 */
	public function get_setting( string $meta_key, int $auction_id = null ): mixed {
		if ( null === $auction_id ) {
			$auction_id = $this->get_auction_id();
		}

		$value = get_field( $meta_key, $auction_id );

		return apply_filters( 'goodbids_auction_setting', $value, $meta_key, $auction_id );
	}

	/**
	 * Get the Auction Reward Estimated Value.
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return int
	 */
	public function get_estimated_value( int $auction_id = null ): int {
		return intval( $this->get_setting( 'estimated_value', $auction_id ) );
	}

	/**
	 * Get the Auction Start Date/Time
	 *
	 * @since 1.0.0
	 *
	 * @param ?int   $auction_id
	 * @param string $format
	 *
	 * @return string
	 */
	public function get_start_date_time( int $auction_id = null, string $format = '' ): string {
		$start = $this->get_setting( 'auction_start', $auction_id );

		if ( ! $start ) {
			return '';
		}

		return goodbids()->utilities->format_date_time( $start, $format );
	}

	/**
	 * Get the Auction End Date/Time
	 *
	 * @since 1.0.0
	 *
	 * @param ?int   $auction_id
	 * @param string $format
	 *
	 * @return string
	 */
	public function get_end_date_time( int $auction_id = null, string $format = '' ): string {
		$end = $this->get_setting( 'auction_end', $auction_id );

		if ( ! $end ) {
			return '';
		}

		return goodbids()->utilities->format_date_time( $end, $format );
	}

	/**
	 * Check if an Auction has started.
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return bool
	 */
	public function has_started( int $auction_id = null ): bool {
		$start_date_time = $this->get_start_date_time( $auction_id );

		if ( ! $start_date_time ) {
			Log::warning( 'Auction has no start date/time.', compact( 'auction_id' ) );
			return false;
		}

		return $start_date_time < current_datetime()->format( 'Y-m-d H:i:s' );
	}

	/**
	 * Check if an Auction has ended.
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return bool
	 */
	public function has_ended( int $auction_id = null ): bool {
		if ( 'publish' !== get_post_status( $auction_id ) ) {
			return false;
		}

		$end_date_time = $this->get_end_date_time( $auction_id );

		if ( ! $end_date_time ) {
			Log::warning( 'Auction has no end date/time.', compact( 'auction_id' ) );
			return false;
		}

		return $end_date_time <= current_datetime()->format( 'Y-m-d H:i:s' );
	}

	/**
	 * Get number of auction extensions
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return int
	 */
	public function get_extensions( int $auction_id ): int {
		$extensions = get_post_meta( $auction_id, self::AUCTION_EXTENSIONS_META_KEY, true );

		if ( ! is_numeric( $extensions ) ) {
			return 0;
		}

		return intval( $extensions );
	}

	/**
	 * Check if the current Auction is in the extension window.
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return bool
	 */
	public function is_extension_window( int $auction_id = null ): bool {
		if ( $this->has_ended( $auction_id ) ) {
			return false;
		}

		// One extension = always in window.
		if ( $this->get_extensions( $auction_id ) ) {
			return true;
		}

		$end_time  = $this->get_end_date_time( $auction_id );
		$extension = $this->get_bid_extension( $auction_id );

		if ( ! $end_time || ! $extension ) {
			return false;
		}

		try {
			$end = new \DateTimeImmutable( $end_time );

			// Subtract seconds from end time to get window start.
			$window = $end->sub( new \DateInterval( 'PT' . $extension . 'S' ) );
		} catch ( \Exception $e ) {
			Log::error( $e->getMessage(), compact( 'end_time' ) );
			return false;
		}

		// Check if the current time is AFTER the start of the window.
		return current_datetime()->format( 'Y-m-d H:i:s' ) >= $window->format( 'Y-m-d H:i:s' );
	}

	/**
	 * Get the Auction Bid Extension time (in seconds)
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return ?int
	 */
	public function get_bid_extension( int $auction_id = null ): ?int {
		$bid_extension = $this->get_setting( 'bid_extension', $auction_id );

		if ( ! $bid_extension ) {
			Log::error( '[CONFIG] Unable to load Bid Extension' );
			return null;
		}

		$minutes = ! empty( $bid_extension['minutes'] ) ? intval( $bid_extension['minutes'] ) : 0;
		$seconds = ! empty( $bid_extension['seconds'] ) ? intval( $bid_extension['seconds'] ) : 0;

		return ( $minutes * MINUTE_IN_SECONDS ) + $seconds;
	}

	/**
	 * Get the Auction Bid Increment amount
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return int
	 */
	public function get_bid_increment( int $auction_id = null ): int {
		return intval( $this->get_setting( 'bid_increment', $auction_id ) );
	}

	/**
	 * Get the Auction Starting Bid amount
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return int
	 */
	public function get_starting_bid( int $auction_id = null ): int {
		return intval( $this->get_setting( 'starting_bid', $auction_id ) );
	}

	/**
	 * Calculate the Auction Starting Bid amount
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return int
	 */
	public function calculate_starting_bid( int $auction_id = null ): int {
		$starting_bid = $this->get_starting_bid( (int) $auction_id );
		if ( ! $starting_bid ) {
			$starting_bid = $this->get_bid_increment( (int) $auction_id );
		}

		return $starting_bid;
	}

	/**
	 * Get the Auction Goal Amount
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return int
	 */
	public function get_goal( int $auction_id = null ): int {
		return intval( $this->get_setting( 'auction_goal', $auction_id ) );
	}

	/**
	 * Get the Auction Expected High Bid Amount
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return int
	 */
	public function get_expected_high_bid( int $auction_id = null ): int {
		return intval( $this->get_setting( 'expected_high_bid', $auction_id ) );
	}

	/**
	 * Checks if Free Bids are allowed on Auction
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return bool
	 */
	public function are_free_bids_allowed( ?int $auction_id = null ): bool {
		$all_bids  = count( $this->get_bid_order_ids( $auction_id ) );
		$free_bids = count( $this->get_free_bid_order_ids( $auction_id ) );

		// Don't divide by zero.
		if ( ! $all_bids ) {
			return false;
		}

		// Free orders must be less than 50% of all orders.
		if ( round( $free_bids / $all_bids, 2 ) > 0.5 ) {
			return false;
		}

		return true;
	}

	/**
	 * Gets the number of Available Free Bids for an Auction
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return int
	 */
	public function get_free_bids_available( ?int $auction_id = null ): int {
		if ( null === $auction_id ) {
			$auction_id = $this->get_auction_id();
		}

		$free_bids = get_post_meta( $auction_id, Bids::FREE_BIDS_META_KEY, true );

		// Return the default value if we have no value.
		if ( ! $free_bids && 0 !== $free_bids && '0' !== $free_bids ) {
			$free_bids = goodbids()->get_config( 'auctions.default-free-bids' );
			update_post_meta( $auction_id, Bids::FREE_BIDS_META_KEY, $free_bids );
		}

		return intval( $free_bids );
	}

	/**
	 * Update the Free Bids count for an Auction
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 * @param int $free_bids
	 *
	 * @return void
	 */
	public function update_free_bids( int $auction_id, int $free_bids ): void {
		update_post_meta( $auction_id, Bids::FREE_BIDS_META_KEY, $free_bids );
	}

	/**
	 * Maybe Award a Free Bid, if available
	 *
	 * @since 1.0.0
	 *
	 * @param ?int   $auction_id
	 * @param ?int   $user_id
	 * @param string $description
	 *
	 * @return bool
	 */
	public function maybe_award_free_bid( ?int $auction_id, ?int $user_id = null, string $description = '' ): bool {
		$free_bids = $this->get_free_bids_available( $auction_id );
		if ( ! $free_bids ) {
			return false;
		}

		if ( null === $user_id ) {
			$user_id = get_current_user_id();
		}

		if ( goodbids()->users->award_free_bid( $user_id, $auction_id, $description ) ) {
			--$free_bids;
			$this->update_free_bids( $auction_id, $free_bids );
			return true;
		}

		return false;
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
	 * Get Order IDs that have been placed using a Free Bid.
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 * @param int  $limit
	 * @param ?int $user_id
	 *
	 * @return int[]
	 */
	public function get_free_bid_order_ids( ?int $auction_id = null, int $limit = -1, ?int $user_id = null ): array {
		$orders = $this->get_bid_order_ids( $auction_id, $limit, $user_id );
		$return = [];

		foreach ( $orders as $order_id ) {
			// TODO: Add Free Bid Order meta.
			if ( ! goodbids()->woocommerce->orders->is_free_bid_order( $order_id ) ) {
				continue;
			}

			$return[] = $order_id;
		}

		return $return;
	}

	/**
	 * Get Order Objects that have been placed using a Free Bid.
	 *
	 * @param ?int $auction_id
	 * @param int  $limit
	 * @param ?int $user_id
	 *
	 * @return WC_Order[]
	 */
	public function get_free_bid_orders( ?int $auction_id = null, int $limit = -1, ?int $user_id = null ): array {
		return array_map(
			fn ( $order ) => wc_get_order( $order ),
			$this->get_free_bid_order_ids( $auction_id, $limit, $user_id )
		);
	}

	/**
	 * Get Bid Order objects for an Auction
	 *
	 * @since 1.0.0
	 *
	 * @param int  $auction_id
	 * @param int  $limit
	 * @param ?int $user_id
	 *
	 * @return WC_Order[]
	 */
	public function get_bid_orders( int $auction_id, int $limit = -1, ?int $user_id = null ): array {
		return array_map(
			fn ( $order ) => wc_get_order( $order ),
			$this->get_bid_order_ids( $auction_id, $limit, $user_id )
		);
	}

	/**
	 * Get the Auction Bid Count. If a User ID is specified, the Bid Count will be filtered by that User.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return int
	 */
	public function get_bid_count( int $auction_id ): int {
		$transient = sprintf( self::BID_COUNT_TRANSIENT, $auction_id );
		$bid_count = get_transient( $transient );

		if ( $bid_count ) {
			return $bid_count;
		}

		$orders    = $this->get_bid_order_ids( $auction_id );
		$bid_count = count( $orders );

		set_transient( $transient, $bid_count, HOUR_IN_SECONDS );

		return $bid_count;
	}

	/**
	 * Get total bids for a specific User on an Auction.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 * @param int $user_id
	 *
	 * @return int
	 */
	public function get_user_bid_count( int $auction_id, int $user_id ): int {
		$orders = $this->get_bid_order_ids( $auction_id, -1, $user_id );
		return count( $orders );
	}

	/**
	 * Get the Auction Total Raised
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return float
	 */
	public function get_total_raised( int $auction_id ): float {
		$transient = sprintf( self::TOTAL_RAISED_TRANSIENT, $auction_id );
		$total     = get_transient( $transient );

		if ( $total ) {
			return $total;
		}

		$total = collect( $this->get_bid_orders( $auction_id ) )
			->sum( fn( $order ) => $order->get_total( 'edit' ) );

		set_transient( $transient, $total, HOUR_IN_SECONDS );

		return $total;
	}

	/**
	 * Get the Auction Total Donated by User
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 * @param int $user_id
	 *
	 * @return float
	 */
	public function get_user_total_donated( int $auction_id, int $user_id ): float {
		return collect( $this->get_bid_orders( $auction_id, -1, $user_id ) )
			->sum( fn( $order ) => $order->get_total( 'edit' ) );
	}

	/**
	 * Get the last bid order for an Auction.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return ?WC_Order
	 */
	public function get_last_bid( int $auction_id ): ?WC_Order {
		$orders = $this->get_bid_orders( $auction_id, 1 );

		if ( empty( $orders ) ) {
			return null;
		}

		return $orders[0];
	}

	/**
	 * Get the user that placed the last bid.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return ?WP_User
	 */
	public function get_last_bidder( int $auction_id ): ?WP_User {
		$last_bid = $this->get_last_bid( $auction_id );
		return $last_bid?->get_user();
	}

	/**
	 * Checks if the current user is the current high bidder of an Auction
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return bool
	 */
	public function is_current_user_winning( int $auction_id ): bool {
		$last_bidder = $this->get_last_bidder( $auction_id );
		return $last_bidder?->ID === get_current_user_id();
	}

	/**
	 * Get the Auction's winning bidder.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return ?WP_User
	 */
	public function get_winning_bidder( int $auction_id ): ?WP_User {
		if ( ! $this->has_ended( $auction_id ) ) {
			return null;
		}

		return $this->get_last_bidder( $auction_id );
	}

	/**
	 * Checks if the current user is the winning bidder of an Auction
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return bool
	 */
	public function is_current_user_winner( int $auction_id ): bool {
		$winning_bidder = $this->get_winning_bidder( $auction_id );
		return $winning_bidder?->ID === get_current_user_id();
	}

	/**
	 * Get the status of an Auction.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return string
	 */
	public function get_status( int $auction_id ): string {
		if ( 'publish' !== get_post_status( $auction_id ) ) {
			return self::STATUS_DRAFT;
		}

		$status = self::STATUS_UPCOMING;

		if ( $this->has_started( $auction_id ) ) {
			$status = self::STATUS_LIVE;
		}

		if ( $this->has_ended( $auction_id ) ) {
			$status = self::STATUS_CLOSED;
		}

		return $status;
	}

	/**
	 * Trigger to Node the start of an auction.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return bool
	 */
	private function trigger_auction_start( int $auction_id ): bool {
		/**
		 * @param int $auction_id
		 */
		do_action( 'goodbids_auction_start', $auction_id );

		$result = goodbids()->auctioneer->auctions->start( $auction_id );

		if ( true !== $result ) {
			return false;
		}

		// Update the Auction meta to indicate it has started.
		// This is used for the sole purposes of filtering started auctions from get_starting_auctions() method.
		update_post_meta( $auction_id, self::AUCTION_STARTED_META_KEY, 1 );

		return true;
	}

	/**
	 * Check if the Auction Start event has been triggered.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return bool
	 */
	private function start_triggered( int $auction_id ): bool {
		return boolval( get_post_meta( $auction_id, self::AUCTION_STARTED_META_KEY, true ) );
	}

	/**
	 * Check if the Auction End event has been triggered.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return bool
	 */
	private function end_triggered( int $auction_id ): bool {
		return boolval( get_post_meta( $auction_id, self::AUCTION_CLOSE_META_KEY, true ) );
	}

	/**
	 * Extend the auction
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return bool
	 */
	private function extend_auction( int $auction_id ): bool {
		if ( ! $this->is_extension_window( $auction_id ) ) {
			return false;
		}

		$extension = $this->get_bid_extension( $auction_id );

		// Bail early if missing extension value.
		if ( ! $extension ) {
			Log::error( 'Missing Auction Bid Extension', compact( 'auction_id' ) );
			return false;
		}

		try {
			$close      = current_datetime()->add( new \DateInterval( 'PT' . $extension . 'S' ) );
			$close_time = $close->format( 'Y-m-d H:i:s' );
		} catch ( \Exception $e ) {
			Log::error( $e->getMessage(), compact( 'extension' ) );
			return false;
		}

		// Be sure to extend, not shorten.
		if ( $close_time < $this->get_end_date_time( $auction_id ) ) {
			return false;
		}

		// Update the Auction Close Date/Time
		update_post_meta( $auction_id, self::AUCTION_CLOSE_META_KEY, $close_time );

		// Update Extensions
		$extensions = $this->get_extensions( $auction_id );
		++$extensions;
		update_post_meta( $auction_id, self::AUCTION_EXTENSIONS_META_KEY, $extensions );

		// Trigger Node to update the Auction.
		goodbids()->auctioneer->auctions->update( $auction_id );

		return true;
	}

}
