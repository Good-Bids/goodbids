<?php
/**
 * Single Auction Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Auctions;

use DateInterval;
use DateTimeImmutable;
use Exception;
use GoodBids\Nonprofits\Invoices;
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
	 * The Auction ID.
	 *
	 * @since 1.0.0
	 * @var ?int
	 */
	private ?int $auction_id = null;

	/**
	 * Initialize Auctions
	 *
	 * @param ?int $auction_id
	 *
	 * @since 1.0.0
	 */
	public function __construct( ?int $auction_id = null ) {
		if ( null === $auction_id ) {
			$auction_id = goodbids()->auctions->get_auction_id();
		}

		$this->auction_id = $auction_id;
	}

	/**
	 * Get the Auction ID.
	 *
	 * @since 1.0.0
	 * @return ?int
	 */
	public function get_id(): ?int {
		return $this->auction_id;
	}

	/**
	 * Get the URL for the Auction
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_url(): string {
		return get_permalink( $this->get_id() );
	}

	/**
	 * Get the Auction Title
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_title(): string {
		return get_the_title( $this->get_id() );
	}

	/**
	 * Get the Invoice ID for an Auction
	 *
	 * @since 1.0.0
	 *
	 * @return ?int
	 */
	public function get_invoice_id(): ?int {
		$invoice_id = get_post_meta( $this->get_id(), Invoices::INVOICE_ID_META_KEY, true );

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
	 * @return bool
	 */
	public function has_bid_product(): bool {
		return boolval( goodbids()->bids->get_product_id( $this->get_id() ) );
	}

	/**
	 * Sets the Bid product ID for an Auction.
	 *
	 * @since 1.0.0
	 *
	 * @param int $bid_product_id
	 *
	 * @return void
	 */
	public function set_bid_product_id( int $bid_product_id ): void {
		update_post_meta( $this->get_id(), Bids::AUCTION_BID_META_KEY, $bid_product_id );
		update_post_meta( $bid_product_id, self::PRODUCT_AUCTION_META_KEY, $this->get_id() );
	}

	/**
	 * Sets the Bid product Variation ID for an Auction.
	 *
	 * @since 1.0.0
	 *
	 * @param int $bid_variation_id
	 *
	 * @return void
	 */
	public function set_bid_variation_id(int $bid_variation_id ): void {
		update_post_meta( $this->get_id(), Bids::AUCTION_BID_VARIATION_META_KEY, $bid_variation_id );
	}

	/**
	 * Retrieves a setting for an Auction.
	 *
	 * @since 1.0.0
	 *
	 * @param string $meta_key
	 *
	 * @return mixed
	 */
	public function get_setting( string $meta_key ): mixed {
		$value = get_field( $meta_key, $this->get_id() );

		return apply_filters( 'goodbids_auction_setting', $value, $meta_key, $this->get_id() );
	}

	/**
	 * Get the Auction Reward Estimated Value.
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_estimated_value(): int {
		return intval( $this->get_setting( 'estimated_value' ) );
	}

	/**
	 * Get the Auction Reward Estimated Value formatted.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_estimated_value_formatted(): string {
		return wc_price( $this->get_estimated_value() );
	}

	/**
	 * Get the Auction Start Date/Time
	 *
	 * @since 1.0.0
	 *
	 * @param string $format
	 *
	 * @return string
	 */
	public function get_start_date_time( string $format = '' ): string {
		$start = $this->get_setting( 'auction_start' );

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
	 * @param string $format
	 *
	 * @return string
	 */
	public function get_end_date_time( string $format = '' ): string {
		$end = $this->get_setting( 'auction_end' );

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
	 * @return bool
	 */
	public function has_started(): bool {
		$start_date_time = $this->get_start_date_time();

		if ( ! $start_date_time ) {
			Log::warning( 'Auction has no start date/time.', compact( 'this' ) );
			return false;
		}

		return $start_date_time < current_datetime()->format( 'Y-m-d H:i:s' );
	}

	/**
	 * Check if an Auction has ended.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function has_ended(): bool {
		if ( 'publish' !== get_post_status( $this->auction_id ) ) {
			return false;
		}

		$end_date_time = $this->get_end_date_time();

		if ( ! $end_date_time ) {
			Log::warning( 'Auction has no end date/time.', compact( 'this' ) );
			return false;
		}

		return $end_date_time <= current_datetime()->format( 'Y-m-d H:i:s' );
	}

	/**
	 * Get number of auction extensions
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_extensions(): int {
		$extensions = get_post_meta( $this->get_id(), self::AUCTION_EXTENSIONS_META_KEY, true );

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
	 * @return bool
	 */
	public function is_extension_window(): bool {
		if ( $this->has_ended() ) {
			return false;
		}

		// One extension = always in window.
		if ( $this->get_extensions() ) {
			return true;
		}

		$end_time  = $this->get_end_date_time();
		$extension = $this->get_bid_extension();

		if ( ! $end_time || ! $extension ) {
			return false;
		}

		try {
			$end = new DateTimeImmutable( $end_time );

			// Subtract seconds from end time to get window start.
			$window = $end->sub( new DateInterval( 'PT' . $extension . 'S' ) );
		} catch ( Exception $e ) {
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
	 * @return ?int
	 */
	public function get_bid_extension(): ?int {
		$bid_extension = $this->get_setting( 'bid_extension' );

		if ( ! $bid_extension ) {
			Log::error( '[CONFIG] Unable to load Bid Extension', [ 'auction_id' => $this->get_id() ] );
			return null;
		}

		$minutes = ! empty( $bid_extension['minutes'] ) ? intval( $bid_extension['minutes'] ) : 0;
		$seconds = ! empty( $bid_extension['seconds'] ) ? intval( $bid_extension['seconds'] ) : 0;

		return ( $minutes * MINUTE_IN_SECONDS ) + $seconds;
	}

	/**
	 * Get the formatted Auction Bid Extension time
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_bid_extension_formatted(): string {
		$bid_extension = $this->get_setting( 'bid_extension' );
		$formatted     = '';

		if ( ! $bid_extension ) {
			return $formatted;
		}

		if ( ! empty( $bid_extension['minutes'] ) ) {
			$formatted = number_format( $bid_extension['minutes'], 0 ) . ' ' . __( 'minutes', 'goodbids' );
		}

		if ( ! empty( $bid_extension['seconds'] ) ) {
			if ( $formatted ) {
				$formatted .= ' ' . __( 'and', 'goodbids' ) . ' ';
			}
			$formatted .= number_format( $bid_extension['seconds'], 0 ) . ' ' . __( 'seconds', 'goodbids' );
		}

		return $formatted;
	}

	/**
	 * Get the Auction Bid Increment amount
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_bid_increment(): int {
		return intval( $this->get_setting( 'bid_increment' ) );
	}

	/**
	 * Get the Formatted Auction Bid Increment amount
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_bid_increment_formatted(): string {
		return wc_price( $this->get_bid_increment() );
	}

	/**
	 * Get the Auction Starting Bid amount
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_starting_bid(): int {
		return intval( $this->get_setting( 'starting_bid' ) );
	}

	/**
	 * Calculate the Auction Starting Bid amount
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function calculate_starting_bid(): int {
		$starting_bid = $this->get_starting_bid();
		if ( ! $starting_bid ) {
			$starting_bid = $this->get_bid_increment();
		}

		return $starting_bid;
	}

	/**
	 * Get the Auction Goal Amount
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_goal(): int {
		return intval( $this->get_setting( 'auction_goal' ) );
	}

	/**
	 * Get the Auction Goal Amount
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_goal_formatted(): string {
		return wc_price( $this->get_goal() );
	}

	/**
	 * Get the Auction Expected High Bid Amount
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_expected_high_bid(): int {
		return intval( $this->get_setting( 'expected_high_bid' ) );
	}

	/**
	 * Get the Auction Expected High Bid Amount formatted
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_expected_high_bid_formatted(): string {
		return wc_price( $this->get_expected_high_bid() );
	}

	/**
	 * Checks if Free Bids are allowed on Auction
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function are_free_bids_allowed(): bool {
		$all_bids  = count( $this->get_bid_order_ids() );
		$free_bids = count( $this->get_free_bid_order_ids() );

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
	 * @return int
	 */
	public function get_free_bids_available(): int {
		$free_bids = get_post_meta( $this->get_id(), Bids::FREE_BIDS_META_KEY, true );

		// Return the default value if we have no value.
		if ( ! $free_bids && 0 !== $free_bids && '0' !== $free_bids ) {
			$free_bids = goodbids()->get_config( 'auctions.default-free-bids' );
			update_post_meta( $this->get_id(), Bids::FREE_BIDS_META_KEY, $free_bids );
		}

		return intval( $free_bids );
	}

	/**
	 * Update the Free Bids count for an Auction
	 *
	 * @since 1.0.0
	 *
	 * @param int $free_bids
	 *
	 * @return void
	 */
	public function update_free_bids( int $free_bids ): void {
		update_post_meta( $this->get_id(), Bids::FREE_BIDS_META_KEY, $free_bids );
	}

	/**
	 * Maybe Award a Free Bid, if available
	 *
	 * @since 1.0.0
	 *
	 * @param ?int   $user_id
	 * @param string $description
	 *
	 * @return bool
	 */
	public function maybe_award_free_bid( ?int $user_id = null, string $description = '' ): bool {
		$free_bids = $this->get_free_bids_available();
		if ( ! $free_bids ) {
			return false;
		}

		if ( null === $user_id ) {
			$user_id = get_current_user_id();
		}

		if ( goodbids()->users->award_free_bid( $user_id, $this->get_id(), $description ) ) {
			--$free_bids;
			$this->update_free_bids( $free_bids );
			return true;
		}

		return false;
	}

	/**
	 * Get Bid Order IDs for an Auction
	 *
	 * @since 1.0.0
	 *
	 * @param int  $limit
	 * @param ?int $user_id
	 *
	 * @return int[]
	 */
	public function get_bid_order_ids( int $limit = -1, ?int $user_id = null ): array {
		return goodbids()->auctions->get_bid_order_ids( $this->get_id(), $limit, $user_id );
	}

	/**
	 * Get all Bidders for an Auction
	 *
	 * @since 1.0.0
	 *
	 * @return int[]
	 */
	public function get_bidder_ids(): array {
		$orders  = $this->get_bid_orders();
		$bidders = [];
		foreach ( $orders as $order ) {
			$bidders[] = $order->get_user_id();
		}
		return $bidders;
	}

	/**
	 * Get Order IDs that have been placed using a Free Bid.
	 *
	 * @since 1.0.0
	 *
	 * @param int  $limit
	 * @param ?int $user_id
	 *
	 * @return int[]
	 */
	public function get_free_bid_order_ids( int $limit = -1, ?int $user_id = null ): array {
		$orders = $this->get_bid_order_ids( $limit, $user_id );
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
	 * @param int  $limit
	 * @param ?int $user_id
	 *
	 * @return WC_Order[]
	 */
	public function get_free_bid_orders( int $limit = -1, ?int $user_id = null ): array {
		return array_map(
			fn ( $order ) => wc_get_order( $order ),
			$this->get_free_bid_order_ids( $limit, $user_id )
		);
	}

	/**
	 * Get Bid Order objects for an Auction
	 *
	 * @since 1.0.0
	 *
	 * @param int  $limit
	 * @param ?int $user_id
	 *
	 * @return WC_Order[]
	 */
	public function get_bid_orders( int $limit = -1, ?int $user_id = null ): array {
		return array_map(
			fn ( $order ) => wc_get_order( $order ),
			$this->get_bid_order_ids( $limit, $user_id )
		);
	}

	/**
	 * Get the Auction Bid Count. If a User ID is specified, the Bid Count will be filtered by that User.
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_bid_count(): int {
		$transient = sprintf( self::BID_COUNT_TRANSIENT, $this->get_id() );
		$bid_count = get_transient( $transient );

		if ( $bid_count ) {
			return $bid_count;
		}

		$orders    = $this->get_bid_order_ids();
		$bid_count = count( $orders );

		set_transient( $transient, $bid_count, HOUR_IN_SECONDS );

		return $bid_count;
	}

	/**
	 * Get bids for a specific User
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $user_id
	 * @param int $limit
	 *
	 * @return array
	 */
	public function get_user_bid_order_ids( ?int $user_id = null, int $limit = -1 ): array {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}
		return $this->get_bid_order_ids( $limit, $user_id );
	}

	/**
	 * Get total bids for a specific User on an Auction.
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id
	 *
	 * @return int
	 */
	public function get_user_bid_count( int $user_id ): int {
		$orders = $this->get_user_bid_order_ids( $user_id );
		return count( $orders );
	}

	/**
	 * Get last bid order for a specific User
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $user_id
	 *
	 * @return ?WC_Order
	 */
	public function get_user_last_bid( ?int $user_id = null): ?WC_Order {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}

		$bid_order_ids = $this->get_bid_order_ids( 1, $user_id );

		if ( ! $bid_order_ids ) {
			return null;
		}

		return wc_get_order( $bid_order_ids[0] );
	}

	/**
	 * Get the Auction Total Raised
	 *
	 * @since 1.0.0
	 *
	 * @return float
	 */
	public function get_total_raised(): float {
		$transient = sprintf( self::TOTAL_RAISED_TRANSIENT, $this->get_id() );
		$total     = get_transient( $transient );

		if ( $total ) {
			return $total;
		}

		$total = collect( $this->get_bid_orders() )
			->sum( fn( $order ) => $order->get_total( 'edit' ) );

		set_transient( $transient, $total, HOUR_IN_SECONDS );

		return $total;
	}

	/**
	 * Get the formatted Auction Total Raised
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_total_raised_formatted(): string {
		return wc_price( $this->get_total_raised() );
	}

	/**
	 * Get the Auction Total Donated by User
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id
	 *
	 * @return float
	 */
	public function get_user_total_donated( int $user_id ): float {
		return collect( $this->get_bid_orders( -1, $user_id ) )
			->sum( fn( $order ) => $order->get_total( 'edit' ) );
	}

	/**
	 * Get the formatted Total Donated by User
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id
	 *
	 * @return string
	 */
	public function get_user_total_donated_formatted( int $user_id ): string {
		return wc_price( $this->get_user_total_donated( $user_id ) );
	}

	/**
	 * Get the last bid order for an Auction.
	 *
	 * @since 1.0.0
	 *
	 * @return ?WC_Order
	 */
	public function get_last_bid(): ?WC_Order {
		$orders = $this->get_bid_orders( 1 );

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
	 * @return ?WP_User
	 */
	public function get_last_bidder(): ?WP_User {
		$last_bid = $this->get_last_bid();
		return $last_bid?->get_user();
	}

	/**
	 * Checks if the current user is the current high bidder of an Auction
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_current_user_winning(): bool {
		$last_bidder = $this->get_last_bidder();
		return $last_bidder?->ID === get_current_user_id();
	}

	/**
	 * Get the Auction's winning bidder.
	 *
	 * @since 1.0.0
	 *
	 * @return ?WP_User
	 */
	public function get_winning_bidder(): ?WP_User {
		if ( ! $this->has_ended() ) {
			return null;
		}

		return $this->get_last_bidder();
	}

	/**
	 * Checks if the current user is the winning bidder of an Auction
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_current_user_winner(): bool {
		$winning_bidder = $this->get_winning_bidder();
		return $winning_bidder?->ID === get_current_user_id();
	}

	/**
	 * Get the status of an Auction.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_status(): string {
		if ( 'publish' !== get_post_status( $this->get_id() ) ) {
			return self::STATUS_DRAFT;
		}

		$status = self::STATUS_UPCOMING;

		if ( $this->has_started() ) {
			$status = self::STATUS_LIVE;
		}

		if ( $this->has_ended() ) {
			$status = self::STATUS_CLOSED;
		}

		return $status;
	}

	/**
	 * Trigger to Node the start of an auction.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function trigger_start(): bool {
		/**
		 * @param int $auction_id
		 */
		do_action( 'goodbids_auction_start', $this->get_id() );

		$result = goodbids()->auctioneer->auctions->start( $this->get_id() );

		if ( true !== $result ) {
			return false;
		}

		// Update the Auction meta to indicate it has started.
		// This is used for the sole purposes of filtering started auctions from get_starting_auctions() method.
		update_post_meta( $this->get_id(), self::AUCTION_STARTED_META_KEY, 1 );

		return true;
	}

	/**
	 * Check if the Auction Start event has been triggered.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function start_triggered(): bool {
		return boolval( get_post_meta( $this->get_id(), self::AUCTION_STARTED_META_KEY, true ) );
	}

	/**
	 * Trigger to Node an auction has ended.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function trigger_close(): bool {
		/**
		 * @param int $auction_id
		 */
		do_action( 'goodbids_auction_end', $this->get_id() );

		$result = goodbids()->auctioneer->auctions->end( $this->get_id() );

		if ( true !== $result ) {
			return false;
		}

		// Update the Auction meta to indicate it has closed.
		// This is used for the sole purposes of filtering started auctions from get_closing_auctions() method.
		update_post_meta( $this->get_id(), self::AUCTION_CLOSED_META_KEY, 1 );

		return true;
	}

	/**
	 * Check if the Auction End event has been triggered.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function end_triggered(): bool {
		return boolval( get_post_meta( $this->get_id(), self::AUCTION_CLOSE_META_KEY, true ) );
	}

	/**
	 * Extend the auction
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function extend(): bool {
		if ( ! $this->is_extension_window() ) {
			return false;
		}

		$extension = $this->get_bid_extension();

		// Bail early if missing extension value.
		if ( ! $extension ) {
			Log::error( 'Missing Auction Bid Extension', compact( 'this' ) );
			return false;
		}

		try {
			$close      = current_datetime()->add( new DateInterval( 'PT' . $extension . 'S' ) );
			$close_time = $close->format( 'Y-m-d H:i:s' );
		} catch ( Exception $e ) {
			Log::error( $e->getMessage(), compact( 'extension' ) );
			return false;
		}

		// Be sure to extend, not shorten.
		if ( $close_time < $this->get_end_date_time() ) {
			return false;
		}

		// Update the Auction Close Date/Time
		update_post_meta( $this->get_id(), self::AUCTION_CLOSE_META_KEY, $close_time );

		// Update Extensions
		$extensions = $this->get_extensions();
		++$extensions;
		update_post_meta( $this->get_id(), self::AUCTION_EXTENSIONS_META_KEY, $extensions );

		// Trigger Node to update the Auction.
		goodbids()->auctioneer->auctions->update( $this->get_id() );

		return true;
	}

	/**
	 * Increase the current bid amount.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function increase_bid(): bool {
		$bids = goodbids()->bids;

		$bid_product   = $bids->get_product( $this->get_id() );
		$bid_variation = $bids->get_variation( $this->get_id() );

		if ( ! $bid_product || ! $bid_variation ) {
			Log::error( 'Auction missing Bid Product or Variation', compact( 'this' ) );
			return false;
		}

		$increment_amount = $this->get_bid_increment();
		$current_price    = floatval( $bid_variation->get_regular_price( 'edit' ) );
		$new_price        = $current_price + $increment_amount;

		// Add support for new variation.
		$bids->update_bid_product_attributes( $bid_product );

		// Create the new Variation.
		$new_variation = $bids->create_new_bid_variation( $bid_product->get_id(), $new_price, $this->get_id() );

		if ( ! $new_variation->save() ) {
			Log::error( 'There was a problem saving the new Bid Variation', compact( 'this' ) );
			return false;
		}

		// Set the Bid variation as a meta of the Auction.
		$this->set_bid_variation_id( $new_variation->get_id() );

		// Disallow backorders on previous variation.
		$bid_variation->set_backorders( 'no' );
		$bid_variation->save();

		return true;
	}

	/**
	 * Get Auction Watch Count
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_watch_count(): int {
		return goodbids()->watchers->get_watcher_count( $this->get_id() );
	}
}
