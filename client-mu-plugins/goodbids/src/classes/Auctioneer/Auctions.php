<?php
/**
 * Auctioneer Auctions Endpoint
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Auctioneer;

/**
 * Class for Auctioneer Auctions
 *
 * @since 1.0.0
 */
class Auctions {

	/**
	 * Auctioneer Auctions Endpoint
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private string $endpoint = 'auctions';

	/**
	 * Trigger an Auction Start event for an Auction
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return bool
	 */
	public function start( int $auction_id ): bool {
		$guid = goodbids()->auctions->get_guid( $auction_id );

		if ( ! $guid ) {
			// TODO: Log error.
			return false;
		}

		$endpoint = "{$this->endpoint}/$guid/start";
		$payload  = $this->get_payload( $auction_id, 'start' );
		$response = goodbids()->auctioneer->request( $endpoint, $payload, 'POST' );

		if ( ! $response ) {
			return false;
		}

		return true;
	}

	/**
	 * Trigger an Auction End event for an Auction
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return bool
	 */
	public function end( int $auction_id ): bool {
		$guid = goodbids()->auctions->get_guid( $auction_id );

		if ( ! $guid ) {
			// TODO: Log error.
			return false;
		}

		$endpoint = "{$this->endpoint}/$guid/end";
		$payload  = $this->get_payload( $auction_id, 'end' );
		$response = goodbids()->auctioneer->request( $endpoint, $payload, 'POST' );

		if ( ! $response ) {
			return false;
		}

		return true;
	}

	/**
	 * Update an Auction
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 * @param string $context
	 *
	 * @return bool
	 */
	public function update( int $auction_id, string $context ): bool {
		$guid = goodbids()->auctions->get_guid( $auction_id );

		if ( ! $guid ) {
			// TODO: Log error.
			return false;
		}

		$endpoint = "{$this->endpoint}/$guid/update";
		$payload  = $this->get_payload( $auction_id, 'update:' . $context );
		$response = goodbids()->auctioneer->request( $endpoint, $payload, 'PUT' );

		if ( ! $response ) {
			return false;
		}

		return true;
	}

	/**
	 * Get the payload based on context
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 * @param string $context
	 *
	 * @return array
	 */
	private function get_payload( int $auction_id, string $context ): array {
		$bid_product = goodbids()->auctions->get_bid_product( $auction_id );

		$payload = [
			'id'          => goodbids()->auctions->get_guid( $auction_id ),
			'requestTime' => current_datetime()->format( 'c' ),
		];

		// TODO: Maybe refactor using individual Response or Payload classes.
		if ( 'start' === $context ) {

			$payload['startTime']  = goodbids()->auctions->get_start_date_time( $auction_id, 'c' );
			$payload['endTime']    = goodbids()->auctions->get_end_date_time( $auction_id, 'c' );
			$payload['currentBid'] = floatval( $bid_product?->get_price( 'edit' ) );


		} elseif ( 'update:' . \GoodBids\Auctions\Auctions::CONTEXT_EXTENSION === $context ) {

			$payload['currentBid'] = floatval( $bid_product?->get_price( 'edit' ) );
			$payload['endTime']    = goodbids()->auctions->get_end_date_time( $auction_id, 'c' );


		} elseif ( 'end' === $context ) {

			$last_bid    = goodbids()->auctions->get_last_bid( $auction_id );
			$last_bidder = goodbids()->auctions->get_last_bidder( $auction_id );

			$payload['totalBids']   = goodbids()->auctions->get_bid_count( $auction_id );
			$payload['totalRaised'] = goodbids()->auctions->get_total_raised( $auction_id );
			$payload['lastBid']     = $last_bid?->get_total( 'edit' );
			$payload['lastBidder']  = $last_bidder?->ID;
		}

		return $payload;
	}

}
