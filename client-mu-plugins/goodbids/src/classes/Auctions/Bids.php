<?php
/**
 * Bids Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Auctions;

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
	const AUCTION_BID_META_KEY = 'gb_bid_product_id';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const BID_AUCTION_META_KEY = Auctions::PRODUCT_AUCTION_META_KEY;

	/**
	 * Initialize Bids
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
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

		// Prevent access to Bid product.
		$this->prevent_access_to_bid_product();
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
					// TODO: Log error.
					return;
				}

				$bid_title = sprintf(
					'%s %s (%s: %s)',
					__( 'Bid for', 'goodbids' ),
					get_the_title( $post_id ),
					__( 'ID', 'goodbids' ),
					$post_id
				);

				// Create a new Bid product.
				$bid_product = new \WC_Product_Simple();
				$bid_product->set_name( $bid_title );
				$bid_product->set_slug( sanitize_title( $bid_title ) );
				$bid_product->set_regular_price( $starting_bid );
				$bid_product->set_category_ids( [ $this->get_bids_category_id() ] );
				$bid_product->set_status( 'publish' );
				$bid_product->set_manage_stock( true );
				$bid_product->set_stock_quantity( 1 );
				$bid_product->set_sold_individually( true );
				$bid_product->set_virtual( true );

				try {
					$bid_product->set_sku( 'BID-' . $post_id );
				} catch ( \WC_Data_Exception $e ) {
					// Do nothing.
				}

				$bid_product = apply_filters( 'goodbids_bid_product_create', $bid_product, $post_id );

				if ( ! $bid_product->save() ) {
					// TODO: Log error.
					return;
				}

				// Set the Bid product as a meta of the Auction.
				goodbids()->auctions->set_bid_product_id( (int) $post_id, $bid_product->get_id() );
			},
			10
		);
	}

	/**
	 * Retrieve the Bids category ID, or create it if it doesn't exist.
	 *
	 * @since 1.0.0
	 *
	 * @return ?int
	 */
	public function get_bids_category_id(): ?int {
		$bids_category = get_term_by( 'slug', Auctions::ORDER_TYPE_BID, 'product_cat' );

		if ( ! $bids_category ) {
			$bids_category = wp_insert_term( 'Bids', 'product_cat' );

			if ( is_wp_error( $bids_category ) ) {
				// TODO: Log error.
				return null;
			}

			return $bids_category['term_id'];
		}

		return $bids_category->term_id;
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
		$auction_id = get_post_meta( $bid_product_id, self::BID_AUCTION_META_KEY, true );
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
				if ( ! goodbids()->woocommerce->is_bid_order( $order_id ) ) {
					return;
				}

				if ( goodbids()->auctions->has_ended( $auction_id ) ) {
					return;
				}

				$bid_product = goodbids()->auctions->get_bid_product( $auction_id );

				if ( ! $bid_product ) {
					// TODO: Log error.
					return;
				}

				$increment = goodbids()->auctions->get_bid_increment( $auction_id );
				$bid_price = floatval( $bid_product->get_regular_price( 'edit' ) );

				$bid_product->set_stock_quantity( 1 );
				$bid_product->set_regular_price( $bid_price + $increment );
				$bid_product->save();
			},
			10,
			2
		);
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

				$bid_product_id = goodbids()->auctions->get_bid_product_id( $post_id );

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

				$bid_product_id = goodbids()->auctions->get_bid_product_id( $post_id );

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

				$bid_product_id = goodbids()->auctions->get_bid_product_id( $post_id );

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
	 * Disable access to Bid product singular product page.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function prevent_access_to_bid_product(): void {
		add_action(
			'template_redirect',
			function (): void {
				if ( ! is_singular( 'product' ) ) {
					return;
				}

				$product_id = get_the_ID();

				if ( Auctions::ORDER_TYPE_BID !== goodbids()->auctions->get_product_type( $product_id ) ) {
					return;
				}

				$auction_id = $this->get_auction_id( $product_id );

				if ( ! $auction_id ) {
					wp_safe_redirect( home_url() );
					exit;
				}

				wp_safe_redirect( get_permalink( $auction_id ), 301 );
				exit;
			}
		);
	}
}
