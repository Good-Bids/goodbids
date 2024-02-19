<?php
/**
 * WooCommerce Cart Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce;

use GoodBids\Auctions\Auction;
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

		// Prevent users from accessing the Cart page.
		$this->disable_cart_access();

		// Clear query params after adding bids and reward products to cart.
		$this->redirect_after_add_to_cart();

		// Disable the add to cart message.
		$this->disable_add_to_cart_message();
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

				$auction_id   = goodbids()->products->get_auction_id_from_product( $item->get_product_id() );
				$product_type = goodbids()->products->get_type( $item->get_product_id() );

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
				if ( ! goodbids()->products->get_type( $product->get_id() ) ) {
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
				if ( ! goodbids()->products->get_type( $product_id ) ) {
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

				$auction_id = goodbids()->products->get_auction_id_from_product( $product_id );

				if ( ! $auction_id ) {
					$redirect = add_query_arg( 'gb-notice', Notices::AUCTION_NOT_FOUND, $auth_url );
					wp_safe_redirect( $redirect );
					exit;
				}

				$auction = goodbids()->auctions->get( $auction_id );

				$auction_url = $auction->get_url();

				if ( Bids::ITEM_TYPE === goodbids()->products->get_type( $product_id ) ) {
					if ( ! $auction->has_started() ) {
						$redirect = add_query_arg( 'gb-notice', Notices::AUCTION_NOT_STARTED, $auction_url );
						wp_safe_redirect( $redirect );
						exit;
					}

					if ( $auction->has_ended() ) {
						$redirect = add_query_arg( 'gb-notice', Notices::AUCTION_HAS_ENDED, $auction_url );
						wp_safe_redirect( $redirect );
						exit;
					}

					// Make sure they aren't the current high bidder.
					if ( $auction->is_current_user_winning() ) {
						$redirect = add_query_arg( 'gb-notice', Notices::ALREADY_HIGH_BIDDER, $auction_url );
						wp_safe_redirect( $redirect );
						exit;
					}

					// Nothing left to do for bid items.
					return $passed;
				}

				/**
				 * Reward Items
				 */
				if ( ! $auction->has_ended() ) {
					$redirect = add_query_arg( 'gb-notice', Notices::AUCTION_NOT_ENDED, $auction_url );
					wp_safe_redirect( $redirect );
					exit;
				}

				if ( ! $auction->is_current_user_winner() ) {
					$redirect = add_query_arg( 'gb-notice', Notices::NOT_AUCTION_WINNER, $auction_url );
					wp_safe_redirect( $redirect );
					exit;
				}

				if ( goodbids()->rewards->is_redeemed( $auction_id ) ) {
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

				if ( ! goodbids()->products->get_type( $product->get_id() ) ) {
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

				if ( ! goodbids()->products->get_type( $product->get_id() ) ) {
					return;
				}

				wp_safe_redirect( wc_get_checkout_url() );
				exit;
			},
			15,
			2
		);
	}

	/**
	 * Disable Added to Cart message for Bid and Reward products.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function disable_add_to_cart_message(): void {
		add_filter(
			'wc_add_to_cart_message_html',
			function ( string $message, array $products ): string {
				foreach ( $products as $product_id => $quantity ) {
					if ( goodbids()->products->get_type( $product_id ) ) {
						return '';
					}
				}

				return $message;
			},
			10,
			2
		);
	}

	/**
	 * Disable access to the Cart page.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function disable_cart_access(): void {
		add_action(
			'template_redirect',
			function (): void {
				if ( ! is_cart() ) {
					return;
				}

				$checkout_url = wc_get_page_permalink( 'checkout' );

				// Redirect back to the checkout page if cart is not empty.
				if ( WC()->cart->get_cart_contents() ) {
					wp_safe_redirect( $checkout_url );
					exit;
				}

				// Redirect to the home page if cart is empty.
				wp_safe_redirect( home_url() );
				exit;
			}
		);
	}
}
