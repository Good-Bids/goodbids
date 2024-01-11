<?php
/**
 * WordPress Custom API Details Endpoints
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Auctions\API;

defined( 'ABSPATH' ) || exit;

use GoodBids\Auctions\Auctions;
use GoodBids\Utilities\Payload;
use WC_REST_Controller;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

/**
 * API Details Controller
 *
 * @since 1.0.0
 */
class Details extends WC_REST_Controller {

	/**
	 * Endpoint namespace.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $namespace = 'wp/v2';

	/**
	 * Route base.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $rest_base = Auctions::SINGULAR_SLUG;

	/**
	 * Route endpoint.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private string $rest_endpoint = 'details';

	/**
	 * Register routes.
	 *
	 * @since 1.0.0
	 */
	public function register_routes(): void {
		register_rest_route(
			$this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/' . $this->rest_endpoint, [
				[
					'methods'  => WP_REST_Server::READABLE,
					'callback' => [ $this, 'get_details' ],
					'args'     => [
						'id' => [
							'description' => __( 'Unique identifier for the resource (Auction ID).', 'goodbids' ),
							'type'        => 'integer',
						],
					],
				],
				'schema' => [ $this, 'get_public_item_schema' ],
			]
		);
	}

	/**
	 * Returns Details for a given Auction.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_details( WP_REST_Request $request ): WP_Error|WP_REST_Response {
		$payload_data = [
			'auctionStatus',
			'socketUrl',
			'accountUrl',
			'endTime',
		];

		$status = strtolower( goodbids()->auctions->get_status( $request['id'] ) );

		if ( strtolower( Auctions::STATUS_UPCOMING ) === $status ) {
			$payload_data[] = 'startTime';
		} elseif ( strtolower( Auctions::STATUS_LIVE ) === $status ) {
			$payload_data = array_merge(
				$payload_data,
				[
					'startTime',
					'totalBids',
					'totalRaised',
					'currentBid',
					'lastBid',
				]
			);
		} elseif ( strtolower( Auctions::STATUS_CLOSED ) === $status ) {
			$payload_data = array_merge(
				$payload_data,
				[
					'totalBids',
					'totalRaised',
					'currentBid',
					'lastBid',
				]
			);
		}

		$data = ( new Payload( $request['id'], $payload_data ) )->get_data();

		return rest_ensure_response( $data );
	}
}
