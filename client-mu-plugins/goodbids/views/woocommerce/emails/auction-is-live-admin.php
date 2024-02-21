<?php
/**
 * Admin Auction Live Email
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
do_action( 'woocommerce_email_header', $email_heading ); ?>

<p>
	<?php
	printf(
		/* translators: %1$s: Auction title, %2$s: Site title, %3$s: Auction Start Date  */
		esc_html__( 'Just letting you know that the %1$s auction for the %2$s GOODBIDS site went live on %3$s.', 'goodbids' ),
		'{auction.title}',
		'{site_title}',
		'{auction.start_date_time}',
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
				<td class="th"><?php esc_html_e( 'Auction Reward', 'goodbids' ); ?></td>
				<td class="td">{reward.title}</td>
			</tr>
			<?php if ( $auction_estimated_value ) : ?>
				<tr>
					<td class="th"><?php esc_html_e( 'Fair Market Value', 'goodbids' ); ?></td>
					<td class="td"><?php echo wp_kses_post( $auction_estimated_value_formatted ); ?></td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>

<p>
	<?php
	printf(
		'%s <strong>%s</strong> %s %s %s %s. %s.',
		esc_html__( 'The auction will end on', 'goodbids' ),
		'{auction.end_date_time}',
		esc_html__( 'unless a bid is placed within', 'goodbids' ),
		'{auction.bid_extension}',
		esc_html__( 'of the scheduled time. Each subsequent bid will extend the auction length by', 'goodbids' ),
		'{auction.bid_extension}',
		esc_html__( 'We will send you an auction summary when the auction has closed', 'goodbids' )
	);
	?>
</p>

<p>
	<?php
	printf(
		'%s <a href="%s">%s</a> %s',
		esc_html__( 'Keep an eye on', 'goodbids' ),
		'{auction.url}',
		esc_html__( 'the auction page', 'goodbids' ),
		esc_html__( 'for live bidding updates!', 'goodbids' )
	);
	?>
</p>

<?php

/* * @hooked WC_Emails::email_footer() Output the email footer */
do_action( 'woocommerce_email_footer' );
