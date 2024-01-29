<?php
/**
 * GoodBids My Account > Orders Header
 *
 * @since 1.0.0
 * @package GoodBids
 */

?>
<div class="goodbids-orders-header">
	<dl class="goodbids-order-metrics">
		<dt><?php esc_html_e( 'Total Bids', 'goodbids' ); ?></dt>
		<dd><?php echo esc_html( goodbids()->sites->get_user_total_bids() ); ?></dd>

		<dt><?php esc_html_e( 'Total Donated', 'goodbids' ); ?></dt>
		<dd><?php echo wp_kses_post( wc_price( goodbids()->sites->get_user_total_donated() ) ); ?></dd>

		<dt><?php esc_html_e( 'Nonprofits Supported', 'goodbids' ); ?></dt>
		<dd><?php echo esc_html( goodbids()->sites->get_user_nonprofits_supported() ); ?></dd>
	</dl>
</div>
