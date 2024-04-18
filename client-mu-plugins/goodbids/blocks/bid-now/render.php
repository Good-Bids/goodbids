<?php
/**
 * Block: Bid Now
 *
 * @global array $block
 * @since 1.0.0
 *
 * @package GoodBids
 */

$bid_now = new GoodBids\Blocks\BidNow( $block );

// Bail early if initial requirements are not met.
if ( ! $bid_now->display() ) :
	return;
endif;

// Make sure the auction has started.
if ( ! $bid_now->has_auction_started() ) :
	$bid_now->render_auction_not_started();
	return;
endif;
?>
<div <?php block_attr( $block, $bid_now->get_block_classes() ); ?>>
	<?php $bid_now->load_view( 'template' ); ?>
</div>
