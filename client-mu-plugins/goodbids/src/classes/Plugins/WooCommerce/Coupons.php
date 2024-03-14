<?php
/**
 * WooCommerce Coupons Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce;

use GoodBids\Auctions\Auction;
use GoodBids\Auctions\Bids;
use GoodBids\Auctions\Rewards;
use GoodBids\Frontend\Notices;
use GoodBids\Utilities\Log;
use WC_Coupon;
use WC_Product;

/**
 * Class for Coupons Functionality
 *
 * @since 1.0.0
 */
class Coupons {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const REWARD_COUPON_META_KEY = '_goodbids_reward_%d_coupon_id';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const FREE_BID_COUPON_META_KEY = '_goodbids_free_bid_%d_%d_coupon_id';

	/**
	 * @since 1.0.0
	 * @var bool
	 */
	public bool $hyperdb_enabled = true;

	/**
	 * Initialize Coupons
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Fixes a known issue with WP VIP and WooCoupons.
		$this->wp_vip_db_fix();

		// Automatically apply coupons at checkout.
		$this->apply_cart_coupons();
	}

	/**
	 * Generate or retrieve the Reward Coupon Code for this Auction.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 * @param int $reward_id
	 *
	 * @return ?string
	 */
	public function get_reward_coupon_code( int $auction_id, int $reward_id ): ?string {
		$reward = goodbids()->rewards->get_product( $auction_id );

		if ( ! $reward ) {
			return null;
		}

		if ( $reward->get_id() !== $reward_id ) {
			// Reward ID does not match the Auction Reward ID.
			return null;
		}

		$existing = get_post_meta( $reward_id, sprintf( self::REWARD_COUPON_META_KEY, $auction_id ), true );

		if ( $existing ) {
			// Make sure it's still valid.
			if ( wc_get_coupon_id_by_code( $existing ) ) {
				return $existing;
			}
		}

		$coupon_code  = 'GB_REWARD_' . strtoupper( wc_rand_hash() );
		$reward_price = $reward->get_price( 'edit' );
		$description  = sprintf(
			'%s: %d (%s: %d)',
			__( 'Autogenerated Coupon Code for Auction ID', 'goodbids' ),
			esc_html( $auction_id ),
			__( 'Product ID', 'goodbids' ),
			esc_html( $reward_id )
		);

		$this->generate( $coupon_code, $description, $reward_id, $reward_price );

		update_post_meta( $reward_id, sprintf( self::REWARD_COUPON_META_KEY, $auction_id ), $coupon_code );

		return $coupon_code;
	}

	/**
	 * Generate or retrieve the Free Bid Coupon Code for this User.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 * @param int $bid_variation_id
	 *
	 * @return ?string
	 */
	public function get_free_bid_coupon_code( int $auction_id, int $bid_variation_id ): ?string {
		$bid_variation = goodbids()->bids->get_variation( $auction_id );

		if ( ! $bid_variation ) {
			return null;
		}

		if ( $bid_variation->get_id() !== $bid_variation_id ) {
			// Bid Product ID does not match the Auction Bid Product ID.
			return null;
		}

		$existing = get_user_meta( get_current_user_id(), sprintf( self::FREE_BID_COUPON_META_KEY, $auction_id, $bid_variation_id ), true );

		if ( $existing ) {
			// Make sure it's still valid.
			if ( wc_get_coupon_id_by_code( $existing ) ) {
				return $existing;
			}
		}

		$coupon_code  = 'GB_FREEBID_' . strtoupper( wc_rand_hash() );
		$reward_price = $bid_variation->get_price( 'edit' );
		$description  = sprintf(
			'%s: %d',
			__( 'Autogenerated Free Bid Coupon Code for Auction ID', 'goodbids' ),
			esc_html( $auction_id )
		);

		$this->generate( $coupon_code, $description, $bid_variation_id, $reward_price );

		update_user_meta( get_current_user_id(), sprintf( self::FREE_BID_COUPON_META_KEY, $auction_id, $bid_variation_id ), $coupon_code );

		return $coupon_code;
	}

