<?php
/**
 * GoodBids My Account > Orders Header
 *
 * @since 1.0.0
 * @package GoodBids
 */

?>
<div class="goodbids-orders-header">
	<ul class="goodbids-order-metrics flex justify-between">
		<li>
			<strong><?php esc_html_e( 'Total Bids', 'goodbids' ); ?></strong><br>
			<?php echo esc_html( goodbids()->sites->get_user_total_bids() ); ?>
		</li>

		<li>
			<strong><?php esc_html_e( 'Total Donated', 'goodbids' ); ?></strong><br>
			<?php echo wp_kses_post( wc_price( goodbids()->sites->get_user_total_donated() ) ); ?>
		</li>

		<li>
			<strong><?php esc_html_e( 'Nonprofits Supported', 'goodbids' ); ?></strong><br>
			<?php echo esc_html( goodbids()->sites->get_user_nonprofits_supported() ); ?>
		</li>
	</ul>
</div>
