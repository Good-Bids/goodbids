<?php
/**
 * Bids Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Auctions;

use GoodBids\Frontend\Notices;
use GoodBids\Utilities\Log;
use WC_Product;
use WC_Product_Attribute;
use WC_Product_Variable;
use WC_Product_Variation;

/**
 * Class for Bids
 *
 * @since 1.0.0
 */
class Bids {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const ITEM_TYPE = 'bids';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const AUCTION_BID_META_KEY = 'gb_bid_product_id';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const AUCTION_BID_VARIATION_META_KEY = '_gb_bid_variation_id';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const FREE_BIDS_META_KEY = '_goodbids_free_bids';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const FREE_BID_STATUS_ALL = 'all';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const FREE_BID_STATUS_UNUSED = 'unused';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const FREE_BID_STATUS_USED = 'used';

	/**
	 * Initialize Bids
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Disable Bids on Main Site.
		if ( is_main_site() ) {
			return;
		}

		// Create a Bid product when an Auction is created.
		$this->create_bid_product_for_auction();

		// Bump Auction Bid Product Price when an Order is completed.
		$this->update_bid_product_on_order_complete();

		// Trash the Bid product when an Auction is trashed.
		$this->trash_bid_product_on_auction_trash();

		// Restore the Bid product when an Auction is restored from trash.
		$this->restore_bid_product_on_auction_restore();

		// Delete the Bid product when an Auction is deleted.
		$this->delete_bid_product_on_auction_delete();

		// Perform redirect after a bid checkout.
		$this->redirect_after_bid_checkout();
	}

	/**
	 * Create a Bid product when an Auction is created.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function create_bid_product_for_auction(): void {
		add_action(
			'wp_after_insert_post',
			function ( $post_id ): void {
				// Bail if this is a revision.
				if ( wp_is_post_revision( $post_id ) || 'publish' !== get_post_status( $post_id ) ) {
					return;
				}

				// Bail if not an Auction.
				if ( goodbids()->auctions->get_post_type() !== get_post_type( $post_id ) ) {
					return;
				}

				// Bail if the Auction already has a Bid product.
				if ( goodbids()->auctions->has_bid_product( (int) $post_id ) ) {
					return;
				}

				// Set starting bid amount.
				$starting_bid = goodbids()->auctions->calculate_starting_bid( (int) $post_id );

				// Make sure this meta value has been saved.
				if ( ! $starting_bid ) {
					Log::error( 'Starting Bid not calculated', compact( 'post_id' ) );
					return;
				}

				$bid_product = $this->create_new_bid_product( $post_id );

				if ( ! $bid_product->save() ) {
					Log::error( 'There was a problem saving the Bid Product', compact( 'post_id' ) );
					return;
				}

				// Set the Bid product as a meta of the Auction.
				goodbids()->auctions->set_bid_product_id( (int) $post_id, $bid_product->get_id() );

				$variation = $this->create_new_bid_variation( $bid_product->get_id(), $starting_bid, $post_id );

				if ( ! $variation->save() ) {
					Log::error( 'There was a problem saving the Bid Variation', compact( 'post_id' ) );
					return;
				}

				// Set the Bid variation as a meta of the Auction.
				goodbids()->auctions->set_bid_variation_id( (int) $post_id, $variation->get_id() );
			},
			10
		);
	}

	/**
	 * Create a new Bid product.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return WC_Product_Variable
	 */
	private function create_new_bid_product( int $auction_id ): WC_Product_Variable {
		$bid_title = sprintf(
			'%s %s',
			__( 'Bid for', 'goodbids' ),
			get_the_title( $auction_id )
		);

		// Create a new Bid product.
		$bid_product = new WC_Product_Variable();
		$bid_product->set_name( $bid_title );
		$bid_product->set_slug( sanitize_title( $bid_title ) );
		$bid_product->set_category_ids( [ $this->get_category_id() ] );
		$bid_product->set_status( 'publish' );

		// Stock Management, Somewhat locked down.
		$bid_product->set_sold_individually( true );
		$bid_product->set_virtual( true );

		// Instance Attribute.
		$instance_attr = new WC_Product_Attribute();
		$instance_attr->set_id( 0 );
		$instance_attr->set_name( 'bid_instance' );
		$instance_attr->set_visible( false );
		$instance_attr->set_variation( true );
		$instance_attr->set_options( [ 0 ] );

		$bid_product->set_attributes( [ 'bid_instance' => $instance_attr ] );

		try {
			$bid_product->set_sku( 'GB-BID-' . $auction_id );
			$bid_product->set_catalog_visibility( 'hidden' );
		} catch ( \WC_Data_Exception $e ) {
			// Do nothing.
		}

		/**
		 * Filter the Bid product before it is saved.
		 *
		 * @param WC_Product_Variable $bid_product The Bid Product.
		 * @param int $auction_id The Auction ID.
		 */
		return apply_filters( 'goodbids_bid_product_create', $bid_product, $auction_id );
	}

