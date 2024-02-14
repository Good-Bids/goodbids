<?php
/**
 * GoodBids My Account > Auctions Header
 *
 * @since 1.0.0
 * @package GoodBids
 */

?>
<div class="mb-6 first-line:goodbids-auctions-header">

	<ul class="flex flex-wrap gap-6 p-0 m-0 list-none goodbids-order-metrics">
		<li class="w-full p-4 text-center rounded-sm sm:w-auto bg-contrast-5">
			<p class="m-0 font-thin uppercase has-x-small-font-size"><?php esc_html_e( 'Total Auctions', 'goodbids' ); ?></p>
			<p class="mt-2 mb-0 font-semibold"><?php echo esc_html( count( goodbids()->sites->get_user_participating_auctions() ) ); ?></p>
		</li>

		<li class="w-full p-4 text-center rounded-sm sm:w-auto bg-contrast-5">
			<p class="m-0 font-thin uppercase has-x-small-font-size"><?php esc_html_e( 'Live Auctions', 'goodbids' ); ?></p>
			<p class="mt-2 mb-0 font-semibold"><?php echo esc_html( count( goodbids()->sites->get_user_live_participating_auctions() ) ); ?></p>
		</li>

		<li class="w-full p-4 text-center rounded-sm sm:w-auto bg-contrast-5">
			<p class="m-0 font-thin uppercase has-x-small-font-size"><?php esc_html_e( 'Auctions Won', 'goodbids' ); ?></p>
			<p class="mt-2 mb-0 font-semibold"><?php echo esc_html( count( goodbids()->sites->get_user_auctions_won() ) ); ?></p>
		</li>
	</ul>
</div>
