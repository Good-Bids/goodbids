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
	 * Defines the environment variables for each URL.
	 *
	 * @since 1.0.0
	 * @var string[]
	 */
	const ENVIRONMENTS = [
		'local'      => 'GOODBIDS_AUCTIONEER_URL_LOCAL',
		'develop'    => 'GOODBIDS_AUCTIONEER_URL_DEVELOP',
		'production' => 'GOODBIDS_AUCTIONEER_URL_PRODUCTION',
	];

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
	 * Initialize Auctioneer
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->url = vip_get_env_var( self::ENVIRONMENTS[ $this->environment ], null );

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
			});

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
	private function request( string $endpoint, array $params = [], string $method = 'GET' ): ?array {
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

		if ( ! is_array( $response ) ) {
			return null;
		}

		return $response;
	}

	/**
	 * Trigger an Auction Start event for an Auction
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return array|true
	 */
	public function auction_start( int $auction_id ): array|bool {
		$bid_product = goodbids()->auctions->get_bid_product( $auction_id );

		$payload = [
			'id'                => goodbids()->auctions->get_guid( $auction_id ),
			'status'            => strtolower( goodbids()->auctions->get_status( $auction_id ) ),
//			'startTime'         => goodbids()->auctions->get_start_date_time( $auction_id, 'c ),
			'endTime'           => goodbids()->auctions->get_end_date_time( $auction_id, 'c' ),
			'totalBids'         => goodbids()->auctions->get_bid_count( $auction_id ),
			'totalRaised'       => goodbids()->auctions->get_total_raised( $auction_id ),
			'currentBid'        => floatval( $bid_product?->get_price( 'edit' ) ),
			'lastBid'           => goodbids()->auctions->get_last_bid( $auction_id )?->get_total(),
			'lastBidder'        => null, // user id
			'availableFreeBids' => 0,
			'requestTime'       => current_datetime()->format( 'c' ),
//			'viewers'           => 0,
		];

		$response = $this->request( 'auctions', $payload, 'POST' );

		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			// TODO: Log Response
			error_log( '[GB] ' . wp_strip_all_tags( $this->get_response_message( $response ) ) );
			return $response;
		}

		$message  = $this->get_response_message_raw( $response );
		$expected = 'Auction stored';

		if ( $message !== $expected ) {
			// TODO: Log error.
			error_log( '[GB] ' . $message );
			return $response;
		}

		return true;
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
