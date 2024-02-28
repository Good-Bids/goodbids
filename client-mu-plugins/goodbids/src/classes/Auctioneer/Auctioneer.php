<?php
/**
 * The Auctioneer functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Auctioneer;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use GoodBids\Auctioneer\Endpoints\Auctions;
use GoodBids\Utilities\Cookies;
use GoodBids\Utilities\Log;

/**
 * Class for Auctioneer, our Node.js server
 *
 * @since 1.0.0
 */
class Auctioneer {

	/**
	 * @since 1.0.0
	 */
	const AUCTIONEER_COOKIE = 'goodbids_auctioneer_session';

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
	 * @var ?string
	 */
	private ?string $api_key = null;

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
		if ( ! $this->configure_api_key() ) {
			return;
		}

		$this->init();
	}

	/**
	 * Set up the URL to use for the API.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function configure_url(): bool {
		// Environment is set on init. See set_environment().
		$environments = goodbids()->get_config( 'vip-constants.auctioneer.urls' );

		if ( ! $environments || empty( $environments[ $this->environment ] ) ) {
			goodbids()->utilities->display_admin_error( __( 'Missing Auctioneer URL constants config.', 'goodbids' ) );
			return false;
		}

		$this->url = vip_get_env_var( $environments[ $this->environment ], null );

		// Abort if missing environment variable.
		if ( ! $this->url ) {
			goodbids()->utilities->display_admin_error( __( 'Missing Auctioneer URL environment variables.', 'goodbids' ) );
			return false;
		}

		return true;
	}

	/**
	 * Sets the API Key to be used with requests to the Auctioneer.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function configure_api_key(): bool {
		$env_var = goodbids()->get_config( 'vip-constants.auctioneer.api-key' );

		if ( ! $env_var ) {
			goodbids()->utilities->display_admin_error( __( 'Missing Auctioneer API Key constants config.', 'goodbids' ) );
			return false;
		}

		$this->api_key = vip_get_env_var( $env_var, null );

		// Abort if missing environment variable.
		if ( ! $this->api_key ) {
			goodbids()->utilities->display_admin_error( __( 'Missing Auctioneer API Key environment variable.', 'goodbids' ) );
			return false;
		}

		return true;
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

		// Set the environment based on settings.
		$this->set_environment();

		// Create a custom session cookie readable by Auctioneer.
		$this->create_session_cookie();

		// Destroy session cookie on log out.
		$this->destroy_session_cookie();

		// Tell Auctioneer when Auction has started.
		$this->send_auctioneer_open_event();

		// Tell Auctioneer when Auction has closed.
		$this->send_auctioneer_close_event();

		$this->initialized = true;
	}

	/**
	 * Determine which environment to use.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function set_environment(): void {
		add_action(
			'init',
			function () {
				$environment = goodbids()->get_config( 'auctioneer.environment' );

				// Validate Setting.
				if ( ! in_array( $environment, [ 'local', 'develop', 'staging', 'production' ], true ) ) {
					return;
				}

				$this->environment = $environment;
			}
		);
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
					'Content-Type'  => 'application/json',
					'Authorization' => 'Bearer ' . $this->api_key,
				],
				'body'    => wp_json_encode( $params ),
			]
		);

		Log::debug( '[Auctioneer] Response', compact( 'response' ) );
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
	 * Get the last response code.
	 *
	 * @since 1.0.0
	 *
	 * @return ?int
	 */
	public function get_last_response_code(): ?int {
		if ( ! $this->get_last_response() ) {
			return null;
		}

		return wp_remote_retrieve_response_code( $this->get_last_response() );
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
	public function is_invalid_response( mixed $response = null ): bool {
		if ( null === $response ) {
			$response = $this->get_last_response();
		}

		if ( is_wp_error( $response ) ) {
			Log::debug( '[Auctioneer] Invalid Response: ' . $response->get_error_message(), compact( 'response' ) );
			return true;
		}

		if ( ! is_array( $response ) || empty( $response ) ) {
			Log::warning( '[Auctioneer] Invalid or Empty Response.', compact( 'response' ) );
			return true;
		}

		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			Log::debug( '[Auctioneer] Bad Response: ' . wp_strip_all_tags( $this->get_response_message( $response ) ), compact( 'response' ) );
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
			return wp_remote_retrieve_body( $response );
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

	/**
	 * Generate a User cookie readable by Auctioneer.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function create_session_cookie(): void {
		add_action(
			'set_logged_in_cookie',
			function ( $cookie, $expire, $expiration, $user_id ) {
				$session_cookie = $this->generate_auctioneer_cookie( $user_id );
				Cookies::set( self::AUCTIONEER_COOKIE, $session_cookie, $expire, false );
			},
			10,
			4
		);
	}

	/**
	 * Generates an encrypted cookie for Auctioneer
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id
	 *
	 * @return string
	 */
	private function generate_auctioneer_cookie( int $user_id ): string {
		$payload = [
			'userId' => $user_id,
		];

		return JWT::encode( $payload, $this->api_key, 'HS256' );
	}

	/**
	 * Decrypts the encrypted user cookie for Auctioneer, returns the User ID.
	 *
	 * @since 1.0.0
	 *
	 * @param string $cookie
	 *
	 * @return int
	 */
	public function decrypt_auctioneer_cookie( string $cookie ): int {
		$data = JWT::decode( $cookie, new Key( $this->api_key, 'HS256' ) );
		return $data?->userId ?: 0;
	}

	/**
	 * Destroy session cookie on log out.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function destroy_session_cookie(): void {
		add_action(
			'clear_auth_cookie',
			function() {
				Cookies::clear( self::AUCTIONEER_COOKIE );
			}
		);
	}

	/**
	 * Gets the URL for the Auctioneer.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_url(): string {
		if ( ! $this->url ) {
			$this->configure_url();
		}

		return $this->url;
	}

	/**
	 * Sends an event to Auctioneer to start an auction.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function send_auctioneer_open_event(): void {
		add_action(
			'goodbids_auction_start',
			fn ( int $auction_id ) => $this->auctions->start( $auction_id ),
			200
		);
	}

	/**
	 * Sends an event to Auctioneer to close an auction.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function send_auctioneer_close_event(): void {
		add_action(
			'goodbids_auction_end',
			fn ( int $auction_id ) => $this->auctions->end( $auction_id ),
			200
		);
	}
}
