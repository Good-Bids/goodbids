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
use GoodBids\Users\FreeBid;
use GoodBids\Utilities\Log;
use WC_Order;
use WC_Product;
use WP_Post;
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
	 * @var string
	 */
	const FREE_BIDS_META_KEY = '_goodbids_free_bids';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const BID_LOCKED_META_KEY = '_goodbids_bid_locked';

	/**
	 * @since 1.0.1
	 * @var string
	 */
	const LAST_BID_ORDER_META_KEY = '_goodbids_last_bid_order_id';

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
	 * The Auction ID.
	 *
	 * @since 1.0.0
	 * @var ?WP_Post
	 */
	private ?WP_Post $post = null;

	/**
	 * Use metadata or get_field()
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	private bool $use_meta = false;

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

		if ( ! $auction_id ) {
			return;
		}

		$this->auction_id = $auction_id;
		$this->post       = get_post( $this->auction_id );
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
	 * Check if Auction is Valid
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_valid(): bool {
		return ! is_null( $this->post );
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
	 * Get the URL to place a bid on the Auction
	 *
	 * @since 1.0.0
	 *
	 * @param bool $is_free_bid
	 *
	 * @return string
	 */
	public function get_place_bid_url( bool $is_free_bid = false ): string {
		return goodbids()->bids->get_place_bid_url( $this->get_id(), $is_free_bid );
	}

	/**
	 * Get the Edit URL for the Auction
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_edit_url(): string {
		$edit_url = get_edit_post_link( $this->get_id() );
		return $edit_url ?: '#';
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
	 * Get the Tax Invoice ID for an Auction if available.
	 *
	 * @since 1.0.0
	 *
	 * @return ?int
	 */
	public function get_tax_invoice_id(): ?int {
		$invoice_id = get_post_meta( $this->get_id(), Invoices::TAX_INVOICE_ID_META_KEY, true );

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
		return boolval( $this->get_product_id() );
	}

	/**
	 * Get the Bid Product ID
	 *
	 * @since 1.0.0
	 *
	 * @return ?int
	 */
	public function get_product_id(): ?int {
		return goodbids()->bids->get_product_id( $this->get_id() );
	}

	/**
	 * Get the Bid Product Variation
	 *
	 * @since 1.0.0
	 *
	 * @return ?int
	 */
	public function get_variation_id(): ?int {
		return goodbids()->bids->get_variation_id( $this->get_id() );
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
	public function set_bid_variation_id( int $bid_variation_id ): void {
		update_post_meta( $this->get_id(), Bids::AUCTION_BID_VARIATION_META_KEY, $bid_variation_id );
	}

	/**
	 * Get Bid Product ID
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_bid_product_id(): int {
		return goodbids()->bids->get_product_id( $this->get_id() );
	}

	/**
	 * Get Bid Product
	 *
	 * @since 1.0.0
	 *
	 * @return ?WC_Product
	 */
	public function get_bid_product(): ?WC_Product {
		return goodbids()->bids->get_product( $this->get_id() );
	}

	/**
	 * Get Reward Product ID
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_reward_id(): int {
		return goodbids()->rewards->get_product_id( $this->get_id() );
	}

	/**
	 * Get Reward Product
	 *
	 * @since 1.0.0
	 *
	 * @return ?WC_Product
	 */
	public function get_reward(): ?WC_Product {
		return goodbids()->rewards->get_product( $this->get_id() );
	}

	/**
	 * Get Claim Reward URL
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_claim_reward_url(): int {
		return goodbids()->rewards->get_claim_reward_url( $this->get_id() );
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
		if ( $this->use_meta ) {
			$value = get_post_meta( $this->get_id(), $meta_key, true );
		} else {
			$value = get_field( $meta_key, $this->get_id() );
		}

		return apply_filters( 'goodbids_auction_setting', $value, $meta_key, $this->get_id() );
	}

	/**
	 * Get the Auction Meta
	 *
	 * @since 1.0.0
	 *
	 * @param string $meta_key
	 *
	 * @return mixed
	 */
	public function get_meta( string $meta_key ): mixed {
		$this->use_meta = true;

		if ( method_exists( $this, 'get_' . $meta_key ) ) {
			return $this->{'get_' . $meta_key}();
		}

		return $this->get_setting( $meta_key );
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
	 * Get the formatted Auction Reward Estimated Value.
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
	 * Get the Original Auction End Date/Time
	 *
	 * @since 1.0.1
	 *
	 * @param string $format
	 *
	 * @return string
	 */
	public function get_original_end_date_time( string $format = '' ): string {
		$end = get_post_meta( $this->get_id(), 'auction_end', true );

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
		if ( 'publish' !== get_post_status( $this->get_id() ) ) {
			return false;
		}

		$start_date_time = $this->get_start_date_time();

		if ( ! $start_date_time ) {
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
			return false;
		}

		return $end_date_time <= current_datetime()->format( 'Y-m-d H:i:s' );
	}

	/**
	 * Check if an Auction is Ending Soon
	 *
	 * @since 1.0.1
	 *
	 * @return bool
	 */
	public function is_ending_soon(): bool {
		$end_date_time = $this->get_end_date_time();
		if ( ! $end_date_time ) {
			return false;
		}

		// TODO add a variable in the UI for 'ending soon notification threshold'
		
		$static_threshold_min = HOUR_IN_SECONDS * 1; // 60 minutes
		$static_threshold_max = HOUR_IN_SECONDS * 2; // 120 minutes

		try {
			$end  = new DateTimeImmutable( $end_date_time, wp_timezone() );
		} catch ( Exception $e ) {
			Log::error( $e->getMessage(), compact( 'end_date_time' ) );
			return false;
		}

		$diff = $end->getTimestamp() - current_datetime()->getTimestamp();

		if ( $diff <= 0 ) {
			return false;
		}

		// if the diff is less than the max for the threshold
		// and the diff is more than the min for the threshold
		// (i.e., if it falls within the threshold),
		// then it's ending soon.
		$ending_soon = $static_threshold_max > $diff && $static_threshold_min <= $diff;

		return $ending_soon;
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
			$bid_extension_minutes = $this->get_setting( 'bid_extension_minutes' );
			$bid_extension_seconds = $this->get_setting( 'bid_extension_seconds' );

			if ( ! $bid_extension_minutes && ! $bid_extension_seconds ) {
				return null;
			}

			$bid_extension = [
				'minutes' => $bid_extension_minutes,
				'seconds' => $bid_extension_seconds,
			];
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
		$seconds = $this->get_bid_extension();

		if ( is_null( $seconds ) ) {
			return '';
		}

		if ( $seconds < MINUTE_IN_SECONDS ) {
			return sprintf(
				'%d %s',
				$seconds,
				_n( 'second', 'seconds', $seconds, 'goodbids' )
			);
		}

		$min = floor( $seconds / MINUTE_IN_SECONDS );
		$sec = $seconds % MINUTE_IN_SECONDS;

		if ( $seconds < HOUR_IN_SECONDS ) {
			$return = sprintf(
				'%d %s',
				$min,
				_n( 'minute', 'minutes', $min, 'goodbids' )
			);

			if ( $sec ) {
				$return .= sprintf(
					' and %d %s',
					$sec,
					_n( 'second', 'seconds', $sec, 'goodbids' )
				);
			}

			return $return;
		}

		$hr  = floor( $seconds / HOUR_IN_SECONDS );
		$min = floor( ( $seconds % HOUR_IN_SECONDS ) / MINUTE_IN_SECONDS );

		$return = sprintf(
			'%d %s',
			$hr,
			_n( 'hour', 'hours', $hr, 'goodbids' )
		);

		if ( ! $min && ! $sec ) {
			return $return;
		}

		if ( $min && ! $sec ) {
			$return .= sprintf(
				' and %d %s',
				$min,
				_n( 'minute', 'minutes', $min, 'goodbids' )
			);
		} else if ( ! $min && $sec ) {
			$return .= sprintf(
				' and %d %s',
				$sec,
				_n( 'second', 'seconds', $sec, 'goodbids' )
			);
		} else {
			$return .= sprintf(
				', %d %s, and %d %s',
				$min,
				_n( 'minute', 'minutes', $min, 'goodbids' ),
				$sec,
				_n( 'second', 'seconds', $sec, 'goodbids' )
			);
		}

		return $return;
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
	 * Get the formatted Auction Goal Amount
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
	 * Get the formatted Auction Expected High Bid Amount
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
		$free_bids = get_post_meta( $this->get_id(), self::FREE_BIDS_META_KEY, true );

		// Return the default value if we have no value.
		if ( ! $free_bids && 0 !== $free_bids && '0' !== $free_bids ) {
			$free_bids = goodbids()->get_config( 'auctions.default-free-bids' );
			update_post_meta( $this->get_id(), self::FREE_BIDS_META_KEY, $free_bids );
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
		update_post_meta( $this->get_id(), self::FREE_BIDS_META_KEY, $free_bids );
	}

	/**
	 * Maybe Award a Free Bid, if available
	 *
	 * @since 1.0.0
	 *
	 * @param ?int   $user_id
	 * @param string $details
	 * @param bool   $notify_later
	 *
	 * @return bool
	 */
	public function maybe_award_free_bid( ?int $user_id = null, string $details = '', bool $notify_later = false ): bool {
		$auction_free_bids = $this->get_free_bids_available();
		if ( ! $auction_free_bids ) {
			return false;
		}

		if ( null === $user_id ) {
			$user_id = get_current_user_id();
		}

		if ( goodbids()->free_bids->award( $user_id, $this->get_id(), FreeBid::TYPE_PAID_BID, $details, $notify_later ) ) {
			// Update the Auction's available free bids.
			--$auction_free_bids;
			$this->update_free_bids( $auction_free_bids );
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
	 * Get the IDs of all users who have placed bid orders for this Auction.
	 *
	 * @since 1.0.0
	 *
	 * @param int $limit
	 *
	 * @return array
	 */
	public function get_bidder_ids( int $limit = -1 ): array {
		$orders   = $this->get_bid_orders( $limit );
		$user_ids = [];

		foreach ( $orders as $order ) {
			$user_ids[] = $order->get_user_id();
		}

		return array_unique( $user_ids );
	}
	/**
	 * Get the User's last bid on this Auction
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $user_id
	 *
	 * @return ?WC_Order
	 */
	public function get_user_last_bid( ?int $user_id = null ): ?WC_Order {
		$orders = $this->get_bid_order_ids( 1, $user_id );
		if ( ! $orders ) {
			return null;
		}

		return wc_get_order( $orders[0] );
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
	 * Get total bids for a specific User on an Auction.
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id
	 *
	 * @return int
	 */
	public function get_user_bid_count( int $user_id ): int {
		$orders = $this->get_bid_order_ids( -1, $user_id );
		return count( $orders );
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
	 * Alias get_bid_count method.
	 *
	 * @alias get_bid_count
	 * @since 1.0.0
	 * @return int
	 */
	public function get_total_bids(): int {
		return $this->get_bid_count();
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
	 * Get the formatted Auction Total Donated by User
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
		$last_order = get_post_meta( $this->get_id(), self::LAST_BID_ORDER_META_KEY, true );

		if ( $last_order ) {
			return wc_get_order( $last_order );
		}

		$orders = $this->get_bid_orders( 1 );

		if ( empty( $orders ) ) {
			return null;
		}

		return $orders[0];
	}

	/**
	 * Get the value of the last bid (regardless if free or not)
	 *
	 * @since 1.0.0
	 *
	 * @return ?float
	 */
	public function get_last_bid_value(): ?float {
		$last_bid = $this->get_last_bid();
		if ( ! $last_bid ) {
			return null;
		}

		if ( ! $last_bid->get_subtotal() ) {
			return floatval( $last_bid->get_discount_total() );
		}

		return $last_bid->get_subtotal();
	}

	/**
	 * Get the user that placed the last bid.
	 *
	 * @since 1.0.0
	 *
	 * @return ?WP_User
	 */
	public function get_last_bidder(): ?WP_User {
		$last_bidder_id = $this->get_last_bidder_id();

		if ( ! $last_bidder_id ) {
			return null;
		}

		$last_bidder = get_user_by( 'ID', $last_bidder_id );

		if ( ! $last_bidder ) {
			return null;
		}

		return $last_bidder;
	}

	/**
	 * Get the ID of the user who is the last bidder.
	 *
	 * @since 1.0.0
	 *
	 * @return ?int
	 */
	public function get_last_bidder_id(): ?int {
		$last_bid = $this->get_last_bid();

		if ( ! $last_bid ) {
			return null;
		}

		return $last_bid->get_user_id();
	}

	/**
	 * Checks if the current user is the current high bidder of an Auction
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_current_user_winning(): bool {
		$last_bidder_id = $this->get_last_bidder_id();
		return $last_bidder_id === get_current_user_id();
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
		if ( 'publish' !== get_post_status( $this->get_id() ) ) {
			return false;
		}

		if ( ! $this->has_started() || $this->start_triggered() ) {
			return false;
		}

		// Update the Auction meta to indicate it has started.
		update_post_meta( $this->get_id(), self::AUCTION_STARTED_META_KEY, 1 );

		/**
		 * Called when an Auction has started.
		 *
		 * @since 1.0.0
		 *
		 * @param int $auction_id
		 */
		do_action( 'goodbids_auction_start', $this->get_id() );

		// Reset the Auction transients.
		goodbids()->sites->clear_all_site_transients();

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
	 * @return void
	 */
	public function trigger_close(): void {
		if ( ! $this->has_ended() || $this->end_triggered() ) {
			return;
		}

		// Update the Auction meta to indicate it has closed.
		update_post_meta( $this->get_id(), self::AUCTION_CLOSED_META_KEY, 1 );

		/**
		 * Called when an Auction has ended.
		 *
		 * @since 1.0.0
		 *
		 * @param int $auction_id The Closing Auction ID.
		 */
		do_action( 'goodbids_auction_end', $this->get_id() );

		// Reset the Auction transients.
		goodbids()->sites->clear_all_site_transients();
	}

	/**
	 * Check if the Auction End event has been triggered.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function end_triggered(): bool {
		return boolval( get_post_meta( $this->get_id(), self::AUCTION_CLOSED_META_KEY, true ) );
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
			$extended_close      = current_datetime()->add( new DateInterval( 'PT' . $extension . 'S' ) );
			$extended_close_time = $extended_close->format( 'Y-m-d H:i:s' );
		} catch ( Exception $e ) {
			Log::error( $e->getMessage(), compact( 'extension' ) );
			return false;
		}

		// Be sure to extend, not shorten.
		if ( $extended_close_time < $this->get_end_date_time() ) {
			return false;
		}

		// Update the Auction Close Date/Time
		update_post_meta( $this->get_id(), self::AUCTION_CLOSE_META_KEY, $extended_close_time );

		// Update Extensions
		$extensions = $this->get_extensions();
		++$extensions;
		update_post_meta( $this->get_id(), self::AUCTION_EXTENSIONS_META_KEY, $extensions );

		/**
		 * Action triggered when an Auction is Extended.
		 *
		 * @since 1.0.1
		 *
		 * @param int $auction_id
		 */
		do_action( 'goodbids_auction_extended', $this->get_id() );

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
		$bids = goodbids()->bids; // Easier to reference.

		$bid_product = $bids->get_product( $this->get_id() );

		if ( ! $bid_product ) {
			Log::error( 'Auction missing Bid Product', [ 'auction' => $this->get_id() ] );
			return false;
		}

		$bid_variation = $bids->get_variation( $this->get_id() );

		if ( $bid_variation ) {
			$current_price = floatval( $bid_variation->get_price( 'edit' ) );
		} else {
			$current_price = $this->get_last_bid_value();
		}

		$increment_amount = $this->get_bid_increment();
		$new_price        = $current_price + $increment_amount;

		// Add support for new variation.
		$bids->update_bid_product_attributes( $bid_product );

		// Create the new Variation.
		$new_variation = $bids->create_new_bid_variation( $bid_product->get_id(), $new_price, $this->get_id() );

		if ( ! $new_variation->save() ) {
			Log::error( 'There was a problem saving the new Bid Variation', compact( 'this' ) );
			return false;
		}

		$bid_author = get_post_field( 'post_author', $bid_product->get_id() );
		$bids->set_variation_author( $new_variation, $bid_author );

		// Set the Bid variation as a meta of the Auction.
		$this->set_bid_variation_id( $new_variation->get_id() );

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

	/**
	 * Final checks to ensure bid is allowed.
	 *
	 * @since 1.0.0
	 *
	 * @param array $order_info
	 *
	 * @return bool
	 */
	public function bid_allowed( array $order_info ): bool {
		$product = wc_get_product( $this->get_variation_id() );

		if ( $order_info['variation_id'] !== $this->get_variation_id() ) {
			return false;
		}

		if ( $product->get_stock_quantity() <= 0 ) {
			return false;
		}

		if ( $this->bid_locked() ) {
			return false;
		}

		return true;
	}

	/**
	 * Check if the bid is currently locked.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function bid_locked(): bool {
		$lock = get_post_meta( $this->get_variation_id(), self::BID_LOCKED_META_KEY, true );

		if ( ! $lock ) {
			return false;
		}

		return get_current_user_id() !== $lock;
	}

	/**
	 * Lock the bid to prevent duplicates
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function lock_bid(): void {
		update_post_meta( $this->get_variation_id(), self::BID_LOCKED_META_KEY, get_current_user_id() );
	}

	/**
	 * Unlock the bid in the event checkout fails.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function unlock_bid(): void {
		delete_post_meta( $this->get_variation_id(), self::BID_LOCKED_META_KEY );
	}
}
