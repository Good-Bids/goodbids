<?php
/**
 * Auction Watchers Live email
 *
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p>
	<?php
	printf(
		/* translators: %s: Customer username */
		esc_html__( 'Hi %s,', 'goodbids' ),
		'{user.firstName}'
	);
	?>
</p>

<p>
	<?php
	printf(
		/* translators: %1$s: Auction title, %2$s: Auction end time, %3$s: Auction total Bids, %4$s: Auction total Raised, %5$s: Site name  */
		esc_html__( 'The %1$s GOODBIDS auction ended on %2$s with %3$s placed and %4$s raised for %5$s. Check out the summary below for key auction metrics.', 'goodbids' ),
		'{auction.title}',
		'{auction.actualEndDate}',
		'{auction.totalBids}',
		'{auction.totalRaised}',
		'{site_title}'
	);
	?>
</p>


<div style="margin-bottom: 40px;">
	<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
		<thead>
			<tr>
				<th class="td" scope="col"><?php esc_html_e( 'Auction Title', 'goodbids' ); ?></th>
				<th class="td" scope="col">{auction.title}</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="td"><?php esc_html_e( 'Scheduled Start', 'goodbids' ); ?></td>
				<td class="td">{auction.startTime}</td>
			</tr>
			<tr>
				<td class="td"><?php esc_html_e( 'Starting Bid', 'goodbids' ); ?></td>
				<td class="td">{auction.startingBid}</td>
			</tr>
			<tr>
				<td class="td"><?php esc_html_e( 'Bid Increment', 'goodbids' ); ?></td>
				<td class="td">{auction.bidIncrement}</td>
			</tr>
			<?php if ( $auction_goal ) : ?>
				<tr>
					<td class="td"><?php esc_html_e( 'Auction Goal', 'goodbids' ); ?></td>
					<td class="td"><?php echo esc_html( $auction_goal ); ?></td>
				</tr>
			<?php endif; ?>
			<?php if ( $auction_high_bid ) : ?>
				<tr>
					<td class="td"><?php esc_html_e( 'Expected High Bid', 'goodbids' ); ?></td>
					<td class="td"><?php echo esc_html( $auction_high_bid ); ?></td>
				</tr>
			<?php endif; ?>
			<tr>
				<td class="td"><?php esc_html_e( 'Scheduled End', 'goodbids' ); ?></td>
				<td class="td">{auction.endTime}</td>
			</tr>
			<tr>
				<td class="td"><?php esc_html_e( 'Bid Extension', 'goodbids' ); ?></td>
				<td class="td">{auction.bidExtension}</td>
			</tr>
			<tr>
				<td class="td"></td>
				<td class="td"></td>
			</tr>
			<tr>
				<td class="td"><?php esc_html_e( 'Auction Reward', 'goodbids' ); ?></td>
				<td class="td">{auction.rewardTitle}</td>
			</tr>
			<tr>
				<td class="td"><?php esc_html_e( 'Reward Type', 'goodbids' ); ?></td>
				<td class="td">{auction.rewardType}</td>
			</tr>
			<?php if ( $auction_estimated_value ) : ?>
				<tr>
					<td class="td"><?php esc_html_e( 'Fair Market Value', 'goodbids' ); ?></td>
					<td class="td"><?php echo esc_html( $auction_estimated_value ); ?></td>
				</tr>
			<?php endif; ?>
			<tr>
				<td class="td"></td>
				<td class="td"></td>
			</tr>
			<tr>
				<td class="td"><?php esc_html_e( 'Actual End', 'goodbids' ); ?></td>
				<td class="td">{auction.actualEndDate}</td>
			</tr>
			<tr>
				<td class="td"><?php esc_html_e( 'Total Bids', 'goodbids' ); ?></td>
				<td class="td">{auction.totalBids}</td>
			</tr>
			<tr>
				<td class="td"><?php esc_html_e( 'Total Raised', 'goodbids' ); ?></td>
				<td class="td">{auction.totalRaised}</td>
			</tr>
			<tr>
				<td class="td"><?php esc_html_e( 'High Bid', 'goodbids' ); ?></td>
				<td class="td">{auction.highBid}</td>
			</tr>
		</tbody>
	</table>
</div>

<p>
	<?php esc_html_e( 'The auction has ended. The auctioneer has chosen a winner. The winner will be notified by email and will be notified by text. The winner has 40 days to claim their reward. We will send you a confirmation email once the reward has been claimed.', 'goodbids' ); ?>
</p>

<p>
	<?php
	printf(
		/* translators: %1$s: Login URL */
		'<a href="%1$s">Login to your site</a> to view additional auction information.',
		'{auction.loginUrl}',
	);
	?>
</p>


<?php
/** * Show user-defined additional content - this is set in each email's settings. */
if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

/* * @hooked WC_Emails::email_footer() Output the email footer */
do_action( 'woocommerce_email_footer', $email );
