<?php
/**
 * WooCommerce Orders Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce;

use Exception;
use GoodBids\Auctions\Bids;
use GoodBids\Auctions\Rewards;
use GoodBids\Frontend\Notices;
use GoodBids\Plugins\WooCommerce;
use GoodBids\Utilities\Log;

/**
 * Class for Order Methods
 *
 * @since 1.0.0
 */
class Orders {

	/**
	 * Initialize Orders
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Redirect a User when somebody else bids the amount currently in their cart.
		$this->handle_duplicate_bid_ajax();
	}

	/**
	 * Get the Auction info from the Order.
	 *
	 * @param int $order_id
	 *
	 * @since 1.0.0
	 *
	 * @return ?array
	 */
	public function get_auction_info( int $order_id ): ?array {
		$order         = wc_get_order( $order_id );
		$auction_id    = $this->get_auction_id( $order_id );
		$variation_id  = $this->get_bid_product_variation_id( $order_id );
		$order_type    = $this->get_type( $order_id );
		$uses_free_bid = $this->is_free_bid_order( $order_id );

		// Return early if we already have this info.
		if ( $auction_id && $order_type ) {
			return [
				'auction_id'    => $auction_id,
				'variation_id'  => $variation_id,
				'order_type'    => $order_type,
				'uses_free_bid' => $uses_free_bid,
			];
		}

		// Find order items with Auction Meta.
		foreach ( $order->get_items() as $item ) {
			try {
				$auction_id = wc_get_order_item_meta( $item->get_id(), WooCommerce::AUCTION_META_KEY );
				$order_type = wc_get_order_item_meta( $item->get_id(), WooCommerce::TYPE_META_KEY );
			} catch ( Exception $e ) {
				Log::error( $e->getMessage() );
				continue;
			}

			if ( $auction_id && $order_type ) {
				$variation_id = $item->get_variation_id();
				break;
			}
		}

		if ( ! $auction_id || ! $order_type ) {
			return null;
		}

		return [
			'auction_id'    => $auction_id,
			'order_type'    => $order_type,
			'variation_id'  => $variation_id,
			'uses_free_bid' => $uses_free_bid,
		];
	}

	/**
	 * Get the Order Auction ID.
	 *
	 * @since 1.0.0
	 *
	 * @param int $order_id
	 *
	 * @return ?int
	 */
	public function get_auction_id( int $order_id = 0 ): ?int {
		$order_id = $order_id ?: get_the_ID();

		if ( ! $order_id ) {
			return null;
		}

		$order      = wc_get_order( $order_id );
		$auction_id = $order->get_meta( WooCommerce::AUCTION_META_KEY );

		return intval( $auction_id ) ?: null;
	}

	/**
	 * Get the Order Type.
	 *
	 * @since 1.0.0
	 *
	 * @param int $order_id
	 *
	 * @return ?string
	 */
	public function get_type( int $order_id ): ?string {
		$order = wc_get_order( $order_id );
		return $order->get_meta( WooCommerce::TYPE_META_KEY ) ?: null;
	}

	/**
	 * Get the Bid Product Variation ID from an Order.
	 *
	 * @since 1.0.0
	 *
	 * @param int $order_id
	 *
	 * @return ?int
	 */
	public function get_bid_product_variation_id( int $order_id ): ?int {
		$order = wc_get_order( $order_id );

		foreach ( $order->get_items() as $item ) {
			$product_type = goodbids()->products->get_type( $item->get_product_id() );

			if ( Bids::ITEM_TYPE === $product_type ) {
				return $item->get_variation_id();
			}
		}

		return null;
	}

	/**
	 * Check if an Order is a Bid Order.
	 *
	 * @since 1.0.0
	 *
	 * @param int $order_id
	 *
	 * @return bool
	 */
	public function is_bid_order( int $order_id ): bool {
		return Bids::ITEM_TYPE === $this->get_type( $order_id );
	}

	/**
	 * Check if an Order is a Reward Order.
	 *
	 * @since 1.0.0
	 *
	 * @param int $order_id
	 *
	 * @return bool
	 */
	public function is_reward_order( int $order_id ): bool {
		return Rewards::ITEM_TYPE === $this->get_type( $order_id );
	}

	/**
	 * Check if an Order was placed using a Free Bid.
	 *
	 * @since 1.0.0
	 *
	 * @param int $order_id
	 *
	 * @return bool
	 */
	public function is_free_bid_order( int $order_id ): bool {
		// Not a Bid Order.
		if ( Bids::ITEM_TYPE !== $this->get_type( $order_id ) ) {
			return false;
		}

		$order = wc_get_order( $order_id );

		if ( $order->get_total( 'edit' ) > 0 ) {
			return false;
		}

		return true;
	}

	/**
	 * Get the tax for an order.
	 *
	 * @since 1.0.0
	 *
	 * @param int $order_id
	 *
	 * @return float
	 */
	public function get_tax_amount( int $order_id ): float {
		$order = wc_get_order( $order_id );
		return $order->get_total_tax( 'edit' );
	}

	/**
	 * Check if an order has any tax
	 *
	 * @since 1.0.0
	 *
	 * @param int $order_id
	 *
	 * @return bool
	 */
	public function has_tax( int $order_id ): bool {
		$tax = $this->get_tax_amount( $order_id );
		return $tax > 0;
	}

