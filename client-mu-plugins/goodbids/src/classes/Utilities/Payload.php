<?php
/**
 * Auctioneer Payload
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Utilities;

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
	 * The Auction ID.
	 *
	 * @since 1.0.0
	 * @var ?int
	 */
	private ?int $auction_id;

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
	private ?WC_Product $bid_product = null;

	/**
	 * The Last Bid Order
	 *
	 * @since 1.0.0
	 * @var ?WC_Order
	 */
	private ?WC_Order $last_bid = null;

	/**
	 * The Last Bidder User object
	 *
	 * @since 1.0.0
	 * @var ?WP_User
	 */
	private ?WP_User $last_bidder = null;

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
	 * Get the Payload Data as an array.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_data(): array {
		// Payload Defaults.
		$data = [];

		foreach ( $this->payload as $item ) {
			$data[ $item ] = $this->get_payload_item( $item );
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
		return match ( $item ) {
			'accountUrl'        => $this->get_auth_url(),
			'auctionStatus'     => strtolower( goodbids()->auctions->get_status( $this->auction_id ) ),
			'bidUrl'            => goodbids()->auctions->get_place_bid_url( $this->auction_id ),
			'currentBid'        => $this->get_current_bid(),
			'endTime'           => goodbids()->auctions->get_end_date_time( $this->auction_id, 'c' ),
			'freeBidsAvailable' => false,
			'isLastBidder'      => $this->is_user_last_bidder( $this->get_user_id() ),
			'lastBid'           => $this->get_last_bid(),
			'lastBidder'        => $this->get_last_bidder(),
			'rewardUrl'         => goodbids()->auctions->get_claim_reward_url( $this->auction_id ), // TBD.
			'shareUrl'          => '', // TBD.
			'socketUrl'         => $this->get_socket_url(),
			'startTime'         => goodbids()->auctions->get_start_date_time( $this->auction_id, 'c' ),
			'totalBids'         => goodbids()->auctions->get_bid_count( $this->auction_id ),
			'totalRaised'       => goodbids()->auctions->get_total_raised( $this->auction_id ),
			'userId'            => $this->get_user_id(),
			'userFreeBids'      => 0, // TBD.
			'userTotalBids'     => goodbids()->auctions->get_user_bid_count( $this->auction_id, $this->get_user_id() ),
			'userTotalDonated'  => goodbids()->auctions->get_user_total_donated( $this->auction_id, $this->get_user_id() ),
			default             => null,
		};
	}

	/**
	 * Get the current bid value
	 *
	 * @since 1.0.0
	 *
	 * @return ?float
	 */
	private function get_current_bid(): ?float {
		if ( ! $this->bid_product ) {
			$this->bid_product = goodbids()->auctions->get_bid_product( $this->auction_id );
		}

		return floatval( $this->bid_product?->get_price( 'edit' ) );
	}

	/**
	 * Get the last bid value
	 *
	 * @since 1.0.0
	 *
	 * @return ?float
	 */
	private function get_last_bid(): ?float {
		if ( ! $this->last_bid ) {
			$this->last_bid = goodbids()->auctions->get_last_bid( $this->auction_id );
		}

		return $this->last_bid?->get_total( 'edit' );
	}

	/**
	 * Get the last bidder User ID
	 *
	 * @since 1.0.0
	 *
	 * @return ?int
	 */
	private function get_last_bidder(): ?int {
		if ( ! $this->last_bidder ) {
			$this->last_bidder = goodbids()->auctions->get_last_bidder( $this->auction_id );
		}

		return $this->last_bidder?->ID;
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
		if ( ! $this->last_bidder ) {
			$this->last_bidder = goodbids()->auctions->get_last_bidder( $this->auction_id );
		}

		return $this->last_bidder?->ID === $user_id;
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
}
