<?php
/**
 * Bid Now Block
 *
 * @since 1.0.0
 *
 * @package GoodBids
 */

namespace GoodBids\Blocks;

use GoodBids\Plugins\ACF\ACFBlock;

/**
 * Class for Bid Now Block
 *
 * @since 1.0.0
 */
class BidNow extends ACFBlock {

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
		parent::__construct( $block );

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

		if ( goodbids()->auctions->has_ended( $this->auction_id ) ) {

			if ( goodbids()->auctions->is_current_user_winner( $this->auction_id ) ) {
				return __( 'Claim Your Reward', 'goodbids' );
			}

			return __( 'Auction has Ended.', 'goodbids' );
		}

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
		if ( is_admin() ) {
			return '#';
		}

		if ( ! goodbids()->auctions->has_ended( $this->auction_id ) ) {
			return $this->bid_product_id ? add_query_arg( 'add-to-cart', $this->bid_product_id, wc_get_checkout_url() ) : '#';
		}

		if ( goodbids()->auctions->is_current_user_winner( $this->auction_id ) ) {
			return goodbids()->auctions->get_claim_reward_url( $this->auction_id );
		}

		return '#'; // Disable button for non-winners.
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

	/**
	 * Determine if Auction has started.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function has_auction_started(): bool {
		if ( ! goodbids()->auctions->has_started( $this->auction_id ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Display a message when auction isn't live yet.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function render_auction_not_started(): void {
		printf(
			'<p>%s</p>',
			esc_html__( 'Auction has not started.', 'goodbids' )
		);
	}
}
