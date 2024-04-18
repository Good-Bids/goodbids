<?php
/**
 * Stats View
 *
 * @global BidNow $bid_now
 *
 * @since 1.0.1
 * @package GoodBids
 */

use GoodBids\Blocks\BidNow;

?>
<div class="-mt-[0.75rem] grid grid-cols-3 gap-2" style="opacity: 1;">
	<div class="rounded-sm bg-contrast-5 px-4 py-2" role="region" aria-live="polite" aria-atomic="true">
		<span class="block text-sm font-bold">Raised</span>
		<span class="block text-sm">$10</span>
	</div>
	<div class="rounded-sm bg-contrast-5 px-4 py-2" role="region" aria-live="polite" aria-atomic="true">
		<span class="block text-sm font-bold">Last Bid</span>
		<span class="block text-sm">$10</span></div>
	<div class="rounded-sm bg-contrast-5 px-4 py-2" role="region" aria-live="polite" aria-atomic="true">
		<span class="block text-sm font-bold">Bids</span>
		<span class="block text-sm">1</span>
	</div>
</div>
