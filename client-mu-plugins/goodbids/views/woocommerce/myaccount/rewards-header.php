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
<div class="goodbids-rewards-header">
	<ul class="goodbids-rewards-metrics flex justify-between">
		<li>
			<strong><?php esc_html_e( 'Earned', 'goodbids' ); ?></strong><br>
			<?php echo esc_html( $earned ); ?>
		</li>

		<li>
			<strong><?php esc_html_e( 'Claimed', 'goodbids' ); ?></strong><br>
			<?php echo esc_html( $claimed ); ?>
		</li>

		<li>
			<strong><?php esc_html_e( 'Unclaimed', 'goodbids' ); ?></strong><br>
			<?php echo esc_html( $unclaimed ); ?>
		</li>
	</ul>
</div>
