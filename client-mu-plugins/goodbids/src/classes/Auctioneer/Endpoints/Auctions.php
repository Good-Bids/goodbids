<?php
/**
 * Auctioneer Auctions Endpoint
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Auctioneer\Endpoints;

use GoodBids\Auctioneer\Payload;

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
		return ( new Payload( $auction_id, $context ) )->get_data();
	}

}
