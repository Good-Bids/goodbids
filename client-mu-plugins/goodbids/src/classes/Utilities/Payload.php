<?php
/**
 * Auctioneer Payload
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Utilities;

use GoodBids\Auctions\Auction;
use GoodBids\Users\FreeBids;
use WC_Order;
use WC_Product;
use WP_User;

/**
 * Class for Generating Payloads for Auctioneer
 *
 * @since 1.0.0
 */
class Payload {

	/**
	 * Items to include in the Payload
	 *
	 * @since 1.0.0
	 * @var string[]
	 */
	private array $payload = [];

	/**
	 * @since 1.0.0
	 * @var array
	 */
	private array $override_data = [];

	/**
	 * The Auction ID.
	 *
	 * @since 1.0.0
	 * @var ?int
	 */
	private ?int $auction_id;

	/**
	 * The instance of the Auction.
	 *
	 * @since 1.0.0
	 * @var ?Auction
	 */
	private ?Auction $auction;

	/**
	 * The User ID.
	 *
	 * @since 1.0.0
	 * @var ?int
	 */
	private ?int $user_id = null;

	/**
	 * The Bid Product
	 *
	 * @since 1.0.0
	 * @var ?WC_Product
	 */
	private ?WC_Product $bid_variation = null;

	/**
	 * The Last Bid Order
	 *
	 * @since 1.0.0
	 * @var ?WC_Order
	 */
	private ?WC_Order $last_bid = null;

	/**
	 * The Last Bidder User ID
	 *
	 * @since 1.0.0
	 * @var ?int
	 */
	private ?int $last_bidder_id = null;

	/**
	 * The Last Bidder User object
	 *
	 * @since 1.0.0
	 * @var ?WP_User
	 */
	private ?int $last_bidder = null;

	/**
	 * Initialize a new Payload
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 * @param array $data
	 *
	 * @return Payload
	 */
	public function __construct( int $auction_id, array $data = [] ) {
		$this->set_auction_id( $auction_id );
		$this->set_payload_data( $data );
		return $this;
	}

	/**
	 * Sets the Auction ID
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return Payload
	 */
	public function set_auction_id( int $auction_id ): Payload {
		$this->auction_id = $auction_id;
		$this->auction    = goodbids()->auctions->get( $auction_id );
		return $this;
	}

	/**
	 * Sets the User ID
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id
	 *
	 * @return Payload
	 */
	public function set_user_id( int $user_id ): Payload {
		$this->user_id = $user_id;
		return $this;
	}

	/**
	 * Returns the User ID
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_user_id(): int {
		return $this->user_id;
	}

	/**
	 * Set the Payload data based on the context.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data
	 *
	 * @return Payload
	 */
	public function set_payload_data( array $data ): Payload {
		$this->payload = array_merge( $this->payload, $data );
		return $this;
	}

	/**
	 * Override default Payload Data.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key
	 * @param mixed  $value
	 *
	 * @return void
	 */
	public function set_data( string $key, mixed $value ): void {
		$this->override_data[ $key ] = $value;
	}

	/**
	 * Get the Payload Data as an array.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_data(): array {
		$data  = [];
		$dates = [
			'startTime',
			'endTime',
			'requestTime',
		];

		foreach ( $this->payload as $item ) {
			$data[ $item ] = $this->get_payload_item( $item );

			if ( in_array( $item, $dates, true ) ) {
				$data[ $item ] = $this->convert_datetime_to_gmt( $data[ $item ] );
			}
		}

		return $data;
	}

	/**
	 * Get the payload item value
	 *
	 * @since 1.0.0
	 *
	 * @param string $item
	 *
	 * @return mixed
	 */
	private function get_payload_item( string $item ): mixed {
		if ( isset( $this->override_data[ $item ] ) ) {
			return $this->override_data[ $item ];
		}

		return match ( $item ) {
			'accountUrl'        => $this->get_auth_url(),
			'auctionStatus'     => strtolower( $this->auction->get_status() ),
			'bidUrl'            => goodbids()->bids->get_place_bid_url( $this->auction_id ),
			'currentBid'        => $this->get_current_bid(),
			'endTime'           => $this->auction->get_end_date_time( 'c' ),
			'freeBidsAvailable' => $this->auction->get_free_bids_available(),
			'freeBidsAllowed'   => $this->auction->are_free_bids_allowed(),
			'isLastBidder'      => $this->is_user_last_bidder( $this->get_user_id() ),
			'lastBid'           => $this->get_last_bid_amount(),
			'lastBidder'        => $this->get_last_bidder_id(),
			'requestTime'       => current_datetime()->format( 'c' ),
			'rewardClaimed'     => $this->has_user_claimed_reward(),
			'rewardUrl'         => goodbids()->rewards->get_claim_reward_url( $this->auction_id ),
			'shareUrl'          => '', // TBD.
			'socketUrl'         => $this->get_socket_url(),
			'startTime'         => $this->auction->get_start_date_time( 'c' ),
			'totalBids'         => $this->auction->get_bid_count(),
			'totalRaised'       => $this->auction->get_total_raised(),
			'useFreeBidParam'   => FreeBids::USE_FREE_BID_PARAM,
			'userFreeBids'      => goodbids()->free_bids->get_available_count( $this->get_user_id() ),
			'userId'            => $this->get_user_id(),
			'userTotalBids'     => $this->auction->get_user_bid_count( $this->get_user_id() ),
			'userTotalDonated'  => $this->auction->get_user_total_donated( $this->get_user_id() ),
			default             => null,
		};
	}

