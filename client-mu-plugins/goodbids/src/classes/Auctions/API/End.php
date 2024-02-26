<?php
/**
 * Auction REST API End Endpoint
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
class End extends WC_REST_Controller {

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
	private string $rest_endpoint = 'end';

	/**
	 * Register routes.
	 *
	 * @since 1.0.0
	 */
	public function register_routes(): void {
		register_rest_route(
			$this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/' . $this->rest_endpoint, [
				[
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'end' ],
					'permission_callback' => '__return_true',
					'args'                => [
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
	 * Sends End event for a given Auction.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function end( WP_REST_Request $request ): WP_Error|WP_REST_Response {
		$auction_id = $request['id'];

		if ( ! $auction_id || ! is_numeric( $auction_id ) ) {
			return new WP_Error( 'missing_id', __( 'Missing valid resource ID.', 'goodbids' ) );
		}

		if ( goodbids()->auctions->get_post_type() !== get_post_type( $auction_id ) ) {
			return new WP_Error( 'not_auction_type', __( 'The provided resource ID is not a valid Auction post type.', 'goodbids' ) );
		}

		if ( 'publish' !== get_post_status( $auction_id ) ) {
			return new WP_Error( 'auction_not_published', __( 'The provided Auction ID does not have a published status.', 'goodbids' ) );
		}

		$auction = goodbids()->auctions->get( $auction_id );

		if ( ! $auction->has_ended() ) {
			return new WP_Error( 'auction_not_ended', __( 'The provided Auction ID has not ended yet.', 'goodbids' ) );
		}

		$auction->trigger_close();

		return rest_ensure_response( 'ok' );
	}
}