	/**
	 * Create a new Bid product variation.
	 *
	 * @since 1.0.0
	 *
	 * @param int $bid_product_id
	 * @param float $bid_price
	 * @param int $auction_id
	 *
	 * @return WC_Product_Variation
	 */
	public function create_new_bid_variation( int $bid_product_id, float $bid_price, int $auction_id ): WC_Product_Variation {
		/** @var WC_Product_Variable $bid_product */
		$bid_product = wc_get_product( $bid_product_id );
		$variations  = $bid_product->get_available_variations();
		$count       = count( $variations );

		$variation = new WC_Product_Variation();
		$variation->set_parent_id( $bid_product_id );
		$variation->set_regular_price( $bid_price );

		// Stock Management, Somewhat locked down.
		$variation->set_manage_stock( true );
		$variation->set_stock_quantity( 1 );
		$variation->set_backorders( 'yes' ); // This resolves a cart conflict.
		$variation->set_sold_individually( true );
		$variation->set_virtual( true );

		$variation->set_attributes( [ 'bid_instance' => $count ] );

		try {
			$variation->set_sku( 'GB-BID-' . $auction_id . '-' . $count );
		} catch ( \WC_Data_Exception $e ) {
			// Do nothing.
		}

		/**
		 * Filter the Bid Variation before it is saved.
		 *
		 * @param WC_Product_Variation $variation The Bid Product Variation.
		 * @param WC_Product_Variable $bid_product The Bid Product.
		 * @param int $auction_id The Auction ID.
		 */
		return apply_filters( 'goodbids_bid_product_variation_create', $variation, $bid_product, $auction_id );
	}

	/**
	 * Retrieve the Bids category ID, or create it if it doesn't exist.
	 *
	 * @since 1.0.0
	 *
	 * @return ?int
	 */
	public function get_category_id(): ?int {
		$bids_category = get_term_by( 'slug', self::ITEM_TYPE, 'product_cat' );

		if ( ! $bids_category ) {
			$bids_category = wp_insert_term( 'Bids', 'product_cat' );

			if ( is_wp_error( $bids_category ) ) {
				Log::error( $bids_category->get_error_message() );
				return null;
			}

			return $bids_category['term_id'];
		}

		return $bids_category->term_id;
	}



	/**
	 * Retrieves the Bid product ID for an Auction.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return int
	 */
	public function get_product_id( int $auction_id ): int {
		if ( ! $auction_id ) {
			$auction_id = goodbids()->auctions->get_auction_id();
		}

		return intval( get_post_meta( $auction_id, Bids::AUCTION_BID_META_KEY, true ) );
	}

	/**
	 * Retrieves the Bid product ID for an Auction.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return ?WC_Product
	 */
	public function get_product( int $auction_id ): ?WC_Product {
		$bid_product_id = $this->get_product_id( $auction_id );

		if ( ! $bid_product_id ) {
			return null;
		}

		return wc_get_product( $bid_product_id );
	}

	/**
	 * Retrieves the current Bid Variation ID for an Auction.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return int
	 */
	public function get_variation_id( int $auction_id ): int {
		if ( ! $auction_id ) {
			$auction_id = goodbids()->auctions->get_auction_id();
		}

		return intval( get_post_meta( $auction_id, Bids::AUCTION_BID_VARIATION_META_KEY, true ) );
	}

