<?php
/**
 * Admin Auction Live Email
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package GoodBids
 *
 * @var string $email_heading
 * @var string $email
 * @var ?string $auction_estimated_value
 * @var ?string $auction_goal
 * @var ?string $auction_expected_high_bid
 */

defined( 'ABSPATH' ) || exit;

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p>
	<?php
	printf(
		/* translators: %1$s: Auction title, %2$s: Site title, %3$s: Auction Start Date  */
		esc_html__( 'Just letting you know that the %1$s auction on the %2$s GOODBIDS site went live on %3$s.', 'goodbids' ),
		'{auction.title}',
		'{site_title}',
		'{auction.start_date_time}',
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
				<td class="td">{auction.start_date_time}</td>
			</tr>
			<tr>
				<td class="td"><?php esc_html_e( 'Starting Bid', 'goodbids' ); ?></td>
				<td class="td">{auction.starting_bid}</td>
			</tr>
			<tr>
				<td class="td"><?php esc_html_e( 'Bid Increment', 'goodbids' ); ?></td>
				<td class="td">{auction.bid_increment}</td>
			</tr>
			<?php if ( $auction_goal ) : ?>
				<tr>
					<td class="td"><?php esc_html_e( 'Auction Goal', 'goodbids' ); ?></td>
					<td class="td"><?php echo esc_html( $auction_goal ); ?></td>
				</tr>
			<?php endif; ?>
			<?php if ( $auction_expected_high_bid ) : ?>
				<tr>
					<td class="td"><?php esc_html_e( 'Expected High Bid', 'goodbids' ); ?></td>
					<td class="td"><?php echo esc_html( $auction_expected_high_bid ); ?></td>
				</tr>
			<?php endif; ?>
			<tr>
				<td class="td"><?php esc_html_e( 'Scheduled End', 'goodbids' ); ?></td>
				<td class="td">{auction.end_date_time}</td>
			</tr>
			<tr>
				<td class="td"><?php esc_html_e( 'Bid Extension', 'goodbids' ); ?></td>
				<td class="td">{auction.bid_extension}</td>
			</tr>
			<tr>
				<td class="td"><?php esc_html_e( 'Auction Reward', 'goodbids' ); ?></td>
				<td class="td">{reward.title}</td>
			</tr>
			<tr>
				<td class="td"><?php esc_html_e( 'Reward Type', 'goodbids' ); ?></td>
				<td class="td">{reward.type}</td>
			</tr>
			<?php if ( $auction_estimated_value ) : ?>
				<tr>
					<td class="td"><?php esc_html_e( 'Fair Market Value', 'goodbids' ); ?></td>
					<td class="td"><?php echo esc_html( $auction_estimated_value ); ?></td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>

<p>
	<?php
	printf(
		/* translators: %1$s: Auction Start Date/Time, %2$s: Auction Bid Extension, %2$s: Auction Bid Extension */
		esc_html__( 'The auction will end on %1$s unless a bid is placed within %2$s of the scheduled time. Each subsequent bid will extend the auction length by %3$s minutes. We will send you an auction summary when the auction has closed.', 'goodbids' ),
		'{auction.end_date_time}',
		'{auction.bid_extension}',
		'{auction.bid_extension}',
	);
	?>
</p>

<p>
	<?php echo esc_html__( 'Keep an eye on the auction page for live bidding updates!', 'goodbids' ); ?>
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
do_action( 'woocommerce_email_footer', $email );
