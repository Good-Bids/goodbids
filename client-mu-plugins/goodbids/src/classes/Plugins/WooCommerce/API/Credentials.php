<?php
/**
 * WooCommerce Custom API Credentials Endpoints
 *
 * Most of this code is copied from WooCommerce's API Keys class.
 * @see `woocommerce/includes/admin/class-wc-admin-api-keys.php`
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

/**
 * API Credentials Controller
 *
 * @since 1.0.0
 */
class Credentials extends WC_REST_Controller {

	/**
	 * Endpoint namespace.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $namespace = 'wc/v3';

	/**
	 * Route base.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $rest_base = 'credentials';

	/**
	 * API Credentials Description
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private string $default_description = 'Autogenerated API Credentials via GoodBids REST API.';

	/**
	 * API Credentials User ID
	 *
	 * @since 1.0.0
	 * @var int
	 */
	private int $default_user_id = 1;

	/**
	 * Register routes.
	 *
	 * @since 1.0.0
	 */
	public function register_routes(): void {
		register_rest_route(
			$this->namespace, '/' . $this->rest_base, [
				[
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'post_credentials' ],
					'permission_callback' => [ $this, 'get_credentials_permissions_check' ],
				],
				'schema' => [ $this, 'get_public_item_schema' ],
			]
		);
	}

	/**
	 * Check whether a given request has permission to read site data.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|boolean
	 */
	public function get_credentials_permissions_check( WP_REST_Request $request ) : WP_Error|bool {
		if ( ! wc_rest_check_manager_permissions( 'settings', 'read' ) ) {
			return new WP_Error(
				'woocommerce_rest_cannot_view',
				__( 'Sorry, you cannot list credentials.', 'goodbids' ),
				[ 'status' => rest_authorization_required_code() ]
			);
		}

		return true;
	}

	/**
	 * Generate new credentials for a site via POST.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function post_credentials( WP_REST_Request $request ): WP_Error|WP_REST_Response {
		if ( ! is_main_site() ) {
			return new WP_Error( 'goodbids_invalid_request', __( 'Sorry, this endpoint is not supported.', 'goodbids' ) );
		}

		$domain = $request->get_param( 'domain' );

		if ( ! $domain ) {
			return new WP_Error( 'goodbids_missing_required_parameters', __( 'The required `domain` parameter was not found.', 'goodbids' ) );
		}

		$site_id = $this->is_valid_site( $domain );

		if ( ! $site_id ) {
			return new WP_Error( 'goodbids_invalid_site', __( 'The site provided was invalid.', 'goodbids' ) );
		}

		$keys_id = $this->lookup_credentials( $site_id );

		if ( $keys_id && ! $this->delete_credentials( $site_id, $keys_id ) ) {
			// TODO: Log Error
			return new WP_Error( 'goodbids_credentials_malfunction', __( 'There was a problem revoking the previous site credentials.', 'goodbids' ) );
		}

		$data = $this->generate_credentials( $site_id );

		if ( ! $data ) {
			// TODO: Log Error
			return new WP_Error( 'goodbids_credentials_malfunction', __( 'There was a problem generating the site credentials.', 'goodbids' ) );
		}

		return rest_ensure_response( $data );
	}

	/**
	 * Validate a domain and return the site ID.
	 *
	 * @since 1.0.0
	 *
	 * @param string $domain
	 *
	 * @return int|false
	 */
	private function is_valid_site( string $domain ): int|false {
		$site_id = get_blog_id_from_url( $domain );

		if ( ! $site_id ) {
			// TODO: Log Error.
			return false;
		}

		if ( ! is_main_site() ) {
			// TODO: Log Error.
			return false;
		}

		// Do not generate credentials for the main site.
		if ( $site_id === get_main_site_id() ) {
			// TODO: Log Error.
			return false;
		}

		return $site_id;
	}

	/**
	 * Check if a site already has API Credentials.
	 *
	 * @since 1.0.0
	 *
	 * @param int $site_id
	 *
	 * @return int|null
	 */
	private function lookup_credentials( int $site_id ): ?int {
		// Do not allow on main site.
		if ( ! is_main_site() ) {
			// TODO: Log error.
			return null;
		}

		return goodbids()->sites->swap(
			function (): ?int {
				global $wpdb;

				$key = $wpdb->get_col(
					$wpdb->prepare(
						"SELECT key_id
						FROM {$wpdb->prefix}woocommerce_api_keys
						WHERE user_id = %d AND description = %s LIMIT 0,1",
						$this->default_user_id,
						$this->default_description
					)
				);

				if ( ! $key ) {
					return null;
				}

				return intval( $key[0] );
			},
			$site_id
		);
	}

	/**
	 * Delete existing API Credentials for a site.
	 *
	 * @since 1.0.0
	 *
	 * @param int $site_id
	 * @param int $key_id
	 *
	 * @return bool
	 */
	private function delete_credentials( int $site_id, int $key_id ): bool {
		// Do not allow in main site.
		if ( ! is_main_site() ) {
			return false;
		}

		return goodbids()->sites->swap(
			function () use ( $key_id ): bool {
				global $wpdb;

				$delete = $wpdb->delete(
					$wpdb->prefix . 'woocommerce_api_keys',
					[ 'key_id' => $key_id ],
					[ '%d' ]
				);

				return 1 === $delete;
			},
			$site_id
		);
	}

	/**
	 * Generate new API credentials for a site.
	 *
	 * @since 1.0.0
	 *
	 * @param int $site_id
	 *
	 * @return ?string[]
	 */
	private function generate_credentials( int $site_id ): ?array {
		// Do not allow on main site.
		if ( ! is_main_site() ) {
			// TODO: Log error.
			return null;
		}

		return goodbids()->sites->swap(
			function (): ?array {
				global $wpdb;

				$consumer_key    = 'ck_' . wc_rand_hash();
				$consumer_secret = 'cs_' . wc_rand_hash();
				$user_id         = $this->default_user_id;
				$description     = $this->default_description;
				$permissions     = 'read_write';

				$data = [
					'user_id'         => $user_id,
					'description'     => $description,
					'permissions'     => $permissions,
					'consumer_key'    => wc_api_hash( $consumer_key ),
					'consumer_secret' => $consumer_secret,
					'truncated_key'   => substr( $consumer_key, -7 ),
				];

				$wpdb->insert(
					$wpdb->prefix . 'woocommerce_api_keys',
					$data,
					[
						'%d',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
					]
				);

				$insert_id = $wpdb->insert_id;

				if ( 0 === $insert_id ) {
					// TODO: Log error.
					return null;
				}

				return [
					'key'    => $consumer_key,
					'secret' => $consumer_secret,
				];
			},
			$site_id
		);
	}
}
