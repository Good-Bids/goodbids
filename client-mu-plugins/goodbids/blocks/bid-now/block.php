<?php
/**
 * Bid Now Block
 *
 * @since 1.0.0
 *
 * @package GoodBids
 */

namespace GoodBids\Blocks;

/**
 * Class for Bid Now Block
 *
 * @since 1.0.0
 */
class BidNow {

	/**
	 * @since 1.0.0
	 * @var array
	 */
	private array $block = [];

	/**
	 * @since 1.0.0
	 * @var ?int
	 */
	private ?int $auction_id = null;

	/**
	 * @since 1.0.0
	 * @var ?int
	 */
	private ?int $bid_product_id = null;

	/**
	 * Initialize the block.
	 *
	 * @since 1.0.0
	 *
	 * @param array $block
	 */
	public function __construct( array $block ) {
		$this->block      = $block;
		$this->auction_id = goodbids()->auctions->get_auction_id();

		if ( $this->auction_id ) {
			$this->bid_product_id = goodbids()->auctions->get_bid_product_id( $this->auction_id );
		}
	}

	/**
	 * Determines if the block should be displayed
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function display(): bool {
		if ( ! is_admin() && goodbids()->auctions->get_post_type() !== get_post_type() ) {
			return false;
		}

		return true;
	}

	/**
	 * Returns the text for the Bid Now button
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_button_text(): string {
		$button_text = __( 'GOODBID Now', 'goodbids' );

		if ( $this->bid_product_id && ! is_admin() ) {
			$bid_product = wc_get_product( $this->bid_product_id );
			$button_text = sprintf(
			/* translators: %s: Bid Price */
				__( 'GOODBID %s Now', 'goodbids' ),
				wc_price( $bid_product->get_regular_price() )
			);
		}

		return $button_text;
	}

	/**
	 * Returns the URL for the Bid Now button
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_button_url(): string {
		return $this->bid_product_id && ! is_admin() ? add_query_arg( 'add-to-cart', $this->bid_product_id, wc_get_checkout_url() ) : '#';
	}

	/**
	 * Returns the classes for the block
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_block_classes(): string {
		return 'wp-block-buttons is-vertical is-content-justification-center is-layout-flex wp-block-buttons-is-layout-flex';
	}
}
