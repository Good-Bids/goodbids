<?php
/**
 * Auctioneer Payload
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Auctioneer;

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
	 * Endpoint used for Request.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private string $endpoint;

	/**
	 * Additional context, if any.
	 *
	 * @since 1.0.0
	 * @var ?string
	 */
	private ?string $context;

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
	 * The Bid Product
	 *
	 * @since 1.0.0
	 * @var ?WC_Product
	 */
	private ?WC_Product $bid_product;

	/**
	 * The Last Bid Order
	 *
	 * @since 1.0.0
	 * @var ?WC_Order
	 */
	private ?WC_Order $last_bid;

	/**
	 * The Last Bidder User object
	 *
	 * @since 1.0.0
	 * @var ?WP_User
	 */
	private ?WP_User $last_bidder;

	/**
	 * Initialize a new Payload
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 * @param string $context
	 */
	public function __construct( int $auction_id, string $context ) {
		$this->auction_id = $auction_id;

		if ( str_contains( $context, ':' ) ) {
			$this->endpoint = substr( $context, 0, strpos( $context, ':' ) );
			$this->context  = substr( $context, strpos( $context, ':' ) + 1 );
		} else {
			$this->endpoint = $context;
		}

		$this->set_payload();
	}

	/**
	 * Set the Payload data based on the context.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function set_payload(): void {
		if ( 'start' === $this->endpoint ) {
			$this->payload = [
				'startTime',
				'endTime',
				'currentBid',
			];
		} elseif ( 'update' === $this->endpoint ) {
			$this->payload = [
				'currentBid',
				'endTime',
				'freeBidsAvailable',
			];
		} elseif ( 'end' === $this->endpoint ) {
			$this->payload = [
				'totalBids',
				'totalRaised',
				'lastBid',
				'lastBidder',
			];
		}

		if ( \GoodBids\Auctions\Auctions::CONTEXT_NEW_BID === $this->context ) {
			$this->payload = array_merge(
				$this->payload,
				[
					'totalBids',
					'totalRaised',
					'lastBid',
					'lastBidder',
				]
			);
		}
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
		$data = [
			'id'          => goodbids()->auctions->get_guid( $this->auction_id ),
			'requestTime' => current_datetime()->format( 'c' ),
		];

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
			'currentBid'        => $this->get_current_bid(),
			'endTime'           => goodbids()->auctions->get_end_date_time( $this->auction_id, 'c' ),
			'freeBidsAvailable' => false,
			'lastBid'           => $this->get_last_bid(),
			'lastBidder'        => $this->get_last_bidder(),
			'startTime'         => goodbids()->auctions->get_start_date_time( $this->auction_id, 'c' ),
			'totalBids'         => goodbids()->auctions->get_bid_count( $this->auction_id ),
			'totalRaised'       => goodbids()->auctions->get_total_raised( $this->auction_id ),
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
}
