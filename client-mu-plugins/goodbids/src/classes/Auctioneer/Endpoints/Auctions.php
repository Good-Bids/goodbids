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
		// Set up the payload data with defaults.
		$payload_data = $this->setup_payload_data(
			[
				'currentBid',
				'endTime',
				'freeBidsAllowed',
				'freeBidsAvailable',
				'startTime',
			],
			$extra_data
		);

		$endpoint = "$this->endpoint/$auction_id/start";
		$payload  = ( new Payload( $auction_id, $payload_data ) )->get_data();
		$response = goodbids()->auctioneer->request( $endpoint, $payload, 'POST' );

		// Bail if the request fails and the response code is not 208 (Already Reported).
		if ( ! $response && 208 !== goodbids()->auctioneer->get_last_response_code() ) {
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
		// Set up the payload data with defaults.
		$payload_data = $this->setup_payload_data(
			[
				'lastBid',
				'totalBids',
				'totalRaised',
			],
			$extra_data
		);

		$endpoint = "$this->endpoint/$auction_id/end";
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
	 * @param int   $auction_id
	 * @param array $extra_data
	 *
	 * @return bool
	 */
	public function update( int $auction_id, array $extra_data = [] ): bool {
		// Set up the payload data with defaults.
		$payload_data = $this->setup_payload_data(
			[
				'currentBid',
				'endTime',
				'freeBidsAllowed',
				'freeBidsAvailable',
				'lastBid',
				'lastBidder',
				'startTime',
				'totalBids',
				'totalRaised',
			],
			$extra_data
		);

		$endpoint = "$this->endpoint/$auction_id/update";
		$payload  = ( new Payload( $auction_id, $payload_data ) )->get_data();
		$response = goodbids()->auctioneer->request( $endpoint, $payload, 'POST' );

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
	 * @param array $payload_data
	 * @param array $extra_data
	 *
	 * @return array
	 */
	private function setup_payload_data( array $payload_data, array $extra_data = [] ): array {
		$defaults = [
			'requestTime',
		];

		return array_filter(
			array_unique(
				array_merge(
					$defaults,
					$payload_data,
					$extra_data
				)
			)
		);
	}

}
