<?php
/**
 * WooCommerce Cart Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce;

use GoodBids\Auctions\Bids;
use GoodBids\Frontend\Notices;
use GoodBids\Plugins\WooCommerce;
use WC_Order_Item_Product;
use WC_Product;

/**
 * Class for Cart Methods
 *
 * @since 1.0.0
 */
class Cart {

	/**
	 * Initialize Cart
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Track Auction info while products are in the cart.
		$this->store_auction_info_in_cart();

		// Empty cart before adding a Bid or Reward product.
		$this->auto_empty_cart();

		// Add restrictions to cart products.
		$this->restrict_products();

		// Clear query params after adding bids and reward products to cart.
		$this->redirect_after_add_to_cart();
	}

	/**
	 * Store the Auction ID in the Cart Item Meta.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function store_auction_info_in_cart(): void {
		add_action(
			'woocommerce_checkout_create_order_line_item',
			function ( WC_Order_Item_Product $item ): void {

				if ( ! $item->get_product_id() ) {
					return;
				}

				$auction_id   = goodbids()->auctions->get_auction_id_from_product_id( $item->get_product_id() );
				$product_type = goodbids()->auctions->get_product_type( $item->get_product_id() );

				if ( ! $auction_id || ! $product_type ) {
					return;
				}

				$item->update_meta_data( WooCommerce::AUCTION_META_KEY, $auction_id );
				$item->update_meta_data( WooCommerce::TYPE_META_KEY, $product_type );
			}
		);
	}

	/**
	 * Empty cart before adding a Bid or Reward product.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function auto_empty_cart(): void {
		add_filter(
			'woocommerce_add_to_cart_handler',
			function ( $handler, $product ) {
				if ( ! goodbids()->auctions->get_product_type( $product->get_id() ) ) {
					return $handler;
				}

				WC()->cart->empty_cart();

				return $handler;
			},
			10,
			2
		);
	}

	/**
	 * Restrict our products to authenticated users.
	 * Make sure Reward Product is only available to the Auction Winner.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function restrict_products(): void {
		add_filter(
			'woocommerce_add_to_cart_validation',
			function ( $passed, $product_id ) {
				if ( ! goodbids()->auctions->get_product_type( $product_id ) ) {
					return $passed;
				}

				// Auth check.
				$auth_url = wc_get_page_permalink( 'myaccount' );

				if ( ! is_user_logged_in() ) {
					// Redirect to login page with Add to Cart as the redirect.
					$checkout_url = wc_get_page_permalink( 'checkout' );
					$redirect_to  = add_query_arg( 'add-to-cart', $product_id, $checkout_url );

					$args     = [
						'redirect-to' => urlencode( $redirect_to ),
						'gb-notice'   => Notices::NOT_AUTHENTICATED,
					];
					$redirect = add_query_arg( $args, $auth_url );

					wp_safe_redirect( $redirect );
					exit;
				}

				$auction_id = goodbids()->auctions->get_auction_id_from_product_id( $product_id );

				if ( ! $auction_id ) {
					$redirect = add_query_arg( 'gb-notice', Notices::AUCTION_NOT_FOUND, $auth_url );
					wp_safe_redirect( $redirect );
					exit;
				}

				$auction_url = get_permalink( $auction_id );

				if ( Bids::ITEM_TYPE === goodbids()->auctions->get_product_type( $product_id ) ) {
					if ( ! goodbids()->auctions->has_started( $auction_id ) ) {
						$redirect = add_query_arg( 'gb-notice', Notices::AUCTION_NOT_STARTED, $auction_url );
						wp_safe_redirect( $redirect );
						exit;
					}

					if ( goodbids()->auctions->has_ended( $auction_id ) ) {
						$redirect = add_query_arg( 'gb-notice', Notices::AUCTION_HAS_ENDED, $auction_url );
						wp_safe_redirect( $redirect );
						exit;
					}

					// Nothing left to do for bid items.
					return $passed;
				}

				/**
				 * Reward Items
				 */
				if ( ! goodbids()->auctions->has_ended( $auction_id ) ) {
					$redirect = add_query_arg( 'gb-notice', Notices::AUCTION_NOT_ENDED, $auction_url );
					wp_safe_redirect( $redirect );
					exit;
				}

				if ( ! goodbids()->auctions->is_current_user_winner( $auction_id ) ) {
					$redirect = add_query_arg( 'gb-notice', Notices::NOT_AUCTION_WINNER, $auction_url );
					wp_safe_redirect( $redirect );
					exit;
				}

				if ( goodbids()->auctions->rewards->is_redeemed( $auction_id ) ) {
					$redirect = add_query_arg( 'gb-notice', Notices::REWARD_ALREADY_REDEEMED, $auction_url );
					wp_safe_redirect( $redirect );
					exit;
				}

				return $passed;
			},
			10,
			2
		);
	}

	/**
	 * Redirect after adding bids and reward products to cart
	 * to remove the add-to-cart and use-free-bid url parameters.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function redirect_after_add_to_cart(): void {
		// No need to redirect.
		if ( ! isset( $_REQUEST['add-to-cart'] ) && ! isset( $_REQUEST['use-free-bid'] ) ) { // phpcs:ignore
			return;
		}

		add_filter(
			'woocommerce_add_to_cart_redirect',
			function ( string $url, ?WC_Product $product ): string {
				if ( wp_doing_ajax() ) {
					return $url;
				}

				$product = goodbids()->woocommerce->get_add_to_cart_product( $product );

				if ( ! $product ) {
					return $url;
				}

				if ( ! goodbids()->auctions->get_product_type( $product->get_id() ) ) {
					return $url;
				}

				return wc_get_checkout_url();
			},
			15,
			2
		);

		// Fallback for when the above filter does not work.
		add_action(
			'template_redirect',
			function (): void {
				$product = goodbids()->woocommerce->get_add_to_cart_product();

				if ( ! $product ) {
					return;
				}

				if ( ! goodbids()->auctions->get_product_type( $product->get_id() ) ) {
					return;
				}

				wp_safe_redirect( wc_get_checkout_url() );
				exit;
			},
			15,
			2
		);
	}
}
