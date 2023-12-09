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
	 * Initialize Bids
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Create a Bid product when an Auction is created.
		$this->create_bid_product_for_auction();
	}

	/**
	 * Create a Bid product when an Auction is created.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function create_bid_product_for_auction() : void {
		add_action(
			'wp_insert_post',
			function ( $post_id ) {
				// Bail if this is a revision.
				if ( wp_is_post_revision( $post_id ) || 'publish' !== get_post_status( $post_id ) ) {
					return;
				}

				// Bail if not an Auction.
				if ( Auctions::POST_TYPE !== get_post_type( $post_id ) ) {
					return;
				}

				// Bail if the Auction already has a Bid product.
				if ( goodbids()->auctions->has_bid_product( (int) $post_id ) ) {
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
				$bid_product->set_regular_price( goodbids()->auctions->get_bid_increment( (int) $post_id ) );
				$bid_product->set_category_ids( [ $this->get_bids_category_id() ] );
				$bid_product->set_status( 'publish' );

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
	 * @return int
	 */
	public function get_bids_category_id() : int {
		$bids_category = get_term_by( 'slug', 'bids', 'product_cat' );
		if ( ! $bids_category ) {
			$bids_category = wp_insert_term( 'Bids', 'product_cat' );
		}
		return $bids_category->term_id;
	}
}
