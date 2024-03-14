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
use GoodBids\Network\Nonprofit;
use GoodBids\Plugins\WooCommerce;
use GoodBids\Utilities\Log;
use WP_Block;

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

		// Add additional terms & conditions to the Checkout page
		$this->adjust_terms_block();

		// Add the Nonprofit name to the Checkout page title
		$this->adjust_checkout_title();

		// Modify the Checkout button based on the type of order.
		$this->adjust_checkout_button();
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
					Log::warning( 'Unable to store Auction info on Order Meta.', compact( 'order_id' ) );
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
			},
			50
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
					Log::warning( 'Unable to retrieve Auction info from Order Meta.', compact( 'order' ) );
					return;
				}

				if ( Bids::ITEM_TYPE !== $info['order_type'] ) {
					return;
				}

				$auction = goodbids()->auctions->get( $info['auction_id'] );

				// Make sure Auction has started.
				if ( ! $auction->has_started() ) {
					goodbids()->notices->add_notice( Notices::AUCTION_NOT_STARTED );
					return;
				}

				// Make sure Auction has not ended.
				if ( $auction->has_ended() ) {
					goodbids()->notices->add_notice( Notices::AUCTION_HAS_ENDED );
					return;
				}

				// Perform Free Bids checks.
				if ( goodbids()->woocommerce->orders->is_free_bid_order( $order->get_id() ) ) {
					// Make sure Free Bids are allowed.
					if ( ! $auction->are_free_bids_allowed() ) {
						goodbids()->notices->add_notice( Notices::FREE_BIDS_NOT_ELIGIBLE );
						return;
					}

					// Make sure the current user has available Free Bids.
					if ( ! goodbids()->users->get_available_free_bid_count() ) {
						goodbids()->notices->add_notice( Notices::NO_AVAILABLE_FREE_BIDS );
						return;
					}
				}

				// Perform this check last to ensure the bid hasn't already been placed.
				if ( ! $auction->bid_allowed( $info ) ) {
					goodbids()->notices->add_notice( Notices::BID_ALREADY_PLACED );
					WC()->cart->empty_cart();
					return;
				}

				// Lock the Bid.
				$auction->lock_bid();
			},
			50,
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

	/**
	 * Add terms & conditions and privacy policy text to checkout
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function adjust_terms_block(): void {
		add_filter(
			'render_block',
			function ( string $block_content, array $block ): string {
				if ( is_admin() || ! is_checkout() || empty( $block['blockName'] ) || 'woocommerce/checkout-terms-block' !== $block['blockName'] || is_main_site() ) {
					return $block_content;
				}

				$block_content = '';

				if ( goodbids()->woocommerce->cart->is_bid_order() ) {
					$bid_amount = goodbids()->woocommerce->cart->get_total();
					if ( $bid_amount ) {
						$nonprofit = new Nonprofit( get_current_blog_id() );

						$block_content .= sprintf(
							'<p class="mt-10 ml-10">%s $%s %s %s. %s</p>',
							__( 'By placing this bid, you are making a donation for your full bid amount of', 'goodbids' ),
							esc_html( $bid_amount ),
							__( 'to', 'goodbids' ),
							esc_html( $nonprofit->get_name() ),
							__( 'This is a non-refundable donation.', 'goodbids' )
						);
					}
				}

				$block_content .= sprintf(
					'<p class="mt-10 ml-10">%s %s %s %s.<p>',
					esc_html__( 'By proceeding with your order, you agree to GOODBIDS\'', 'goodbids' ),
					wp_kses_post( goodbids()->sites->get_terms_conditions_link() ),
					esc_html__( 'and', 'goodbids' ),
					wp_kses_post( goodbids()->sites->get_privacy_policy_link() )
				);

				return $block_content;
			},
			10,
			2
		);
	}

	/**
	 * Add the Nonprofit name to the Checkout page title
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function adjust_checkout_title(): void {
		add_filter(
			'render_block',
			function ( string $block_content, array $block ): string {
				if ( is_admin() || ! is_checkout() || empty( $block['blockName'] ) || 'core/post-title' !== $block['blockName'] || is_main_site() ) {
					return $block_content;
				}

				$nonprofit = new Nonprofit( get_current_blog_id() );

				return str_replace(
					'Checkout', // Not exactly supportive if i18n.
					sprintf(
						'%s %s',
						$nonprofit->get_name(),
						esc_html__( 'Checkout', 'goodbids' )
					),
					$block_content
				);
			},
			10,
			2
		);
	}

	/**
	 * Modify the Checkout button based on the type of order.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function adjust_checkout_button(): void {
		add_filter(
			'render_block_data',
			function ( array $block, array $source_block, ?WP_Block $parent_block ): array {
				if ( is_admin() || ! is_checkout() || empty( $block['blockName'] ) || 'woocommerce/checkout-actions-block' !== $block['blockName'] || is_main_site() ) {
					return $block;
				}

				if ( goodbids()->woocommerce->cart->is_bid_order() ) {
					$block['attrs']['placeOrderButtonLabel'] = __( 'Confirm Bid', 'goodbids' );
				} elseif ( goodbids()->woocommerce->cart->is_reward_order() ) {
					$block['attrs']['placeOrderButtonLabel'] = __( 'Claim Reward', 'goodbids' );
				}

				return $block;
			},
			10,
			3
		);
	}
}
