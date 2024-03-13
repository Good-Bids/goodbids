<?php
/**
 * WordPress Custom API Set Onboarding Step
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Nonprofits\API;

defined( 'ABSPATH' ) || exit;

use WC_REST_Controller;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

/**
 * API Step Controller
 *
 * @since 1.0.0
 */
class Step extends WC_REST_Controller {

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
	protected $rest_base = 'onboarding';

	/**
	 * Route endpoint.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private string $rest_endpoint = 'step';

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
					'callback'            => [ $this, 'set_step' ],
					'permission_callback' => '__return_true',
				],
				'schema' => [ $this, 'get_public_item_schema' ],
			]
		);
	}

	/**
	 * Sets the current step.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function set_step( WP_REST_Request $request ): WP_Error|WP_REST_Response {
		$step = $request->get_param( 'step' );

		if ( ! $step ) {
			return new WP_Error( 'goodbids_missing_required_parameters', __( 'The required `step` parameter was not found.', 'goodbids' ) );
		}

		$steps = goodbids()->onboarding->get_steps();

		// TODO: Not sure if this is necessary.

		// TODO: Check for step validity.
		// Presently, we only allow you to set the step from initial to accessibility checker
		// and from set up payments to complete.
		// React is sending the next step as defined in Onboarding.php

		// TODO: Step the onboarding step.
		// If going from initial to a accessibility checker, just record that initial step is complete.
		// If going from set up payments to complete, record that set up payments was skipped.
		// A nice-to-have would be to return the next step to the client, but right now
		// the client is only waiting for a 200.

		return new WP_REST_Response( $step, 200 );
	}
}
