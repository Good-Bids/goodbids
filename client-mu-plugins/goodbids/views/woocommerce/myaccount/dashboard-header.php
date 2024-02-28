<?php
/**
 * GoodBids My Account > Dashboard Header
 *
 * @since 1.0.0
 * @package GoodBids
 */

use GoodBids\Auctions\Bids;

?>
<div class="mb-6 first-line:goodbids-auctions-header">
	<ul class="flex flex-wrap gap-6 p-0 m-0 list-none md:grid md:grid-cols-3 goodbids-order-metrics">
		<li class="w-full p-4 rounded-sm sm:w-auto bg-contrast-5">
			<p class="m-0 font-thin uppercase has-x-small-font-size"><?php esc_html_e( 'Total Donated', 'goodbids' ); ?></p>
			<p class="mt-2 mb-0 text-lg font-bold"><?php echo wp_kses_post( wc_price( goodbids()->sites->get_user_total_donated() ) ); ?></p>
		</li>

		<li class="w-full p-4 rounded-sm sm:w-auto bg-contrast-5">
			<p class="m-0 font-thin uppercase has-x-small-font-size"><?php esc_html_e( 'Nonprofits Supported', 'goodbids' ); ?></p>
			<p class="mt-2 mb-0 text-lg font-bold"><?php echo esc_html( goodbids()->sites->get_user_nonprofits_supported() ); ?></p>
		</li>

		<li class="w-full p-4 text-base rounded-sm sm:w-auto bg-contrast">
			<p class="m-0 font-thin uppercase has-x-small-font-size"><?php esc_html_e( 'Free Bids', 'goodbids' ); ?></p>
			<div class="flex items-center justify-between">
				<p class="mt-2 mb-0 text-lg font-bold"><?php echo esc_html( count( goodbids()->users->get_free_bids( null, Bids::FREE_BID_STATUS_UNUSED ) ) ); ?></p>
				<a class="px-3 py-1 btn-fill-secondary" href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) . 'my-referrals/' ); ?>"><?php esc_html_e( 'Earn more', 'goodbids' ); ?></a>
			</div>
		</li>
	</ul>
</div>