	/**
	 * Retrieves the Bid product ID for an Auction.
	 *
	 * @param int $auction_id
	 *
	 * @since 1.0.0
	 *
	 * @return ?WC_Product
	 */
	public function get_variation( int $auction_id ): ?WC_Product {
		$bid_variation_id = $this->get_variation_id( $auction_id );

		if ( ! $bid_variation_id ) {
			return null;
		}

		return wc_get_product( $bid_variation_id );
	}

	/**
	 * Returns the URL to place a bid on an Auction.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return string
	 */
	public function get_place_bid_url( int $auction_id ): string {
		$bid_variation_id = $this->get_variation_id( $auction_id );

		if ( ! $bid_variation_id ) {
			return '';
		}

		return add_query_arg( 'add-to-cart', $bid_variation_id, wc_get_checkout_url() );
	}

	/**
	 * Retrieve the Bid product ID for an Auction.
	 *
	 * @since 1.0.0
	 *
	 * @param int $bid_product_id
	 *
	 * @return ?int
	 */
	public function get_auction_id( int $bid_product_id ): ?int {
		$product_id = goodbids()->auctions->get_parent_product_id( $bid_product_id );
		$auction_id = get_post_meta( $product_id, Auctions::PRODUCT_AUCTION_META_KEY, true );

		return intval( $auction_id ) ?: null;
	}

	/**
	 * Update the Bid product price when an order is completed.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function update_bid_product_on_order_complete(): void {
		add_action(
			'goodbids_order_payment_complete',
			function ( int $order_id, int $auction_id ) {
				if ( ! goodbids()->woocommerce->orders->is_bid_order( $order_id ) ) {
					return;
				}

				if ( goodbids()->auctions->has_ended( $auction_id ) ) {
					return;
				}

				if ( ! $this->increase_bid( $auction_id ) ) {
					Log::error( 'There was a problem trying to increase the Bid Amount', compact( 'auction_id' ) );
				}
			},
			10,
			2
		);
	}

	/**
	 * Increase the current bid amount.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return bool
	 */
	public function increase_bid( int $auction_id ): bool {
		$bid_product   = $this->get_product( $auction_id );
		$bid_variation = $this->get_variation( $auction_id );

		if ( ! $bid_product || ! $bid_variation ) {
			Log::error( 'Auction missing Bid Product or Variation', compact( 'auction_id' ) );
			return false;
		}

		$increment_amount = goodbids()->auctions->get_bid_increment( $auction_id );
		$current_price    = floatval( $bid_variation->get_regular_price( 'edit' ) );
		$new_price        = $current_price + $increment_amount;

		// Add support for new variation.
		$this->update_bid_product_attributes( $bid_product );

		// Create the new Variation.
		$new_variation = $this->create_new_bid_variation( $bid_product->get_id(), $new_price, $auction_id );

		if ( ! $new_variation->save() ) {
			Log::error( 'There was a problem saving the new Bid Variation', compact( 'auction_id' ) );
			return false;
		}

		// Set the Bid variation as a meta of the Auction.
		goodbids()->auctions->set_bid_variation_id( $auction_id, $new_variation->get_id() );

		// Disallow backorders on previous variation.
		$bid_variation->set_backorders( 'no' );
		$bid_variation->save();

		return true;
	}

	/**
	 * Update the Bid Product to include more variations
	 *
	 * @since 1.0.0
	 *
	 * @param WC_Product $bid_product
	 *
	 * @return void
	 */
	public function update_bid_product_attributes( WC_Product $bid_product ): void {
		$attributes = get_post_meta( $bid_product->get_id(), '_product_attributes', true );

		if ( empty( $attributes['bid_instance'] ) ) {
			return;
		}

		$options   = array_map( 'trim', explode( '|', $attributes['bid_instance']['value'] ) );
		$options[] = count( $options );

		$attributes['bid_instance']['value'] = implode( ' | ', $options );

		update_post_meta( $bid_product->get_id(), '_product_attributes', $attributes );
	}

