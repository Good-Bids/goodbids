<?php
/**
 * Clock View
 *
 * @global BidNow $bid_now
 *
 * @since 1.0.1
 * @package GoodBids
 */

use GoodBids\Blocks\BidNow;

?>
<div class="relative">
	<div class="flex items-center gap-3 px-4" style="opacity: 1;">
		<div class="flex items-center" style="opacity: 1;">
			<svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" viewBox="0 0 24 24" aria-hidden="true">
				<path fill="currentColor" d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10Zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm1-8h4v2h-6V7h2v5Z"></path>
			</svg>
		</div>
		<span role="timer" aria-live="polite" style="opacity: 1;">
			<b>Ending in 6 days</b> if nobody else bids.
		</span>
	</div>
</div>

<div class="flex flex-col items-center justify-center">
	<p>
		<span class="font-bold">LAST MINUTE BIDS automatically extend this auction's close date - </span>
		Smart bidders bid early.
	</p>
</div>
