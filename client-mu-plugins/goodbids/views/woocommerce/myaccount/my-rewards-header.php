<?php
/**
 * GoodBids My Account > Rewards Header
 *
 * @since 1.0.0
 * @package GoodBids
 */

$earned    = count( goodbids()->sites->get_user_auctions_won() );
$claimed   = count( goodbids()->sites->get_user_reward_orders() );
$unclaimed = $earned - $claimed;
?>
<div class="mb-6 goodbids-rewards-header">
	<ul class="flex flex-wrap gap-6 p-0 m-0 list-none md:grid md:grid-cols-3 goodbids-order-metrics">
		<li class="w-full p-4 rounded-sm sm:w-auto bg-contrast-5">
			<p class="m-0 font-thin uppercase has-x-small-font-size"><?php esc_html_e( 'Earned', 'goodbids' ); ?></p>
			<p class="mt-2 mb-0 text-lg font-bold"><?php echo esc_html( $earned ); ?></p>
		</li>

		<li class="w-full p-4 rounded-sm sm:w-auto bg-contrast-5">
			<p class="m-0 font-thin uppercase has-x-small-font-size"><?php esc_html_e( 'Claimed', 'goodbids' ); ?></p>
			<p class="mt-2 mb-0 text-lg font-bold"><?php echo esc_html( $claimed ); ?></p>
		</li>

		<li class="w-full p-4 rounded-sm sm:w-auto bg-contrast-5">
			<p class="m-0 font-thin uppercase has-x-small-font-size"><?php esc_html_e( 'Unclaimed', 'goodbids' ); ?></p>
			<p class="mt-2 mb-0 text-lg font-bold"><?php echo esc_html( $unclaimed ); ?></p>
		</li>
	</ul>
</div>