	/**
	 * Trash the Bid product when an Auction is trashed.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function trash_bid_product_on_auction_trash(): void {
		add_action(
			'trashed_post',
			function ( int $post_id ): void {
				// Bail if not an Auction.
				if ( goodbids()->auctions->get_post_type() !== get_post_type( $post_id ) ) {
					return;
				}

				$bid_product_id = goodbids()->auctions->bids->get_product_id( $post_id );

				// Bail if the Auction doesn't have a Bid product.
				if ( ! $bid_product_id ) {
					return;
				}

				// Trash the Bid product.
				wp_trash_post( $bid_product_id );
			}
		);
	}

	/**
	 * Restore the Bid product when an Auction is restored from trash.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function restore_bid_product_on_auction_restore(): void {
		add_action(
			'untrashed_post',
			function ( int $post_id ): void {
				// Bail if not an Auction.
				if ( goodbids()->auctions->get_post_type() !== get_post_type( $post_id ) ) {
					return;
				}

				$bid_product_id = goodbids()->auctions->bids->get_product_id( $post_id );

				// Bail if the Auction doesn't have a Bid product.
				if ( ! $bid_product_id ) {
					return;
				}

				// Restore the Bid product.
				wp_untrash_post( $bid_product_id );
			}
		);
	}

	/**
	 * Delete the Bid product when an Auction is deleted.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function delete_bid_product_on_auction_delete(): void {
		add_action(
			'before_delete_post',
			function ( int $post_id ): void {
				// Bail if not an Auction.
				if ( goodbids()->auctions->get_post_type() !== get_post_type( $post_id ) ) {
					return;
				}

				$bid_product_id = goodbids()->auctions->bids->get_product_id( $post_id );

				// Bail if the Auction doesn't have a Bid product.
				if ( ! $bid_product_id ) {
					return;
				}

				// Delete the Bid product.
				wp_delete_post( $bid_product_id, true );
			}
		);
	}

	/**
	 * Redirect back to Auction after Checkout.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function redirect_after_bid_checkout(): void {
		add_action(
			'woocommerce_thankyou',
			function ( int $order_id ): void {
				if ( is_admin() || wp_doing_ajax() || headers_sent() ) {
					return;
				}

				if ( ! goodbids()->woocommerce->orders->is_bid_order( $order_id ) ) {
					return;
				}

				$auction_id = goodbids()->woocommerce->orders->get_auction_id( $order_id );
				$redirect   = get_permalink( $auction_id );

				// Do not award free bids if this order contains a free bid.
				if ( ! goodbids()->woocommerce->orders->is_free_bid_order( $order_id ) ) {
					$max_free_bids       = intval( goodbids()->get_config( 'auctions.default-free-bids' ) );
					$remaining_free_bids = goodbids()->auctions->get_free_bids_available( $auction_id );
					$nth_bid             = $max_free_bids - $remaining_free_bids + 1;
					$bid_order           = wc_get_order( $order_id );

					$description = sprintf(
					// translators: %1$s represents the nth bid placed, %2$s represent the amount of the bid, %3$d represents the Auction ID.
						__( 'Placed %1$s Paid Bid for %2$s on Auction ID %3$d.', 'goodbids' ),
						goodbids()->utilities->get_ordinal( $nth_bid ),
						$bid_order->get_total( 'edit' ),
						$auction_id
					);

					if ( goodbids()->auctions->maybe_award_free_bid( $auction_id, null, $description ) ) {
						// TODO: Let the user know they earned a free bid.
						$redirect = add_query_arg( 'gb-notice', Notices::EARNED_FREE_BID, $redirect );
					}
				} else { // Reduce the Free Bid Count for the user.
					if ( goodbids()->users->redeem_free_bid( $auction_id, $order_id ) ) {
						$redirect = add_query_arg( 'gb-notice', Notices::FREE_BID_REDEEMED, $redirect );
					}
				}

				wp_safe_redirect( $redirect );
				exit;
			}
		);
	}
}
