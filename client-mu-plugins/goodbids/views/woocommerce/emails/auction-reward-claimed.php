<?php
/**
 * Auction Reward Claimed email
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package GoodBids
 *
 * @var string $email_heading
 *
 * @var AuctionRewardClaimed $instance
 */

use GoodBids\Plugins\WooCommerce\Emails\AuctionRewardClaimed;

defined( 'ABSPATH' ) || exit;

/** @var WC_Order $reward_order */
$reward_order = $instance->object;

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading );
?>
<p>
	<?php
	printf(
		/* translators: %1$s: Reward title, %2$s: Site title, %3$s: Auction title, %4$s: Bid date */
		esc_html__( 'This is confirmation that you claimed the %1$s reward for the %2$s %3$s auction on %4$s. Thanks again for your generosity.', 'goodbids' ),
		'{reward.title}',
		'{site_title}',
		'{auction.title}',
		'{order.date}',
	);
	?>
</p>

<p>
	{reward.purchase_note}
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
			$item_totals = $reward_order->get_order_item_totals();

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
		esc_html__( 'You can view your bid history, see Auctions you\'ve won, modify your account information, and more from the %1$s.', 'goodbids' ),
		sprintf(
			'<a href="%s">%s</a>',
			'{user.account_url}',
			esc_html__( 'Bidder Dashboard', 'goodbids' )
		)
	);
	?>
</p>

<?php

/* * @hooked WC_Emails::email_footer() Output the email footer */
do_action( 'woocommerce_email_footer' );
