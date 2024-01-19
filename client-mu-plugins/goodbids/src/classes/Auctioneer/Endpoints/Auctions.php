<?php
/**
 * Auctioneer Auctions Endpoint
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Auctioneer\Endpoints;

use GoodBids\Utilities\Payload;

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
	 * @param array $extra_data
	 *
	 * @return bool
	 */
	public function start( int $auction_id, array $extra_data = [] ): bool {
		$guid = goodbids()->auctions->get_guid( $auction_id );

		if ( ! $guid ) {
			// TODO: Log error.
			return false;
		}

		// Set up the payload data with defaults.
		$payload_data = $this->setup_payload_data(
			$guid,
			[
				'currentBid',
				'endTime',
				'freeBidsAllowed',
				'freeBidsAvailable',
				'startTime',
			],
			$extra_data
		);

		$endpoint = "{$this->endpoint}/$guid/start";
		$payload  = ( new Payload( $auction_id, $payload_data ) )->get_data();
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
	 * @param array $extra_data
	 *
	 * @return bool
	 */
	public function end( int $auction_id, array $extra_data = [] ): bool {
		$guid = goodbids()->auctions->get_guid( $auction_id );

		if ( ! $guid ) {
			// TODO: Log error.
			return false;
		}

		// Set up the payload data with defaults.
		$payload_data = $this->setup_payload_data(
			$guid,
			[
				'lastBid',
				'lastBidder',
				'totalBids',
				'totalRaised',
			],
			$extra_data
		);

		$endpoint = "{$this->endpoint}/$guid/end";
		$payload  = ( new Payload( $auction_id, $payload_data ) )->get_data();
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
	 * @param array $extra_data
	 *
	 * @return bool
	 */
	public function update( int $auction_id, array $extra_data = [] ): bool {
		$guid = goodbids()->auctions->get_guid( $auction_id );

		if ( ! $guid ) {
			// TODO: Log error.
			return false;
		}

		// Set up the payload data with defaults.
		$payload_data = $this->setup_payload_data(
			$guid,
			[
				'currentBid',
				'endTime',
				'freeBidsAllowed',
				'freeBidsAvailable',
			],
			$extra_data
		);

		$endpoint = "{$this->endpoint}/$guid/update";
		$payload  = ( new Payload( $auction_id, $payload_data ) )->get_data();
		$response = goodbids()->auctioneer->request( $endpoint, $payload, 'PUT' );

		if ( ! $response ) {
			return false;
		}

		return true;
	}

	/**
	 * Build the payload array
	 *
	 * @since 1.0.0
	 *
	 * @param string $auction_guid
	 * @param array $payload_data
	 * @param array $extra_data
	 *
	 * @return array
	 */
	private function setup_payload_data( string $auction_guid, array $payload_data, array $extra_data = [] ): array {
		$defaults = [
			'id'          => $auction_guid,
			'requestTime' => current_datetime()->format( 'c' ),
		];

		return array_filter(
			array_merge(
				$defaults,
				$payload_data,
				$extra_data
			)
		);
	}

}
