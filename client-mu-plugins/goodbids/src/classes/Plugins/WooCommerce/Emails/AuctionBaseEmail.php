<?php
/**
 * Auction Base Email
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce\Emails;

defined( 'ABSPATH' ) || exit;

use WC_Email;

/**
 * Auction Base Email extend the custom WooCommerce email class
 *
 * @since 1.0.0
 * @extends WC_Email
 */
class AuctionBaseEmail extends WC_Email {

	/**
	 * Get site name.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_site_name() {
		return get_bloginfo( 'name' );
	}

	/**
	 * Get the auction title
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_auction_title() {
		return __( '{auction_title}', 'goodbids' );
	}

	/**
	 * Get the auction url
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_auction_url() {
		return __( '{auction_url}', 'goodbids' );
	}

	/**
	 * Get the auction start date
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_auction_start_date() {
		return __( '{auction_start_date}', 'goodbids' );
	}

	/**
	 * Get the auction end date
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_auction_end_date() {
		return __( '{auction_end_date}', 'goodbids' );
	}

	/**
	 * Get the auction bid extension
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_auction_bid_extension() {
		return __( '{auction_bid_extension}', 'goodbids' );
	}


	/**
	 * Get the auction bid increment
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_auction_bid_increment() {
		return __( '{auction_bid_increment}', 'goodbids' );
	}

	/**
	 * Get the auction high bid
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_auction_high_bid() {
		return __( '{auction_high_bid}', 'goodbids' );
	}

	/**
	 * Get the auction goal
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_auction_goal() {
		return __( '{auction_goal}', 'goodbids' );
	}

	/**
	 * Get the auction starting bid
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_auction_starting_bid() {
		return __( '{auction_starting_bid}', 'goodbids' );
	}

	/**
	 * Get the auction reward title
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_auction_reward_title() {
		return __( '{auction_reward_title}', 'goodbids' );
	}

	/**
	 * Get the auction reward type
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_auction_reward_type() {
		return __( '{auction_reward_type}', 'goodbids' );
	}

	/**
	 * Get the auction market value
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_auction_market_value() {
		return __( '{auction_market_value}', 'goodbids' );
	}

	/**
	 * Get user name.
	 *
	 * @since   1.0.0
	 * @param mixed $id
	 * @return string
	 */
	public function get_user_name() {
		if ( ! $this->user_id ) {
			return;
		}

		$user_first_name = get_user_meta( $this->user_id, 'first_name', true );

		return $user_first_name ? $user_first_name : $this->object->user_login;
	}


	/**
	 * Get login URL
	 *
	 * @since   1.0.0
	 * @return string
	 */
	public function get_default_login_url() {
		return __( '{login_url}', 'goodbids' );
	}
}
