<?php
/**
 * The Auctioneer functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Auctioneer;

/**
 * Class for Auctioneer, our Node.js server
 *
 * @since 1.0.0
 */
class Auctioneer {

	/**
	 * Defines the environment to use.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private string $environment = 'develop';

	/**
	 * @since 1.0.0
	 * @var ?string
	 */
	private ?string $url = null;

	/**
	 * @since 1.0.0
	 * @var bool
	 */
	private bool $initialized = false;

	/**
	 * Store the last response
	 *
	 * @since 1.0.0
	 * @var ?mixed
	 */
	private mixed $last_response = null;

	/**
	 * Auctions Endpoint
	 *
	 * @since 1.0.0
	 * @var ?Auctions
	 */
	public ?Auctions $auctions = null;

	/**
	 * Initialize Auctioneer
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$environments = goodbids()->get_config( 'vip-constants.auctioneer' );

		if ( ! $environments || empty( $environments[ $this->environment ] ) ) {
			add_action(
				'admin_notices',
				function() {
					printf(
						'<div class="notice notice-error is-dismissible">
						<p>%s</p>
					</div>',
						esc_html__( 'Missing Auctioneer constants config.', 'goodbids' )
					);
				}
			);

			return;
		}

		$this->url = vip_get_env_var( $environments[ $this->environment ], null );

		// Abort if missing environment variable.
		if ( ! $this->url ) {
			add_action(
				'admin_notices',
				function() {
					printf(
						'<div class="notice notice-error is-dismissible">
							<p>%s</p>
						</div>',
						esc_html__( 'Missing Auctioneer environment variables.', 'goodbids' )
					);
				}
			);

			return;
		}

		$this->init();
	}

	/**
	 * Run initialization tasks
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init(): void {
		// Init Auctions Endpoint.
		$this->auctions = new Auctions();

		$this->initialized = true;
	}

	/**
	 * Make a request to an API endpoint
	 *
	 * @since 1.0.0
	 *
	 * @param string $endpoint
	 * @param array $params
	 * @param string $method
	 *
	 * @return ?array
	 */
	public function request( string $endpoint, array $params = [], string $method = 'GET' ): ?array {
		if ( ! $this->initialized ) {
			return null;
		}

		$url      = trailingslashit( $this->url ) . $endpoint;
		$response = wp_remote_request(
			$url,
			[
				'method'  => $method,
				'headers' => [
					'Content-Type' => 'application/json',
				],
				'body'    => wp_json_encode( $params ),
			]
		);

		// TODO: Log Response.
		$this->last_response = $response;

		if ( $this->is_invalid_response( $response ) ) {
			return null;
		}

		return $response;
	}

	/**
	 * Get the last response.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed
	 */
	public function get_last_response(): mixed {
		// TODO: Maybe refactor, or remove once we begin logging the requests/responses.
		return $this->last_response;
	}

	/**
	 * Check if the response if valid.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $response
	 *
	 * @return bool
	 */
	public function is_invalid_response( mixed $response ): bool {
		if ( is_wp_error( $response ) ) {
			// TODO: Log error.
			error_log( '[GB] ' . $response->get_error_message() );
			return true;
		}

		if ( ! is_array( $response ) || empty( $response ) ) {
			// TODO: Log error.
			return true;
		}

		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			// TODO: Log Errors.
			error_log( '[GB] ' . wp_strip_all_tags( $this->get_response_message( $response ) ) );
			return true;
		}

		return false;
	}

	/**
	 * Get the message from the response array.
	 *
	 * @since 1.0.0
	 *
	 * @param array $response
	 *
	 * @return string
	 */
	public function get_response_message( array $response ): string {
		$data    = $this->get_response_json( $response );
		$msg_raw = $this->get_response_message_raw( $response );

		if ( ! $msg_raw ) {
			return '';
		}

		$message = sprintf(
			'<p><strong>%s:</strong></p>',
			esc_html( $msg_raw )
		);

		if ( ! empty( $data['details'] ) ) {
			$message .= sprintf(
				'<ul><li>%s</li></ul>',
				is_array( $data['details'] ) ? wp_kses_post( implode( '</li><li>', $data['details'] ) ) : esc_html( $data['details'] )
			);
		}

		return $message;
	}

	/**
	 * Get the raw value for the message from the response array.
	 *
	 * @since 1.0.0
	 *
	 * @param array $response
	 *
	 * @return string
	 */
	public function get_response_message_raw( array $response ): string {
		$data = $this->get_response_json( $response );

		if ( empty( $data['message'] ) ) {
			return '';
		}

		return $data['message'];
	}

	/**
	 * Get the Response JSON as an array
	 *
	 * @since 1.0.0
	 *
	 * @param array $response
	 *
	 * @return ?array
	 */
	public function get_response_json( array $response ): ?array {
		$body = wp_remote_retrieve_body( $response );

		if ( ! $body ) {
			return null;
		}

		$data = json_decode( $body, true );

		if ( ! $data ) {
			return null;
		}

		return $data;
	}
}
