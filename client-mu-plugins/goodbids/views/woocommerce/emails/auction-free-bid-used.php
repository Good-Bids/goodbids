<?php
/**
 * Auction Free Bid Order email
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package GoodBids
 *
 * @var string $email_heading
 *
 * @var AuctionFreeBidUsed $instance
 */

use GoodBids\Plugins\WooCommerce\Emails\AuctionFreeBidUsed;

defined( 'ABSPATH' ) || exit;

/** @var WC_Order $bid_order */
$bid_order = $instance->object;

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading );
?>
<p>
	<?php
	printf(
		/* translators: %1$s: Auction Title, %2$s: Bid Order date, %3$s: Site title, %4$s: Total User Donated */
		esc_html__( 'This is your confirmation of your Free Bid for the %1$s auction on %2$s. You have supported %3$s on this auction for a total donation of %4$s.', 'goodbids' ),
		'{auction.title}',
		'{order.date}',
		'{site_title}',
		'{auction.user.total_donated}'
	);
	?>
</p>

<div style="margin-bottom: 40px;">
	<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
		<thead>
			<tr>
				<th class="td" scope="col" style="text-align:left;"><?php esc_html_e( 'Auction', 'goodbids' ); ?></th>
				<th class="td" scope="col" style="text-align:left;"><?php esc_html_e( 'Total Donated', 'goodbids' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="td" scope="col">{auction.title}</td>
				<td class="td" scope="col">{auction.user.total_donated}</td>
			</tr>
		</tbody>
		<tfoot>
			<?php
			$item_totals = $bid_order->get_order_item_totals();

			if ( $item_totals ) {
				$i = 0;
				foreach ( $item_totals as $total ) {
					$i++;
					?>
					<tr>
						<th class="td" scope="row" style="text-align:left; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>"><?php echo wp_kses_post( $total['label'] ); ?></th>
						<td class="td" style="text-align:left; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>"><?php echo wp_kses_post( $total['value'] ); ?></td>
					</tr>
					<?php
				}
			}
			?>
		</tfoot>
	</table>
</div>

<p>
	<?php
	printf(
		/* translators: %1$s: Free Bids, %2$s: Refer a Friend link */
		esc_html__( 'You have %1$s Free Bids that can be used in any eligible auction. You can earn more by placing one of the first five paid bids in any auction, or by %2$s.', 'goodbids' ),
		'{user.free_bid_count}',
		sprintf(
			'<a href="%s">%s</a>.',
			'{user.referral_link}',
			esc_html__( 'referring a friend', 'goodbids' )
		)
	);
	?>
</p>

<p>
	<?php
		esc_html_e( 'Keep an eye on the Auction page for live bidding updates. We will let you know if you are outbid before the auction closes.', 'goodbids' );
	?>
</p>

<?php

/* * @hooked WC_Emails::email_footer() Output the email footer */
do_action( 'woocommerce_email_footer' );
