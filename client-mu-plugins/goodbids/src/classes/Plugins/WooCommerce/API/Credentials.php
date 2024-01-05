<?php
/**
 * WooCommerce Custom API Credentials Endpoints
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce\API;

defined( 'ABSPATH' ) || exit;

use WC_REST_Controller;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

class Credentials extends WC_REST_Controller {

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'wc/v3';

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'credentials';

	/**
	 * Register routes.
	 *
	 * @since 1.0.0
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace, '/' . $this->rest_base, [
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_credentials' ),
					'permission_callback' => array( $this, 'get_credentials_permissions_check' ),
				],
				'schema' => [ $this, 'get_public_item_schema' ],
			]
		);
	}

	/**
	 * Check whether a given request has permission to read site data.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function get_credentials_permissions_check( WP_REST_Request $request ) : WP_Error|bool {
		if ( ! wc_rest_check_manager_permissions( 'settings', 'read' ) ) {
			return new WP_Error( 'woocommerce_rest_cannot_view', __( 'Sorry, you cannot list credentials.', 'woocommerce' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return true;
	}

	public function get_credentials(): WP_Error|WP_REST_Response {
		$data = [ 'credentials' => 'This is the Nonprofit site.' ];

		if ( is_main_site() ) {
			$data = [ 'credentials' => 'This is the main site.' ];
		}

		return rest_ensure_response( $data );
	}

}
