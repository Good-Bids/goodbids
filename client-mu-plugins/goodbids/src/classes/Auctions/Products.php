<?php
/**
 * Auction Products Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Auctions;

use GoodBids\Utilities\Log;

/**
 * Auction Products
 *
 * @since 1.0.0
 */
class Products {

	/**
	 * Initialize Auction Products
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Prevent access to Bid/Reward products.
		$this->prevent_access_to_products();

		// Update Bid Product when Auction is updated.
		$this->update_bid_product_on_auction_update();

		// Sets a default image
		$this->set_default_feature_image();
	}

	/**
	 * Disable access to Bid product singular product page.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function prevent_access_to_products(): void {
		add_action(
			'template_redirect',
			function (): void {
				if ( ! is_singular( 'product' ) ) {
					return;
				}

				$product_id = get_the_ID();

				if ( ! $this->get_type( $product_id ) ) {
					return;
				}

				$auction_id = $this->get_auction_id_from_product( $product_id );

				if ( ! $auction_id ) {
					wp_safe_redirect( home_url() );
					exit;
				}

				wp_safe_redirect( get_permalink( $auction_id ), 301 );
				exit;
			}
		);
	}

	/**
	 * Get the Auction ID from the Product ID.
	 *
	 * @since 1.0.0
	 *
	 * @param int $product_id
	 *
	 * @return ?int
	 */
	public function get_auction_id_from_product( int $product_id ): ?int {
		$type = $this->get_product_type( $product_id );

		if ( Bids::ITEM_TYPE === $type ) {
			return $this->bids->get_auction_id( $product_id );
		}

		if ( Rewards::ITEM_TYPE === $type ) {
			return $this->rewards->get_auction_id( $product_id );
		}

		return null;
	}

	/**
	 * Get the Product Type
	 *
	 * @since 1.0.0
	 *
	 * @param int $product_id
	 *
	 * @return ?string
	 */
	public function get_type( int $product_id ): ?string {
		if ( ! in_array( get_post_type( $product_id ), [ 'product', 'product_variation' ], true ) ) {
			return null;
		}

		$lookup_id  = $this->get_parent_product_id( $product_id );
		$valid      = [ Bids::ITEM_TYPE, Rewards::ITEM_TYPE ];
		$categories = get_the_terms( $lookup_id, 'product_cat' );

		if ( ! is_array( $categories ) ) {
			return null;
		}

		foreach ( $categories as $category ) {
			if ( in_array( $category->slug, $valid, true ) ) {
				return $category->slug;
			}
		}

		return null;
	}

	/**
	 * Convert a product or variation to the parent product ID.
	 *
	 * @since 1.0.0
	 *
	 * @param int $product_id
	 *
	 * @return ?int
	 */
	public function get_parent_product_id( int $product_id ): ?int {
		if ( ! in_array( get_post_type( $product_id ), [ 'product', 'product_variation' ], true ) ) {
			return $product_id;
		}

		$product = wc_get_product( $product_id );
		return $product?->get_parent_id() ?: $product_id;
	}

	/**
	 * Update the Bid Product when an Auction is updated.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function update_bid_product_on_auction_update(): void {
		add_action(
			'save_post',
			function ( int $post_id ) {
				// Bail if not an Auction and not published.
				if ( ! $post_id || wp_is_post_revision( $post_id ) || 'publish' !== get_post_status( $post_id ) || $this->get_post_type() !== get_post_type( $post_id ) || wp_doing_ajax() ) {
					return;
				}

				// Bail if the Auction doesn't have a Bid product.
				if ( ! $this->has_bid_product( $post_id ) ) {
					Log::error( 'Auction does not have Bid Product', compact( 'post_id' ) );
					return;
				}

				// Only update if Auction hasn't started.
				if ( $this->has_started( $post_id ) ) {
					Log::warning( 'Attempted to update Auction Bid Product after Auction has started', compact( 'post_id' ) );
					return;
				}

				// Update the Bid product variation.
				$bid_variation = goodbids()->auctions->bids->get_variation( $post_id );
				$starting_bid  = $this->calculate_starting_bid( $post_id );
				$bid_price     = intval( $bid_variation->get_price( 'edit' ) );

				if ( $starting_bid && $bid_price !== $starting_bid ) {
					// Update post meta.
					update_post_meta( $bid_variation->get_id(), '_price', $starting_bid );

					// Update current instance.
					$bid_variation->set_price( $starting_bid );
					$bid_variation->save();
				}
			}
		);
	}

	/**
	 * Set the default feature image for Auction
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function set_default_feature_image(): void {
		add_filter(
			'post_thumbnail_html',
			function ( string $html, int $post_id ) {
				if ( ! is_post_type_archive( $this->get_post_type() ) ) {
					return $html;
				}

				// If the auction has a featured image
				if ( has_post_thumbnail( $post_id ) ) {
					return $html;
				}

				// Default to the WooCommerce featured image or WooCommerce default image
				$reward = $this->rewards->get_product( $post_id );

				if ( ! $reward ) {
					return $html;
				}

				return $reward->get_image();
			},
			10,
			2
		);
	}
}
