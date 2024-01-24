<?php
/**
 * Rewards Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Auctions;

use WC_Product;

/**
 * Class for Rewards
 *
 * @since 1.0.0
 */
class Rewards {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const ITEM_TYPE = 'rewards';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const REDEEMED_META_KEY = '_goodbids_reward_redeemed';

	/**
	 * Initialize Rewards
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Init Rewards Category.
		$this->init_category();

		// Connect Reward product to Auctions.
		$this->connect_reward_product_on_auction_save();

		// Mark Reward as redeemed after Checkout.
		$this->mark_as_redeemed();
	}

	/**
	 * Initialize Rewards Category
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init_category(): void {
		add_action(
			'init',
			function () {
				$this->get_category_id();
			}
		);
	}

	/**
	 * Retrieve the Rewards category ID, or create it if it doesn't exist.
	 *
	 * @since 1.0.0
	 *
	 * @return ?int
	 */
	public function get_category_id(): ?int {
		$rewards_category = get_term_by( 'slug', self::ITEM_TYPE, 'product_cat' );

		if ( ! $rewards_category ) {
			$rewards_category = wp_insert_term( 'Rewards', 'product_cat' );

			if ( is_wp_error( $rewards_category ) ) {
				// TODO: Log error.
				return null;
			}

			return $rewards_category['term_id'];
		}

		return $rewards_category->term_id;
	}

	/**
	 * Update the Reward Product when an Auction is saved.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function connect_reward_product_on_auction_save(): void {
		add_action(
			'save_post',
			function ( int $post_id ) {
				// Bail if not an Auction and not published.
				if ( wp_is_post_revision( $post_id ) || 'publish' !== get_post_status( $post_id ) || goodbids()->auctions->get_post_type() !== get_post_type( $post_id ) ) {
					return;
				}

				$reward_id = $this->get_product_id( $post_id );

				if ( ! $reward_id ) {
					// TODO: Log error.
					return;
				}

				update_post_meta( $reward_id, Auctions::PRODUCT_AUCTION_META_KEY, $post_id );
			}
		);
	}

	/**
	 * Returns the Add to Cart URL for the Reward Product
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return string
	 */
	public function get_claim_reward_url( int $auction_id = null ): string {
		$reward_product_id = $this->get_product_id( $auction_id );

		if ( ! $reward_product_id ) {
			return '';
		}

		return add_query_arg( 'add-to-cart', $reward_product_id, wc_get_checkout_url() );
	}

	/**
	 * Get the Auction Reward Product ID.
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return int
	 */
	public function get_product_id( int $auction_id = null ): int {
		if ( ! $auction_id ) {
			$auction_id = goodbids()->auctions->get_auction_id();
		}

		return intval( goodbids()->auctions->get_setting( 'auction_product', $auction_id ) );
	}

	/**
	 * Get the Auction Reward Product object.
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return ?WC_Product
	 */
	public function get_product( int $auction_id = null ): ?WC_Product {
		$reward_product_id = $this->get_product_id( $auction_id );

		if ( ! $reward_product_id ) {
			return null;
		}

		return wc_get_product( $reward_product_id );
	}

	/**
	 * Check if reward for an Auction has been redeemed
	 *
	 * @since 1.0.0
	 *
	 * @param ?int $auction_id
	 *
	 * @return bool
	 */
	public function is_redeemed( ?int $auction_id = null ): bool {
		$reward_id = $this->get_product_id( $auction_id );
		return boolval( get_post_meta( $reward_id, self::REDEEMED_META_KEY, true ) );
	}

	/**
	 * Get the Auction ID from the Reward Product ID.
	 *
	 * @since 1.0.0
	 *
	 * @param int $reward_product_id
	 *
	 * @return ?int
	 */
	public function get_auction_id( int $reward_product_id ): ?int {
		return intval( get_post_meta( $reward_product_id, Auctions::PRODUCT_AUCTION_META_KEY, true ) );
	}

	/**
	 * Mark the reward product for an Auction as redeemed after Checkout.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function mark_as_redeemed(): void {
		add_action(
			'woocommerce_thankyou',
			function ( int $order_id ): void {
				if ( is_admin() ) {
					return;
				}

				if ( ! goodbids()->woocommerce->is_reward_order( $order_id ) ) {
					return;
				}

				$auction_id = goodbids()->woocommerce->get_order_auction_id( $order_id );
				$product_id = $this->get_product_id( $auction_id );

				update_post_meta( $product_id, self::REDEEMED_META_KEY, 1 );

				wp_safe_redirect( get_permalink( $auction_id ) );
				exit;
			}
		);
	}
}
