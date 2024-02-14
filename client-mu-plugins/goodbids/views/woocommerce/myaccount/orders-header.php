<?php
/**
 * GoodBids My Account > Orders Header
 *
 * @since 1.0.0
 * @package GoodBids
 */

?>
<div class="mb-6 goodbids-orders-header">
	<ul class="flex flex-wrap gap-6 p-0 m-0 list-none goodbids-order-metrics">
		<li class="w-full p-4 text-center rounded-sm sm:w-auto bg-contrast-5">
			<p class="m-0 font-thin uppercase has-x-small-font-size"><?php esc_html_e( 'Total Bids', 'goodbids' ); ?></p>
			<p class="mt-2 mb-0 font-semibold"><?php echo esc_html( goodbids()->sites->get_user_total_bids() ); ?></p>
		</li>

		<li class="w-full p-4 text-center rounded-sm sm:w-auto bg-contrast-5">
			<p class="m-0 font-thin uppercase has-x-small-font-size"><?php esc_html_e( 'Total Donated', 'goodbids' ); ?></p>
			<p class="mt-2 mb-0 font-semibold"><?php echo wp_kses_post( wc_price( goodbids()->sites->get_user_total_donated() ) ); ?></p>
		</li>

		<li class="w-full p-4 text-center rounded-sm sm:w-auto bg-contrast-5">
			<p class="m-0 font-thin uppercase has-x-small-font-size"><?php esc_html_e( 'Nonprofits Supported', 'goodbids' ); ?></p>
			<p class="mt-2 mb-0 font-semibold"><?php echo esc_html( goodbids()->sites->get_user_nonprofits_supported() ); ?></p>
		</li>
	</ul>
</div>
