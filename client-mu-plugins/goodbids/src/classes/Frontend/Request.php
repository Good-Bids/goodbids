<?php
/**
 * GoodBids Support Request object
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Frontend;

use GoodBids\Users\Bidder;
use WP_Post;

/**
 * Support Request Class
 *
 * @since 1.0.0
 */
class Request {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const TYPE_BID = 'Bid';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const TYPE_REWARD = 'Reward';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const TYPE_AUCTION = 'Auction';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const TYPE_OTHER = 'Other';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const FIELD_TYPE = '_type';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const FIELD_AUCTION = '_auction';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const FIELD_BID = '_bid';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const FIELD_REWARD = '_reward';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const FIELD_NATURE = '_nature';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const FIELD_REQUEST = '_request';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const FIELD_USER_ID = '_user_id';

	/**
	 * @since 1.0.0
	 * @var int
	 */
	private int $request_id;

	/**
	 * @since 1.0.0
	 * @var ?WP_Post
	 */
	private ?WP_Post $post;

	/**
	 * @since 1.0.0
	 */
	public function __construct( int $request_id ) {
		if ( get_post_type( $request_id ) !== SupportRequest::POST_TYPE ) {
			_doing_it_wrong( __METHOD__, 'The post ID provided is not a support request type', '1.0.0' );
			return;
		}

		$this->request_id = $request_id;
		$this->post       = get_post( $this->request_id );
	}

	/**
	 * Get the Request ID
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_id(): int {
		return $this->request_id;
	}

	/**
	 * Check to see if the request is valid.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_valid(): bool {
		return ! is_null( $this->post );
	}


	public function get_user_id(): ?int {
		$user_id = get_post_meta( $this->get_id(), self::FIELD_USER_ID, true );
		if ( ! $user_id ) {
			return null;
		}

		return $user_id;
	}

	public function get_user(): ?Bidder {
		$user_id = $this->get_user_id();
		if ( ! $user_id ) {
			return null;
		}

		return new Bidder( $user_id );
	}

	public function get_field( string $key ): mixed {
		return get_post_meta( $this->get_id(), $key, true );
	}

	public function get_auction_html(): string {
		$auction = $this->get_field( self::FIELD_AUCTION );
		if ( ! $auction ) {
			return '';
		}

		list( $site_id, $auction_id ) = array_map( 'intval', explode( '|', $auction ) );

		return goodbids()->sites->swap(
			function () use ( $auction_id ) {
				return sprintf(
					'<a href="%s">%s</a> (<a href="%s" target="_blank">%s</a>)',
					esc_url( get_edit_post_link( $auction_id ) ),
					esc_html( get_the_title( $auction_id ) ),
					esc_url( get_permalink( $auction_id ) ),
					esc_html__( 'View', 'goodbids' )
				);
			},
			$site_id
		);
	}

	public function get_user_html(): string {
		$user = $this->get_user();
		if ( ! $user ) {
			return '';
		}

		return sprintf(
			'<a href="%s">%s</a>',
			esc_url( $user->get_edit_url() ),
			esc_html( $user->get_username() )
		);
	}
}
