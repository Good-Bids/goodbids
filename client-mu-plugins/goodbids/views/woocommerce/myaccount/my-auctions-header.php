<?php
/**
 * GoodBids My Account > Auctions Header
 *
 * @since 1.0.0
 * @package GoodBids
 */

?>
<div class="goodbids-auctions-header">
	<ul class="goodbids-auctions-metrics" style="display: flex; justify-content: space-between;">
		<li>
			<strong><?php esc_html_e( 'Total Auctions', 'goodbids' ); ?></strong><br>
			<?php echo esc_html( count( goodbids()->sites->get_user_participating_auctions() ) ); ?>
		</li>

		<li>
			<strong><?php esc_html_e( 'Auctions Won', 'goodbids' ); ?></strong><br>
			<?php echo esc_html( count( goodbids()->sites->get_user_auctions_won() ) ); ?>
		</li>
	</ul>
</div>
