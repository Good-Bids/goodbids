<?php
/**
 * WooCommerce Orders Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce;

use GoodBids\Auctions\Bids;
use GoodBids\Auctions\Rewards;
use GoodBids\Plugins\WooCommerce;

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
	public function __construct() {}

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
		$order_type    = $this->get_type( $order_id );
		$uses_free_bid = $this->is_free_bid_order( $order_id );

		// Return early if we already have this info.
		if ( $auction_id && $order_type ) {
			return [
				'auction_id'    => $auction_id,
				'order_type'    => $order_type,
				'uses_free_bid' => $uses_free_bid,
			];
		}

		// Find order items with Auction Meta.
		foreach ( $order->get_items() as $item ) {
			try {
				$auction_id = wc_get_order_item_meta( $item->get_id(), WooCommerce::AUCTION_META_KEY );
				$order_type = wc_get_order_item_meta( $item->get_id(), WooCommerce::TYPE_META_KEY );
			} catch ( \Exception $e ) {
				continue;
			}

			if ( $auction_id && $order_type ) {
				break;
			}
		}

		if ( ! $auction_id || ! $order_type ) {
			// TODO: Log warning.
			return null;
		}

		return [
			'auction_id'    => $auction_id,
			'order_type'    => $order_type,
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
		$type = get_post_meta( $order_id, WooCommerce::TYPE_META_KEY, true );
		return $type ?: null;
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

		// TODO: Temporary Solution until Free Bids are implemented.
		if ( 0 < $order->get_total( 'edit' ) ) {
			return false;
		}

		return true;
	}
}
