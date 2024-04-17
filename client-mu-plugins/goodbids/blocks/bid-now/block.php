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
			$this->bid_variation_id = $this->auction->get_variation_id();
		}

		$this->register_assets();
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
		$button_text = __( 'GOODBID Now', 'goodbids' );

		if ( ! $this->auction ) {
			return $button_text;
		}

		if ( $this->auction->has_ended() ) {

			if ( $this->auction->is_current_user_winner() ) {
				return __( 'Claim Your Reward', 'goodbids' );
			}

			return __( 'Auction has Ended.', 'goodbids' );
		}

		if ( ! $this->bid_variation_id || is_admin() ) {
			return $button_text;
		}

		if ( $is_free_bid ) {
			return __( 'Place Free Bid', 'goodbids' );
		}

		$bid_variation = wc_get_product( $this->bid_variation_id );

		if ( ! $bid_variation ) {
			return $button_text;
		}

		return sprintf(
			/* translators: %s: Bid Price */
			__( 'GOODBID %s Now', 'goodbids' ),
			wc_price( $bid_variation->get_price() )
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

			return $this->auction->get_place_bid_url( $is_free_bid );
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
		return 'wp-block-buttons is-vertical is-content-justification-center is-layout-flex wp-block-buttons-is-layout-flex standby';
	}

	/**
	 * Determine if Auction has started.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function has_auction_started(): bool {
		if ( ! $this->auction ) {
			return false;
		}

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
		if ( ! goodbids()->free_bids->get_available_count() ) {
			return false;
		}

		// Make sure free bids are allowed.
		if ( ! $this->auction->are_free_bids_allowed() ) {
			return false;
		}

		return true;
	}

	/**
	 * Register Bid Block Assets
	 *
	 * @since 1.0.1
	 *
	 * @return void
	 */
	private function register_assets(): void {
		if ( is_admin() ) {
			return;
		}

		wp_register_style(
			'goodbids-bid-now',
			plugins_url( 'block.css', __FILE__ ),
			[],
			goodbids()->get_version()
		);

		wp_register_script(
			'goodbids-bid-now',
			plugins_url( 'block.js', __FILE__ ),
			[],
			goodbids()->get_version()
		);

		wp_localize_script(
			'goodbids-bid-now',
			'goodbidsBidNow',
			[
				'auctionId' => goodbids()->auctions->get_auction_id(),
				'ajaxURL'   => admin_url( 'admin-ajax.php' ),
			]
		);
	}
}