	/**
	 * Generate a new coupon
	 *
	 * @since 1.0.0
	 *
	 * @param string $code
	 * @param string $description
	 * @param int    $product_id
	 * @param float  $amount
	 *
	 * @return void
	 */
	private function generate( string $code, string $description, int $product_id, float $amount ): void {
		$coupon = new WC_Coupon();
		$coupon->set_code( $code ); // Coupon code.
		$coupon->set_description( $description );

		// Restrictions.
		$coupon->set_individual_use( true );
		$coupon->set_usage_limit_per_user( 1 );
		$coupon->set_usage_limit( 1 );
		$coupon->set_limit_usage_to_x_items( 1 ); // Limit to 1 item.
		$coupon->set_email_restrictions( goodbids()->users->get_emails() ); // Restrict by user email(s).
		$coupon->set_product_ids( [ $product_id ] ); // Restrict to a specific Product type.

		// Amount.
		$coupon->set_discount_type( 'percent' );
		$coupon->set_amount( 100 ); // 100% Discount.
		$coupon->set_maximum_amount( $amount ); // Additional price restriction.

		$coupon->save();
	}

	/**
	 * Apply Free Bid and Reward Coupon to cart, if applicable.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function apply_cart_coupons(): void {
		add_action(
			'goodbids_place_bid',
			function ( int $auction_id, int $product_id, int $variation_id ): void {
				if ( empty( $_REQUEST[ Bids::USE_FREE_BID_PARAM ] ) ) { // phpcs:ignore
					return;
				}

				$auction = goodbids()->auctions->get( $auction_id );

				if ( ! $auction->are_free_bids_allowed() ) {
					goodbids()->notices->add_notice( Notices::FREE_BIDS_NOT_ELIGIBLE );
					return;
				}

				if ( ! goodbids()->users->get_available_free_bid_count() ) {
					goodbids()->notices->add_notice( Notices::NO_AVAILABLE_FREE_BIDS );
					return;
				}

				$product     = wc_get_product( $variation_id );
				$coupon_code = $this->get_free_bid_coupon_code( $auction_id, $product->get_id() );

				$this->apply_the_coupon_code( $coupon_code, $auction, 'goodbids_place_bid_redirect' );
			},
			10,
			3
		);

		add_action(
			'goodbids_claim_reward',
			function ( int $auction_id, int $reward_id ): void {
				$auction     = goodbids()->auctions->get( $auction_id );
				$product     = wc_get_product( $reward_id );
				$coupon_code = $this->get_reward_coupon_code( $auction_id, $product->get_id() );

				$this->apply_the_coupon_code( $coupon_code, $auction, 'goodbids_claim_reward_redirect' );
			},
			10,
			2
		);
	}

	/**
	 * Apply the WooCommerce Coupon
	 *
	 * @since 1.0.0
	 *
	 * @param string $coupon_code
	 * @param Auction $auction
	 * @param string $redirect_hook
	 *
	 * @return void
	 */
	private function apply_the_coupon_code( string $coupon_code, Auction $auction, string $redirect_hook ): void {
		if ( ! $coupon_code ) {
			goodbids()->notices->add_notice( Notices::GET_REWARD_COUPON_ERROR );
			add_filter(
				$redirect_hook,
				function ( $redirect_url ) use ( $auction ) {
					return $auction->get_url();
				}
			);
			return;
		}

		// Only apply Coupon once.
		if (  WC()->cart->has_discount( $coupon_code ) ) {
			return;
		}

		// Apply the Coupon.
		if ( WC()->cart->add_discount( $coupon_code ) ) {
			return;
		}

		Log::error( 'There was a problem adding the discount coupon', compact( 'coupon_code' ) );

		goodbids()->notices->add_notice( Notices::APPLY_COUPON_ERROR );

		add_filter(
			$redirect_hook,
			function ( $redirect_url ) use ( $auction ) {
				return $auction->get_url();
			}
		);
	}

	/**
	 * From WP VIP Support:
	 * This fixes an error that is a known issue of WP VIP (specifically an incompatibility between HyperDB and Woo Coupons).
	 *
	 * While we're still waiting on an official fix here, this filter is being used as a workaround where at least two other scenarios the errors have appeared.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function wp_vip_db_fix(): void {
		add_filter(
			'query',
			function ( string $query ): string {
				if ( is_main_site() || is_admin() || ! $this->hyperdb_enabled ) {
					return $query;
				}

				if ( ! str_contains( $query, 'SELECT' ) || ! str_contains( $query, 'FOR UPDATE' ) ) {
					return $query;
				}

				/** @var \hyperdb $wpdb */
				global $wpdb;

				if ( ! method_exists( $wpdb, 'send_reads_to_master' ) ) {
					return $query;
				}

				$wpdb->send_reads_to_master();

				return $query;
			}
		);
	}
}
