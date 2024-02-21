<?php
/**
 * Bid Now Block
 *
 * @since 1.0.0
 *
 * @package GoodBids
 */

namespace GoodBids\Blocks;

use GoodBids\Auctions\Auction;
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
	 * @var ?Auction
	 */
	private ?Auction $auction = null;

	/**
	 * @since 1.0.0
	 * @var ?int
	 */
	private ?int $bid_variation_id = null;

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
			$this->auction          = goodbids()->auctions->get( $this->auction_id );
			$this->bid_variation_id = goodbids()->bids->get_variation_id( $this->auction_id );
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
	 * @param bool $is_free_bid
	 *
	 * @return string
	 */
	public function get_button_text( bool $is_free_bid = false ): string {
		if ( $this->auction->has_ended() ) {

			if ( $this->auction->is_current_user_winner() ) {
				return __( 'Claim Your Reward', 'goodbids' );
			}

			return __( 'Auction has Ended.', 'goodbids' );
		}

		$button_text = __( 'GOODBID Now', 'goodbids' );

		if ( ! $this->bid_variation_id || is_admin() ) {
			return $button_text;
		}

		if ( $is_free_bid ) {
			return __( 'Place Free Bid', 'goodbids' );
		}

		$bid_variation = wc_get_product( $this->bid_variation_id );

		return sprintf(
			/* translators: %s: Bid Price */
			__( 'GOODBID %s Now', 'goodbids' ),
			wc_price( $bid_variation->get_regular_price() )
		);
	}

	/**
	 * Returns the URL for the Bid Now button
	 *
	 * @since 1.0.0
	 *
	 * @param bool $is_free_bid
	 *
	 * @return string
	 */
	public function get_button_url( bool $is_free_bid = false ): string {
		if ( is_admin() ) {
			return '#';
		}

		if ( ! $this->auction->has_ended() ) {
			if ( ! $this->bid_variation_id ) {
				return '#';
			}

			$url = add_query_arg( 'add-to-cart', $this->bid_variation_id, wc_get_checkout_url() );

			if ( $is_free_bid ) {
				$url = add_query_arg( 'use-free-bid', 1, $url );
			}

			return $url;
		}

		// Double check.
		if ( $is_free_bid ) {
			return '#';
		}

		if ( $this->auction->is_current_user_winner() ) {
			return goodbids()->rewards->get_claim_reward_url( $this->auction_id );
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
		if ( ! $this->auction->has_started() ) {
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

	/**
	 * Determine if the Free Bid button should be displayed.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function show_free_bid_button(): bool {
		// Make sure the auction hasn't ended.
		if ( $this->auction->has_ended() ) {
			return false;
		}

		// Make sure the user is logged in.
		if ( ! is_user_logged_in() ) {
			return false;
		}

		// Make sure the user has free bids.
		if ( ! goodbids()->users->get_available_free_bid_count() ) {
			return false;
		}

		// Make sure free bids are allowed.
		if ( ! $this->auction->are_free_bids_allowed() ) {
			return false;
		}

		return true;
	}
}
