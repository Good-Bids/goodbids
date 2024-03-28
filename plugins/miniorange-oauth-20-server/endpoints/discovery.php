<?php
/**
 * Summary of discovery
 *
 * @package Discovery
 */

/**
 * Summary of _mo_discovery
 *
 * Handles the discovery endpoint.
 *
 * @param mixed $data client data.
 * @return array
 */
function _mo_discovery( $data ) {

	global $moos_home_url_plus_rest_prefix;

	mo_oauth_server_init();     // checking either server is on or off.
	$client_id = isset( $data['client_id'] ) ? $data['client_id'] : false;
	if ( ! $client_id ) {
		wp_send_json(
			array(
				'error'             => 'invalid_request',
				'error_description' => 'Resource Identifier Missing.',
			),
			400
		);
	}

	return array(
		'request_parameter_supported'           => true,
		'claims_parameter_supported'            => false,
		'issuer'                                => $moos_home_url_plus_rest_prefix . '/moserver/' . $client_id,
		'authorization_endpoint'                => $moos_home_url_plus_rest_prefix . '/moserver/authorize',
		'token_endpoint'                        => $moos_home_url_plus_rest_prefix . '/moserver/token',
		'userinfo_endpoint'                     => $moos_home_url_plus_rest_prefix . '/moserver/resource',
		'scopes_supported'                      => array( 'profile', 'openid', 'email' ),
		'id_token_signing_alg_values_supported' => array( 'HS256', 'RS256' ),
		'response_types_supported'              => array( 'code' ),
		'jwks_uri'                              => $moos_home_url_plus_rest_prefix . '/moserver/' . $client_id . '/.well-known/keys',
		'grant_types_supported'                 => array( 'authorization_code' ),
		'subject_types_supported'               => array( 'public' ),
	);
}
