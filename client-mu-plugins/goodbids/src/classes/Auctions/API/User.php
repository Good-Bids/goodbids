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
					'permission_callback' => [ $this, 'get_credentials_permissions_check' ],
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
		$cookie = $request->get_param( 'user_cookie' );

		if ( ! $cookie ) {
			return new WP_Error( 'goodbids_missing_required_parameters', __( 'The required `user_cookie` parameter was not found.', 'goodbids' ) );
		}

		$user_id = goodbids()->auctioneer->decrypt_auctioneer_cookie( $cookie );

		if ( ! $user_id ) {
			return new WP_Error( 'goodbids_invalid_cookie', __( 'The cookie provided was invalid.', 'goodbids' ) );
		}

		$payload_data = [
			'isLastBidder',
			'rewardUrl',
			'userFreeBids',
			'userTotalBids',
			'userTotalDonated',
		];

		$payload = new Payload( $request['id'], $payload_data );
		$payload->set_user_id( $user_id );

		return rest_ensure_response( $payload->get_data() );
	}

	/**
	 * Check whether a given request has permission to read Auction User data.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|boolean
	 */
	public function get_credentials_permissions_check( WP_REST_Request $request ) : WP_Error|bool {
		if ( ! wc_rest_check_post_permissions( goodbids()->auctions->get_post_type(), 'read', $request['id'] ) ) {
			return new WP_Error(
				'woocommerce_rest_cannot_view',
				__( 'Sorry, you cannot view this Auction.', 'goodbids' ),
				[ 'status' => rest_authorization_required_code() ]
			);
		}

		return true;
	}
}
