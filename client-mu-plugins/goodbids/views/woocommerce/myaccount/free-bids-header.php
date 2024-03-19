<?php
/**
 * GoodBids Free Bids > Free Bids Header
 *
 * @since 1.0.0
 * @package GoodBids
 */

use GoodBids\Users\FreeBids;

?>
<div class="mb-6 first-line:goodbids-auctions-header">
	<ul class="flex flex-wrap gap-6 p-0 m-0 list-none md:grid md:grid-cols-3 goodbids-order-metrics">
		<li class="w-full p-4 rounded-sm sm:w-auto bg-contrast-5">
			<p class="m-0 uppercase has-x-small-font-size"><?php esc_html_e( 'Available', 'goodbids' ); ?></p>
			<p class="mt-2 mb-0 text-lg font-bold"><?php echo esc_html( goodbids()->free_bids->get_count( get_current_user_id(), FreeBids::STATUS_UNUSED ) ); ?></p>
		</li>

		<li class="w-full p-4 rounded-sm sm:w-auto bg-contrast-5">
			<p class="m-0 uppercase has-x-small-font-size"><?php esc_html_e( 'Earned', 'goodbids' ); ?></p>
			<p class="mt-2 mb-0 text-lg font-bold"><?php echo esc_html( goodbids()->free_bids->get_count() ); ?></p>
		</li>

		<li class="w-full p-4 rounded-sm sm:w-auto bg-contrast-5">
			<p class="m-0 uppercase has-x-small-font-size"><?php esc_html_e( 'Used', 'goodbids' ); ?></p>
			<p class="mt-2 mb-0 text-lg font-bold"><?php echo esc_html( goodbids()->free_bids->get_count( get_current_user_id(), FreeBids::STATUS_USED ) ); ?></p>
		</li>
	</ul>
</div>
