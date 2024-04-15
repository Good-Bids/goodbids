<?php
/**
 * WooCommerce Cart Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce;

use GoodBids\Auctions\Bids;
use GoodBids\Auctions\Rewards;
use GoodBids\Plugins\WooCommerce;
use WC_Order_Item_Product;

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

		// Prevent users from accessing the Cart page.
		$this->disable_cart_access();

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

	/**
	 * Check if the current order is a Bid Order
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_bid_order(): bool {
		return Bids::ITEM_TYPE === $this->get_order_type();
	}

	/**
	 * Check if the current order is a Reward Order
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_reward_order(): bool {
		return Rewards::ITEM_TYPE === $this->get_order_type();
	}

	/**
	 * Get the Cart Order Type
	 *
	 * @since 1.0.0
	 *
	 * @return ?string
	 */
	public function get_order_type(): ?string {
		if ( ! WC()->cart ) {
			return null;
		}

		$cart = WC()->cart->get_cart_contents();

		foreach ( $cart as $cart_item ) {
			$product = $cart_item['data'];
			if ( goodbids()->products->get_type( $product->get_id() ) ) {
				return goodbids()->products->get_type( $product->get_id() );
			}
		}

		return null;
	}

	/**
	 * Get the total amount of the current order
	 *
	 * @since 1.0.0
	 *
	 * @return float
	 */
	public function get_total(): float {
		if ( ! WC()->cart ) {
			return 0;
		}

		return WC()->cart->get_total( 'edit' );
	}
}
