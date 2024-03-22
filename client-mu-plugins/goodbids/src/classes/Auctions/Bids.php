<?php
/**
 * Bids Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Auctions;

use GoodBids\Frontend\Notices;
use GoodBids\Users\FreeBid;
use GoodBids\Users\FreeBids;
use GoodBids\Utilities\Log;
use WC_Data_Exception;
use WC_Product;
use WC_Product_Attribute;
use WC_Product_Variable;
use WC_Product_Variation;
use WP_Query;

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
	const PLACE_BID_SLUG = 'place-bid';

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
	 * Initialize Bids
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Disable Bids on Main Site.
		if ( is_main_site() ) {
			return;
		}

		// Use the Place Bid slug at the end of the Auction URL to place Bid.
		$this->add_place_bid_url_rewrite();
		$this->handle_place_bid_endpoint();

		// Create a Bid product when an Auction is created.
		$this->create_bid_product_for_auction();

		// Process Free bids before updating the Bid Product.
		$this->update_free_bids_on_order_complete();

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

		// Hide Bid Products from WP Admin > Products.
		$this->filter_bid_products();

		// Reduce the current Bid Product stock to 0 when the Auction ends.
		$this->disable_bid_product_on_auction_end();

		// This ensures all Bid Variations have the same author as the Bid Product.
		$this->update_bid_variation_authors();
	}

	/**
	 * Add custom rewrite rule to place bid for Auction
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_place_bid_url_rewrite(): void {
		add_filter(
			'query_vars',
			function ( $vars ) {
				$vars[] = self::PLACE_BID_SLUG;
				return $vars;
			}
		);

		add_action(
			'init',
			function () {
				$auction_slug = Auctions::SINGULAR_SLUG;
				$post_type    = goodbids()->auctions->get_post_type();
				$bid_slug     = self::PLACE_BID_SLUG;

				add_rewrite_rule(
					'^' . $auction_slug . '/([^/]+)/' . $bid_slug . '/?',
					'index.php?post_type=' . $post_type . '&name=$matches[1]&' . $bid_slug . '=1',
					'top'
				);
			}
		);
	}

	/**
	 * Redirect to the checkout page with latest Bid Variation
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function handle_place_bid_endpoint(): void {
		add_action(
			'template_redirect',
			function (): void {
				if ( ! get_query_var( self::PLACE_BID_SLUG ) ) {
					return;
				}

				$auction_id = get_queried_object_id();
				$auction    = new Auction( $auction_id );

				if ( ! is_user_logged_in() ) {
					$auth_url    = wc_get_page_permalink( 'myaccount' );
					$redirect_to = $auction->get_place_bid_url();

					goodbids()->notices->add_notice( Notices::NOT_AUTHENTICATED );
					$redirect = add_query_arg( 'redirect-to', urlencode( $redirect_to ), $auth_url );

					wp_safe_redirect( $redirect );
					exit;
				}

				if ( ! $auction->has_started() ) {
					goodbids()->notices->add_notice( Notices::AUCTION_NOT_STARTED );
					wp_safe_redirect( $auction->get_url() );
					exit;
				}

				if ( $auction->has_ended() ) {
					goodbids()->notices->add_notice( Notices::AUCTION_HAS_ENDED );
					wp_safe_redirect( $auction->get_url() );
					exit;
				}

				// Make sure they aren't the current high bidder.
				if ( $auction->is_current_user_winning() ) {
					goodbids()->notices->add_notice( Notices::ALREADY_HIGH_BIDDER );
					wp_safe_redirect( $auction->get_url() );
					exit;
				}

				$product_id   = $auction->get_product_id();
				$variation_id = $auction->get_variation_id();

				if ( ! $product_id || ! $variation_id ) {
					// TODO: Add Error Message
					wp_safe_redirect( $auction->get_url() );
					exit;
				}

				WC()->cart->empty_cart();

				try {
					WC()->cart->add_to_cart( $product_id, 1, $variation_id );
				} catch ( \Exception $e ) {
					Log::error( $e->getMessage() );

					// TODO: Add Error Message
					wp_safe_redirect( $auction->get_url() );
					exit();
				}

				/**
				 * Action to perform when a bid is placed.
				 *
				 * @since 1.0.0
				 *
				 * @param int $auction_id The Auction ID.
				 * @param int $product_id The Bid Product ID.
				 * @param int $variation_id The Bid Variation ID.
				 */
				do_action( 'goodbids_place_bid', $auction_id, $product_id, $variation_id );

				/**
				 * Filter the URL to redirect after placing a bid.
				 *
				 * @since 1.0.0
				 *
				 * @param string $redirect The URL to redirect to.
				 * @param int $auction_id The Auction ID.
				 */
				$redirect = apply_filters( 'goodbids_place_bid_redirect', wc_get_checkout_url(), $auction_id );

				wp_safe_redirect( $redirect );
				exit();
			}
		);
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
				// Bail if this is a revision or not an Auction.
				if ( wp_is_post_revision( $post_id ) || 'publish' !== get_post_status( $post_id ) || goodbids()->auctions->get_post_type() !== get_post_type( $post_id ) ) {
					return;
				}

				$auction = goodbids()->auctions->get( (int) $post_id );

				// Bail if the Auction already has a Bid product.
				if ( $auction->has_bid_product() ) {
					return;
				}

				// Set starting bid amount.
				$starting_bid = $auction->calculate_starting_bid();

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
				$auction->set_bid_product_id( $bid_product->get_id() );

				$variation = $this->create_new_bid_variation( $bid_product->get_id(), $starting_bid, $post_id );

				if ( ! $variation->save() ) {
					Log::error( 'There was a problem saving the Bid Variation', compact( 'post_id' ) );
					return;
				}

				$bid_author = get_post_field( 'post_author', $bid_product->get_id() );
				$this->set_variation_author( $variation, $bid_author );

				// Set the Bid variation as a meta of the Auction.
				$auction->set_bid_variation_id( $variation->get_id() );
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
		$bid_product->set_tax_status( 'none' );

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
		} catch ( WC_Data_Exception $e ) {
			Log::error( $e->getMessage() );
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
		} catch ( WC_Data_Exception $e ) {
			Log::error( $e->getMessage() );
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
	 * @return ?WC_Product_Variable
	 */
	public function get_product( int $auction_id ): ?WC_Product {
		$bid_product_id = $this->get_product_id( $auction_id );

		if ( ! $bid_product_id ) {
			return null;
		}

		$bid_product = wc_get_product( $bid_product_id );
		if ( ! $bid_product ) {
			return null;
		}

		return $bid_product;
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

		$variation_id = intval( get_post_meta( $auction_id, Bids::AUCTION_BID_VARIATION_META_KEY, true ) );

		$variation = wc_get_product( $variation_id );

		if ( $variation_id && ! $variation ) {
			if ( ! $this->reset_variation_id( $auction_id ) ) {
				// This is bad.
				return $this->get_product_id( $auction_id );
			}

			$variation_id = $this->get_variation_id( $auction_id );
		}

		return $variation_id;
	}

	/**
	 * Failsafe to Reset the Bid Variation
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 *
	 * @return bool
	 */
	private function reset_variation_id( int $auction_id ): bool {
		$auction = goodbids()->auctions->get( $auction_id );

		/** @var WC_Product_Variable $product */
		$product = $auction->get_bid_product();

		if ( ! $product || ! $product->is_type( 'variable' ) ) {
			return false;
		}

		$variations = $product->get_available_variations();
		$possibles  = [];
		$highest    = 0;

		foreach ( $variations as $variation_data ) {
			$variation = wc_get_product( $variation_data['variation_id'] );

			if ( floatval( $variation->get_price() ) > $highest ) {
				$highest = floatval( $variation->get_price() );
			}

			if ( ! $variation->is_in_stock() ) {
				continue;
			}

			$possibles[] = $variation;
		}

		if ( ! empty( $possibles ) && 1 === count( $possibles ) ) {
			$restore_variation = $possibles[0];
			if ( floatval( $restore_variation->get_price() ) === $highest ) {
				// We found it, back in business!
				$auction->set_bid_variation_id( $restore_variation->get_id() );
				return true;
			}
		}

		// Let's just make a new one based on the last bid.
		$bid_product      = $auction->get_bid_product();
		$last_bid_value   = $auction->get_last_bid_value();
		$increment_amount = $auction->get_bid_increment();
		$new_price        = $last_bid_value + $increment_amount;

		// Add support for new variation.
		$this->update_bid_product_attributes( $bid_product );

		// Create the new Variation.
		$new_variation = $this->create_new_bid_variation( $bid_product->get_id(), $new_price, $auction->get_id() );

		if ( ! $new_variation->save() ) {
			Log::error( 'There was a problem resetting the Bid Variation', compact( 'auction' ) );
			return false;
		}

		$bid_author = get_post_field( 'post_author', $bid_product->get_id() );
		$this->set_variation_author( $new_variation, $bid_author );

		// Set the Bid variation as a meta of the Auction.
		$auction->set_bid_variation_id( $new_variation->get_id() );

		return true;
	}

	/**
	 * Changes the Variation Author to prevent deletion when the user is deleted.
	 *
	 * @param WC_Product_Variation $variation
	 * @param int $author_id
	 *
	 * @return void
	 */
	public function set_variation_author( WC_Product_Variation $variation, int $author_id = 1 ): void {
		$update = [
			'ID'          => $variation->get_id(),
			'post_author' => $author_id,
		];

		$result = wp_update_post( $update );
		if ( is_wp_error( $result ) ) {
			Log::error( $result->get_error_message() );
		}
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

		$product = wc_get_product( $bid_variation_id );
		if ( ! $product ) {
			return null;
		}

		return $product;
	}

	/**
	 * Returns the URL to place a bid on an Auction.
	 *
	 * @since 1.0.0
	 *
	 * @param int $auction_id
	 * @param bool $is_free_bid
	 *
	 * @return string
	 */
	public function get_place_bid_url( int $auction_id, bool $is_free_bid = false ): string {
		$url = trailingslashit( get_permalink( $auction_id ) ) . self::PLACE_BID_SLUG . '/';

		if ( $is_free_bid ) {
			$url = add_query_arg( FreeBids::USE_FREE_BID_PARAM, 1, $url );
		}

		return $url;
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
		$product_id = goodbids()->products->get_parent_product_id( $bid_product_id );
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

				$auction = goodbids()->auctions->get( $auction_id );
				if ( $auction->has_ended() ) {
					return;
				}

				if ( ! $auction->increase_bid() ) {
					Log::error( 'There was a problem trying to increase the Bid Amount', compact( 'auction_id' ) );
				}
			},
			20,
			2
		);
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

				$bid_product_id = $this->get_product_id( $post_id );

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

				$bid_product_id = $this->get_product_id( $post_id );

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

				$bid_product_id = $this->get_product_id( $post_id );

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
				$auction    = goodbids()->auctions->get( $auction_id );

				wp_safe_redirect( $auction->get_url() );
				exit;
			},
			5
		);
	}

	/**
	 * Adjust Free Bid quantities on order complete
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function update_free_bids_on_order_complete(): void {
		add_action(
			'goodbids_order_payment_complete',
			function ( int $order_id, int $auction_id ) {
				// Only process Bid Orders.
				if ( Bids::ITEM_TYPE !== goodbids()->woocommerce->orders->get_type( $order_id ) ) {
					return;
				}

				$auction = goodbids()->auctions->get( $auction_id );

				// Do not award free bids if this order contains a free bid.
				if ( goodbids()->woocommerce->orders->is_free_bid_order( $order_id ) ) {
					// Reduce the Free Bid Count for the user.
					if ( goodbids()->free_bids->redeem( $auction_id, $order_id ) ) {
						goodbids()->notices->add_notice( Notices::FREE_BID_REDEEMED );
					}
					return;
				}

				$max_free_bids       = intval( goodbids()->get_config( 'auctions.default-free-bids' ) );
				$remaining_free_bids = $auction->get_free_bids_available();
				$nth_bid             = $max_free_bids - $remaining_free_bids + 1;
				$bid_order           = wc_get_order( $order_id );

				// Do not award a free bid if payment didn't go through.
				if ( $bid_order->get_total( 'edit' ) > 0 && $bid_order->needs_payment() ) {
					return;
				}

				$details = sprintf(
					// translators: %1$s represents the nth bid placed, %2$s represent the amount of the bid, %3$d represents the Auction ID.
					__( 'Placed %1$s Paid Bid for %2$s on Auction ID %3$d.', 'goodbids' ),
					goodbids()->utilities->get_ordinal( $nth_bid ),
					$bid_order->get_total( 'edit' ),
					$auction_id
				);

				// Potentially award a free bid and reduce the Auction's free bid count.
				$auction->maybe_award_free_bid(
					get_current_user_id(),
					$details,
					true
				);
			},
			10,
			2
		);

		// Notify of Free Bids Awarded that have not already been notified.
		add_action(
			'template_redirect',
			function () {
				if ( get_post_type( get_queried_object_id() ) === goodbids()->auctions->get_post_type() ) {
					$type = FreeBid::TYPE_PAID_BID;
				} elseif ( is_account_page() ) {
					$type = FreeBid::TYPE_REFERRAL;
				} else {
					$type = FreeBid::TYPE_ADMIN_GRANT;
				}

				$free_bids = goodbids()->free_bids->get( get_current_user_id(), FreeBids::STATUS_UNUSED, $type );

				foreach ( $free_bids as $free_bid ) {
					if ( $free_bid->did_awarded_notification() ) {
						continue;
					}

					$free_bid->mark_as_notified();
					goodbids()->free_bids->update( get_current_user_id(), $free_bid->get_id(), $free_bid );

					goodbids()->notices->add_notice( Notices::EARNED_FREE_BID );

					break;
				}
			}
		);
	}

	/**
	 * Hide Bid Products from Non-Super Admins
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function filter_bid_products(): void {
		add_action(
			'pre_get_posts',
			function ( WP_Query $query ) {
				if ( ! is_admin() || is_super_admin() || ! $query->is_main_query() || ! $query->is_post_type_archive( 'product' ) ) {
					return;
				}

				// Hide products in the Bids category.
				$tax_query = $query->get( 'tax_query' );
				if ( ! is_array( $tax_query ) ) {
					$tax_query = [];
				}

				$tax_query[] = [
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => self::ITEM_TYPE,
					'operator' => 'NOT IN',
				];

				$query->set( 'tax_query', $tax_query );
			}
		);
	}

	/**
	 * Reduce the Bid Variation stock to 0 when the Auction ends.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function disable_bid_product_on_auction_end(): void {
		add_action(
			'goodbids_auction_end',
			function ( int $auction_id ) {
				$bid_variation = $this->get_variation( $auction_id );
				$bid_variation->set_stock_quantity( 0 );
				$bid_variation->save();
			},
			11
		);
	}

	/**
	 * Update all Bid Variation authors to match the Bid Product author.
	 *
	 * TODO: REMOVE ME AFTER 2024-04-30
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function update_bid_variation_authors(): void {
		add_action(
			'admin_init',
			function () {
				$bid_products = wc_get_products(
					[
						'limit'    => -1,
						'type'     => 'variable',
						'category' => self::ITEM_TYPE,
						'date_query' => [
							'before' => [
								'year'  => 2024,
								'month' => 4,
								'day'   => 30,
							],
						],
					]
				);

				foreach ( $bid_products as $bid_product ) {
					$author_id  = get_post_field( 'post_author', $bid_product->get_id() );
					$variations = $bid_product->get_available_variations();

					foreach ( $variations as $variation_data ) {
						/** @var WC_Product_Variation $variation */
						$variation = wc_get_product( $variation_data['variation_id'] );
						$variation_author = get_post_field( 'post_author', $variation->get_id() );

						if ( $variation_author !== $author_id ) {
							$this->set_variation_author( $variation, $author_id );
						}
					}
				}
			}
		);
	}
}
