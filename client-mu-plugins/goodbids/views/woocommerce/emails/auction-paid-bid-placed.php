<?php
/**
 * Auction Paid Pid Placed email
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

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading );
?>
<p>
	<?php
	printf(
		/* translators: %1$s: Bid amount, %2$s: Auction Title, %3$s: Site title, %4$s: Bid date, %5$s: Site title %6$s: Bid amount */
		esc_html__( 'This is confirmation of your bid for %1$s to the %2$s auction for %3$s on %4$s. You have supported %5$s on this auction with a total donation of %6$s.', 'goodbids' ),
		'TODO{auction.bid}',
		'{auction.title}',
		'{site_title}',
		'TODO{auction.bid.date}',
		'{site_title}',
		'TODO{auction.bid.amount}'
	);
	?>
</p>

<?php // TODO may need to repalce this with a partial template? Maybe pull in class-wc-email.php -> order_details ?>
<div style="margin-bottom: 40px;">
	<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
		<thead>
			<tr>
				<th class="td" scope="col" style="text-align:left;"><?php esc_html_e( 'Auction', 'goodbids' ); ?></th>
				<th class="td" scope="col" style="text-align:left;"><?php esc_html_e( 'Donation', 'goddbids' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="td" scope="col">{auction.title}</td>
				<td class="td" scope="col">{auction.bid}</td>
			</tr>
			<tr>
				<td class="td" scope="col"><b><?php esc_html_e( 'Subtotal', 'goodbids' ); ?></b></td>
				<td class="td" scope="col">{order.subtotal}</td>
			</tr>
			<tr>
				<td class="td" scope="col"><b><?php esc_html_e( 'Payment Method', 'goodbids' ); ?></b></td>
				<td class="td" scope="col">{order.payment_method}</td>
			</tr>
			<tr>
				<td class="td" scope="col"><b><?php esc_html_e( 'Payment Method', 'goodbids' ); ?></b></td>
				<td class="td" scope="col">{order.payment_method}</td>
			</tr>
			<tr>
				<td class="td" scope="col"><b><?php esc_html_e( 'Total', 'goodbids' ); ?></b></td>
				<td class="td" scope="col">{order.total}</td>
			</tr>
		</tbody>
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
