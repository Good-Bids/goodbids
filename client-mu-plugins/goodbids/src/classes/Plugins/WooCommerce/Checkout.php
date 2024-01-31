<?php
/**
 * WooCommerce Checkout Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce;

use GoodBids\Auctions\Bids;
use GoodBids\Frontend\Notices;
use GoodBids\Plugins\WooCommerce;

/**
 * Class for Checkout Methods
 *
 * @since 1.0.0
 */
class Checkout {

	/**
	 * Initialize Checkout
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Track Auction info for the order during checkout.
		$this->store_auction_info_on_checkout();

		// Validate Bids during checkout.
		$this->validate_bid();

		// Remove the Order Notes Checkout field.
		$this->disable_order_notes();

		// Automatically mark processing orders as Complete.
		$this->automatically_complete_orders();
	}

	/**
	 * Store the Auction ID in the Order Meta.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function store_auction_info_on_checkout(): void {
		add_action(
			'woocommerce_payment_complete',
			function ( int $order_id ) {
				$info = goodbids()->woocommerce->orders->get_auction_info( $order_id );

				if ( ! $info ) {
					// TODO: Log warning.
					return;
				}

				// Update the Order Meta.
				$order = wc_get_order( $order_id );
				$order->update_meta_data( WooCommerce::AUCTION_META_KEY, $info['auction_id'] );
				$order->update_meta_data( WooCommerce::TYPE_META_KEY, $info['order_type'] );
				$order->update_meta_data( WooCommerce::MICROTIME_META_KEY, microtime( true ) );
				$order->save();

				/**
				 * Action triggered when an Order is paid for.
				 *
				 * @since 1.0.0
				 *
				 * @param int $order_id
				 * @param int $auction_id
				 */
				do_action( 'goodbids_order_payment_complete', $order_id, $info['auction_id'] );
			}
		);
	}

	/**
	 * Validate Bids during checkout
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function validate_bid(): void {
		add_action(
			'woocommerce_store_api_checkout_update_order_from_request',
			function ( $order, $request = [] ) {
				if ( empty( $request ) ) {
					return;
				}

				$info = goodbids()->woocommerce->orders->get_auction_info( $order->get_id() );

				if ( ! $info ) {
					// TODO: Log warning.
					return;
				}

				if ( Bids::ITEM_TYPE !== $info['order_type'] ) {
					return;
				}

				// Make sure Auction has started.
				if ( ! goodbids()->auctions->has_started( $info['auction_id'] ) ) {
					$notice = goodbids()->notices->get_notice( Notices::AUCTION_NOT_STARTED );
					wc_add_notice( $notice['message'], $notice['type'] );
					return;
				}

				// Make sure Auction has not ended.
				if ( goodbids()->auctions->has_ended( $info['auction_id'] ) ) {
					$notice = goodbids()->notices->get_notice( Notices::AUCTION_HAS_ENDED );
					wc_add_notice( $notice['message'], $notice['type'] );
					return;
				}

				// Maybe perform Free Bids checks.
				if ( ! goodbids()->woocommerce->orders->is_free_bid_order( $order->get_id() ) ) {
					return;
				}

				// Make sure Free Bids are allowed.
				if ( ! goodbids()->auctions->are_free_bids_allowed( $info['auction_id'] ) ) {
					$notice = goodbids()->notices->get_notice( Notices::FREE_BIDS_NOT_ELIGIBLE );
					wc_add_notice( $notice['message'], $notice['type'] );
					return;
				}

				// Make sure the current user has available Free Bids.
				if ( ! goodbids()->users->get_available_free_bid_count() ) {
					$notice = goodbids()->notices->get_notice( Notices::NO_AVAILABLE_FREE_BIDS );
					wc_add_notice( $notice['message'], $notice['type'] );
				}
			},
			10,
			2
		);
	}

	/**
	 * Disable Order Notes Checkout field.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function disable_order_notes(): void {
		add_filter(
			'render_block',
			function ( string $block_content, array $block ): string {
				if ( empty( $block['blockName'] ) || 'woocommerce/checkout-order-note-block' !== $block['blockName'] ) {
					return $block_content;
				}

				return '';
			},
			10,
			2
		);
	}

	/**
	 * Automatically mark processing orders as Complete.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function automatically_complete_orders(): void {
		add_action(
			'woocommerce_order_status_processing',
			function ( $order_id ): void {
				if ( ! $order_id ) {
					return;
				}

				$order = wc_get_order( $order_id );

				if ( $order->needs_payment() ) {
					return;
				}

				$order->update_status( 'completed' );
			}
		);
	}
}
