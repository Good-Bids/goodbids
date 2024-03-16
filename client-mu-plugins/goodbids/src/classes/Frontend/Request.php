<?php
/**
 * GoodBids Support Request object
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Frontend;

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


}
