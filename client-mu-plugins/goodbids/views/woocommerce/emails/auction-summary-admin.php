<?php
/**
 * Admin Summary Email
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package GoodBids
 *
 * @var string $email_heading
 * @var ?string $auction_estimated_value
 * @var ?string $auction_estimated_value_formatted
 * @var ?string $auction_goal
 * @var ?string $auction_goal_formatted
 * @var ?string $auction_expected_high_bid
 * @var ?string $auction_expected_high_bid_formatted
 */

defined( 'ABSPATH' ) || exit;

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading );
?>

<p>
	<?php
	printf(
		/* translators: %1$s: Auction title, %2$s: Auction end time, %3$s: Auction total Bids, %4$s: Auction total Raised, %5$s: Site name  */
		esc_html__( 'The %1$s GOODBIDS auction ended on %2$s with %3$s bids placed and %4$s raised for %5$s. Check out the summary below for key auction metrics.', 'goodbids' ),
		'{auction.title}',
		'{auction.end_date_time}',
		'{auction.bid_count}',
		'{auction.total_raised}',
		'{site_title}'
	);
	?>
</p>

<div style="margin-bottom: 40px;">
	<table cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
		<tbody>
			<tr>
				<td class="th" scope="col"><?php esc_html_e( 'Auction Title', 'goodbids' ); ?></td>
				<td class="td" scope="col">{auction.title}</td>
			</tr>
			<tr>
				<td class="th"><?php esc_html_e( 'Scheduled Start', 'goodbids' ); ?></td>
				<td class="td">{auction.start_date_time}</td>
			</tr>
			<tr>
				<td class="th"><?php esc_html_e( 'Starting Bid', 'goodbids' ); ?></td>
				<td class="td">{auction.starting_bid}</td>
			</tr>
			<tr>
				<td class="th"><?php esc_html_e( 'Bid Increment', 'goodbids' ); ?></td>
				<td class="td">{auction.bid_increment}</td>
			</tr>
			<?php if ( $auction_goal ) : ?>
				<tr>
					<td class="th"><?php esc_html_e( 'Auction Goal', 'goodbids' ); ?></td>
					<td class="td"><?php echo wp_kses_post( $auction_goal_formatted ); ?></td>
				</tr>
			<?php endif; ?>
			<?php if ( $auction_expected_high_bid ) : ?>
				<tr>
					<td class="th"><?php esc_html_e( 'Expected High Bid', 'goodbids' ); ?></td>
					<td class="td"><?php echo wp_kses_post( $auction_expected_high_bid_formatted ); ?></td>
				</tr>
			<?php endif; ?>
			<tr>
				<td class="th"><?php esc_html_e( 'Scheduled End', 'goodbids' ); ?></td>
				<td class="td">{auction.end_date_time}</td>
			</tr>
			<tr>
				<td class="th"><?php esc_html_e( 'Bid Extension', 'goodbids' ); ?></td>
				<td class="td">{auction.bid_extension}</td>
			</tr>
			<tr>
				<td class="th" colspan="2" style="background-color:#636363;"></td>
			</tr>
			<tr>
				<td class="th"><?php esc_html_e( 'Auction Reward', 'goodbids' ); ?></td>
				<td class="td">{reward.title}</td>
			</tr>
			<?php if ( $auction_estimated_value ) : ?>
				<tr>
					<td class="th"><?php esc_html_e( 'Fair Market Value', 'goodbids' ); ?></td>
					<td class="td"><?php echo wp_kses_post( $auction_estimated_value_formatted ); ?></td>
				</tr>
			<?php endif; ?>
			<tr>
				<td class="th" colspan="2" style="background-color:#636363;"></td>
			</tr>
			<tr>
				<td class="th"><?php esc_html_e( 'Actual End', 'goodbids' ); ?></td>
				<td class="td">{auction.end_date_time}</td>
			</tr>
			<tr>
				<td class="th"><?php esc_html_e( 'Total Bids', 'goodbids' ); ?></td>
				<td class="td">{auction.bid_count}</td>
			</tr>
			<tr>
				<td class="th"><?php esc_html_e( 'Total Raised', 'goodbids' ); ?></td>
				<td class="td">{auction.total_raised}</td>
			</tr>
			<tr>
				<td class="th"><?php esc_html_e( 'High Bid', 'goodbids' ); ?></td>
				<td class="td">{auction.high_bid}</td>
			</tr>
		</tbody>
	</table>
</div>

<p>
	<?php esc_html_e( 'The auction has ended. The winner will be notified by email. The winner has 40 days to claim their reward.', 'goodbids' ); ?>
</p>

<p>
	<?php
	printf(
		'<a href="%s">%s</a> %s',
		'{login_url}',
		esc_html__( 'Login to your site', 'goodbids' ),
		esc_html__( 'to view additional auction information.', 'goodbids' )
	);
	?>
</p>

<?php

/* * @hooked WC_Emails::email_footer() Output the email footer */
do_action( 'woocommerce_email_footer' );
