<?php
/**
 * WordPress Custom API Publish Network Site Endpoint
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Network\API;

defined( 'ABSPATH' ) || exit;

use GoodBids\Network\Nonprofit;
use WC_REST_Controller;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

/**
 * API Publish Controller
 *
 * @since 1.0.0
 */
class Publish extends WC_REST_Controller {

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
	protected $rest_base = 'sites';

	/**
	 * Route endpoint.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private string $rest_endpoint = 'publish';

	/**
	 * Register routes.
	 *
	 * @since 1.0.0
	 */
	public function register_routes(): void {
		register_rest_route(
			$this->namespace, '/' . $this->rest_base . '/' . $this->rest_endpoint, [
				[
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'publish_site' ],
					'permission_callback' => '__return_true',
				],
				'schema' => [ $this, 'get_public_item_schema' ],
			]
		);
	}

	/**
	 * Publishes a site.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function publish_site( WP_REST_Request $request ): WP_Error|WP_REST_Response {
		$site_id = intval( $request->get_param( 'site_id' ) );

		if ( ! $site_id ) {
			return new WP_Error( 'goodbids_missing_required_parameters', __( 'The required `site_id` parameter was not found.', 'goodbids' ) );
		}

		$nonprofit = new Nonprofit( $site_id );

		if ( ! $nonprofit->is_valid() ) {
			return new WP_Error( 'goodbids_invalid_site', __( 'The provided `site_id` does not belong to a valid site.', 'goodbids' ) );
		}

		// Make it live!
		if ( ! $nonprofit->set_status( Nonprofit::STATUS_LIVE ) ) {
			return new WP_Error( 'goodbids_status_update_failed', __( 'There was a problem adjusting the Site Status.', 'goodbids' ) );
		}

		return new WP_REST_Response( $site_id, 200 );
	}
}
