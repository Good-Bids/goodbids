<?php
/**
 * Block: Bid Now
 *
 * @global array $block
 *
 * @since 1.0.0
 * @package GoodBids
 */

$bid_now = new GoodBids\Blocks\BidNow( $block );

// Bail early if initial requirements are not met.
if ( ! $bid_now->display() ) :
	return;
endif;

// Make sure the auction is active.
if ( ! $bid_now->is_auction_active() ) :
	$bid_now->render_auction_not_active();
	return;
endif;
?>
<div <?php block_attr( $block, $bid_now->get_block_classes() ); ?>>
	<a
		href="<?php echo esc_url( $bid_now->get_button_url() ); ?>"
		class="wp-block-button__link wp-element-button w-full block text-center"
	>
		<?php echo wp_kses_post( $bid_now->get_button_text() ); ?>
	</a>
</div>
<div
	id="bidding-block"
	data-auction-id="auction-id"
	data-initial-bids="0"
	data-initial-raised="0"
	data-initial-last-bid="0"
	initial-end-time="no-op"
	initial-free-bids="3"
	initial-user-bids="500"
	initial-last-bidder="user-id"
></div>

