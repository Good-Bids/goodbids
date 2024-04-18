<?php
/**
 * Bidding Block Template.
 *
 * @global BidNow $bid_now
 *
 * @since 1.0.1
 * @package GoodBids
 */

use GoodBids\Blocks\BidNow;

//$bid_now->load_view( 'dialog' );
?>

<div class="flex w-full flex-col gap-6 text-md">
	<?php
	// Bidding/Donation Stats.
	$bid_now->load_view( 'stats' );

	// Starting/Ending Clock/Timer.
	$bid_now->load_view( 'clock' );

	// Bid Buttons.
	$bid_now->load_view( 'bid-buttons' );

	// Bid Status.
	$bid_now->load_view( 'status' );

	// Free Bids Info.
	$bid_now->load_view( 'free-bids' );
	?>
</div>