	/**
	 * Convert a local date to GMT.
	 *
	 * @since 1.0.0
	 *
	 * @param string $date
	 * @param string $format
	 *
	 * @return string
	 */
	public function convert_datetime_to_gmt( string $date, string $format = 'c' ): string {
		if ( str_ends_with( $date, '+00:00' ) ) {
			$date = substr( $date, 0, -6 );
		}

		return get_gmt_from_date( $date, $format );
	}

	/**
	 * Get the current bid value
	 *
	 * @since 1.0.0
	 *
	 * @return ?float
	 */
	private function get_current_bid(): ?float {
		if ( ! $this->bid_variation ) {
			$this->bid_variation = goodbids()->bids->get_variation( $this->auction_id );
		}

		return floatval( $this->bid_variation?->get_price( 'edit' ) );
	}

	/**
	 * Get the last bid value
	 *
	 * @since 1.0.0
	 *
	 * @return ?float
	 */
	private function get_last_bid_amount(): ?float {
		if ( ! $this->last_bid ) {
			$this->last_bid = $this->auction->get_last_bid();
		}

		if ( $this->last_bid ) {
			return $this->last_bid->get_subtotal();
		}

		return 0;
	}

	/**
	 * Get the last bidder User ID
	 *
	 * @since 1.0.0
	 *
	 * @return ?int
	 */
	private function get_last_bidder_id(): ?int {
		if ( ! $this->last_bidder_id ) {
			$this->last_bidder_id = $this->auction->get_last_bidder_id();
		}

		return $this->last_bidder_id;
	}

	/**
	 * Get the last bidder User object
	 *
	 * @since 1.0.0
	 *
	 * @return ?WP_User
	 */
	private function get_last_bidder(): ?WP_User {
		if ( ! $this->last_bidder ) {
			$this->last_bidder = $this->auction->get_last_bidder();
		}

		return $this->last_bidder;
	}

	/**
	 * Checks if the last bidder matches the provided user ID.
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id
	 *
	 * @return bool
	 */
	private function is_user_last_bidder( int $user_id ): bool {
		if ( ! $this->last_bidder_id ) {
			$this->last_bidder_id = $this->auction->get_last_bidder_id();
		}

		return $this->last_bidder_id === $user_id;
	}

	/**
	 * Returns the Websocket URL to the Auctioneer.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	private function get_socket_url(): string {
		$auctioneer_url = goodbids()->auctioneer->get_url();
		$socket_url     = trailingslashit( str_replace( 'https://', 'wss://', $auctioneer_url ) );
		$socket_url    .= '_ws/connect';

		return $socket_url;
	}

	/**
	 * Returns the URL to the My Account page.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	private function get_auth_url(): string {
		$auth_page_id = wc_get_page_id( 'authentication' );

		if ( ! $auth_page_id ) {
			return '';
		}

		return get_permalink( $auth_page_id );
	}

	/**
	 * Checks if the user has claimed the reward for the auction.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function has_user_claimed_reward(): bool {
		if ( ! $this->auction_id || ! $this->get_user_id() ) {
			return false;
		}

		$auction = goodbids()->auctions->get( $this->auction_id );

		// Make sure it's a valid Auction.
		if ( ! $auction->has_started() || ! $auction->has_ended() ) {
			return false;
		}

		$winner = $auction->get_winning_bidder();
		if ( ! $winner || $winner->ID !== $this->get_user_id() ) {
			return false;
		}

		return goodbids()->rewards->is_redeemed( $auction->get_id() );
	}
}
