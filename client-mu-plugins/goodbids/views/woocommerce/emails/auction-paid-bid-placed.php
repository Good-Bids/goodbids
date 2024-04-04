<?php
/**
 * Auction Paid Bid Placed email
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package GoodBids
 *
 * @var string $email_heading
 *
 * @var AuctionPaidBidPlaced $instance
 */

use GoodBids\Plugins\WooCommerce\Emails\AuctionPaidBidPlaced;

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
		/* translators: %1$s: Bid amount, %2$s: Auction Title, %3$s: Site title, %4$s: Bid date, %5$s: Site title %6$s: Bid amount */
		esc_html__( 'This is confirmation of your bid for %1$s to the %2$s auction for %3$s on %4$s. You have supported %5$s on this auction with a total donation of %6$s. Save this for your records as proof of your donation.', 'goodbids' ),
		'{order.total}',
		'{auction.title}',
		'{site_title}',
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
				<th class="td" scope="col" style="text-align:left;"><?php esc_html_e( 'Donation', 'goodbids' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="td" scope="col">{auction.title}</td>
				<td class="td" scope="col">{order.total}</td>
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
		echo esc_html__( 'Keep an eye on the Auction page for live bidding updates. We will let you know if you are outbid before the auction closes.', 'goodbids' );
	?>
</p>

<?php

/* * @hooked WC_Emails::email_footer() Output the email footer */
do_action( 'woocommerce_email_footer' );