	/**
	 * Get all Bid Order IDs
	 *
	 * @since 1.0.0
	 *
	 * @param int $limit
	 *
	 * @return array
	 */
	public function get_all_bid_order_id( int $limit = -1 ): array {
		$args = [
			'limit'      => $limit,
			'status'     => [ 'processing', 'completed' ],
			'return'     => 'ids',
			'orderby'    => 'date',
			'order'      => 'DESC',
			'meta_query' => [
				[
					'key'     => WooCommerce::TYPE_META_KEY,
					'compare' => '=',
					'value'   => Bids::ITEM_TYPE,
				],
			],
		];

		return wc_get_orders( $args );
	}

	/**
	 * Redirect a User when somebody else bids the amount currently in their cart.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function handle_duplicate_bid_ajax(): void {
		$ajax_action = 'goodbids_cart_bid_placed';

		add_action(
			'wp_footer',
			function() use ( $ajax_action ) {
				if ( is_admin() || ! is_checkout() ) {
					return;
				}

				$admin_url = esc_js( admin_url( 'admin-ajax.php' ) );
				$admin_url = str_replace( '&amp;', '&', $admin_url );
				?>
				<script>
					let goodbidsCartBidAbort = false;
					const goodbidsCartBidHandlerInterval = 2000;
					const goodbidsHandleCartBidPlaced = () => {
						const xhr = new XMLHttpRequest();
						xhr.open('POST', '<?php echo $admin_url; // phpcs:ignore ?>', true);
						xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

						if ( goodbidsCartBidAbort ) {
							return;
						}

						xhr.onreadystatechange = () => {
							if ( goodbidsCartBidAbort ) {
								xhr.abort();
								return;
							}

							if (xhr.readyState === 4 && xhr.status === 200) {
								const response = JSON.parse(xhr.responseText);

								if ( goodbidsCartBidAbort ) {
									return;
								}

								if (!response || typeof response !== 'object' || !response.data) {
									return;
								}

								// Halt the process if the user is the winning bidder.
								if (response.data.halt) {
									return;
								}

								// Check again in 2 seconds.
								if (!response.data.outbid) {
									setTimeout(goodbidsHandleCartBidPlaced, goodbidsCartBidHandlerInterval);
									return;
								}

								if ( goodbidsCartBidAbort ) {
									return;
								}

								// Redirect to the Auction for the error.
								window.location.replace(response.data.auctionUrl);
							}
						};

						xhr.send('action=<?php echo esc_js( $ajax_action ); ?>');
					};

					const goodbidsAbortHandlerOnBid = () => {
						const checkoutButton = document.querySelector('.wc-block-components-checkout-place-order-button');
						checkoutButton.addEventListener('click', () => {
							goodbidsCartBidAbort = true;
						}
					};

					document.addEventListener('DOMContentLoaded', function() {
						goodbidsAbortHandlerOnBid();
						goodbidsHandleCartBidPlaced();
					});
				</script>
				<?php
			}
		);

		add_action(
			'wp_ajax_' . $ajax_action,
			function () {
				$variation_id = false;
				$response     = [ 'outbid' => false, 'halt' => false ];

				foreach ( WC()->cart->get_cart() as $cart_item ) {
					$product_id = $cart_item['product_id'];

					if ( Bids::ITEM_TYPE !== goodbids()->products->get_type( $product_id ) ) {
						continue;
					}

					$variation_id = $cart_item['variation_id'];
					break;
				}

				// Bail early, no variation found.
				if ( ! $variation_id ) {
					wp_send_json_success( $response );
				}

				$bid_product = wc_get_product( $variation_id );
				$quantity    = $bid_product->get_stock_quantity( 'edit' );

				// Quantities are still good.
				if ( $quantity > 0 ) {
					wp_send_json_success( $response );
				}

				$auction_id = goodbids()->products->get_auction_id_from_product( $variation_id );
				$auction    = goodbids()->auctions->get( $auction_id );

//				$info = [
//					'auction_id'    => $auction_id,
//					'variation_id'  => $variation_id,
//					'order_type'    => Bids::ITEM_TYPE,
//					'uses_free_bid' => WC()->cart->get_total( 'edit' ) <= 0,
//				];
//
//				if ( $auction->bid_allowed( $info ) ) {
//					wp_send_json_success( $response );
//				}

				// Send a halt signal to the winning bidder.
				if ( $auction->is_current_user_winning() ) {
					$response['halt'] = true;
					wp_send_json_success( $response );
				}

				// Looks like a different user reduced the quantity.
				WC()->cart->empty_cart(); // Empty their cart.

				$response['outbid']     = true;
				$response['auctionUrl'] = $auction->get_url();

				$notice = goodbids()->notices->get_notice( Notices::BID_ALREADY_PLACED_CART );
				wc_add_notice( $notice['message'], $notice['type'], [ '_notice_id' => Notices::BID_ALREADY_PLACED_CART ] );

				wp_send_json_success( $response );
			}
		);

		// Clear Notice if the user is the winning bidder.
		add_action(
			'template_redirect',
			function () {
				if ( ! is_singular( goodbids()->auctions->get_post_type() ) ) {
					return;
				}

				$auction = goodbids()->auctions->get( get_queried_object_id() );

				if ( ! $auction->is_current_user_winning() ) {
					return;
				}

				if ( ! goodbids()->notices->notice_exists( Notices::BID_ALREADY_PLACED_CART ) ) {
					return;
				}

				goodbids()->notices->remove_notice( Notices::BID_ALREADY_PLACED_CART );
			}
		);
	}
}
