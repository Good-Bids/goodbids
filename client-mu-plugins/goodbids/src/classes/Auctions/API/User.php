<?php
/**
 * WordPress Custom API Auction User Endpoints
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
 * API User Controller
 *
 * @since 1.0.0
 */
class User extends WC_REST_Controller {

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
	private string $rest_endpoint = 'user';

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
					'callback'            => [ $this, 'post_user' ],
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
	 * Returns User details for a given Auction.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function post_user( WP_REST_Request $request ): WP_Error|WP_REST_Response {
		$cookie = $request->get_param( 'cookie' );

		if ( ! $cookie ) {
			return new WP_Error( 'goodbids_missing_required_parameters', __( 'The required `cookie` parameter was not found.', 'goodbids' ) );
		}

		$user_id = goodbids()->auctioneer->decrypt_auctioneer_cookie( $cookie );

		if ( ! $user_id ) {
			return new WP_Error( 'goodbids_invalid_cookie', __( 'The cookie provided was invalid.', 'goodbids' ) );
		}

		$payload_data = [
			'rewardClaimed',
			'rewardUrl',
			'userFreeBids',
			'userId',
			'userTotalBids',
			'userTotalDonated',
			'userReferralUrl',
		];

		$payload = new Payload( $request['id'], $payload_data );
		$payload->set_user_id( $user_id );

		return $this->adjust_response_headers( rest_ensure_response( $payload->get_data() ) );
	}

	/**
	 * Adjust the response headers.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Response|WP_Error $response
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	private function adjust_response_headers( WP_REST_Response|WP_Error $response ): WP_REST_Response|WP_Error {
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$cache_ttl = intval( goodbids()->get_config( 'auctions.rest-api.cache-timeout' ) );

		$response->header( 'Cache-Control', sprintf( 'max-age=%d', $cache_ttl ) );

		return $response;
	}
}
