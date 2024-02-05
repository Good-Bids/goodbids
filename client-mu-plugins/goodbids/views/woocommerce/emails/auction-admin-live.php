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
		esc_html( $user_name )
	);
	?>
</p>

<p>
	<?php
	printf(
		/* translators: %1$s: Auction title, %2$s: Site title, %3$s: Auction Start Date  */
		esc_html__( 'Just letting you know that the %1$s auction on the %2$s GOODBIDS site went live on %3$s.', 'goodbids' ),
		esc_html( $auction_title ),
		esc_html( $site_name ),
		esc_html( $auction_start_date ),
	);
	?>
</p>


<table>
	<thead>
	<tr>
		<th><?php esc_html__( 'Auction Title', 'goodbids' ); ?></th>
		<th><?php esc_html( $auction_title ); ?></th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td><?php esc_html__( 'Scheduled Start', 'goodbids' ); ?></td>
		<td><?php esc_html( $auction_start_date ); ?></td>
	</tr>
	<tr>
		<td><?php esc_html__( 'Starting Bid', 'goodbids' ); ?></td>
		<td><?php esc_html( $auction_starting_bid ); ?></td>
	</tr>
	<tr>
		<td><?php esc_html__( 'Bid Increment', 'goodbids' ); ?></td>
		<td><?php esc_html( $auction_bid_increment ); ?></td>
	</tr>
	<?php if ( $auction_goal ) : ?>
		<tr>
		<td><?php esc_html__( 'Auction Goal', 'goodbids' ); ?></td>
		<td><?php esc_html( $auction_goal ); ?></td>
		</tr>
	<?php endif; ?>
	<?php if ( $auction_high_bid ) : ?>
		<tr>
		<td><?php esc_html__( 'Expected High Bid', 'goodbids' ); ?></td>
		<td><?php esc_html( $auction_high_bid ); ?></td>
		</tr>
	<?php endif; ?>
	<tr>
		<td><?php esc_html__( 'Scheduled End', 'goodbids' ); ?></td>
		<td><?php esc_html( $auction_end_date ); ?></td>
	</tr>
	<tr>
		<td><?php esc_html__( 'Bid Extension', 'goodbids' ); ?></td>
		<td><?php esc_html( $auction_bid_extension ); ?></td>
	</tr>
	<tr>
		<td><?php esc_html__( 'Auction Reward', 'goodbids' ); ?></td>
		<td><?php esc_html( $auction_reward_title ); ?></td>
	</tr>
	<tr>
		<td><?php esc_html__( 'Reward Type', 'goodbids' ); ?></td>
		<td><?php esc_html( $auction_reward_type ); ?></td>
	</tr>
	<?php if ( $auction_market_value ) : ?>
		<tr>
			<td><?php esc_html__( 'Fair Market Value', 'goodbids' ); ?></td>
			<td><?php esc_html( $auction_market_value ); ?></td>
		</tr>
	<?php endif; ?>
	</tbody>
</table>

<p>
	<?php
	printf(
		/* translators: %1$s: Auction Start Date/Time, %1$s: Auction Bid Extension */
		esc_html__( 'The auction will end on %1$s unless a bid is placed within {auction.bidExtension} of the scheduled time. Each subsequent bid will extend the auction length by 15 minutes. We will send you an auction summary when the auction has closed.', 'goodbids' ),
		esc_html( $auction_end_date ),
		esc_html( $auction_bid_extension ),
	);
	?>
</p>

<p>
	<?php esc_html__( 'Keep an eye on the auction page for live bidding updates!', 'goodbids' ); ?>
</p>

<p class="button-wrapper">
	<?php
	printf(
		/* translators: %1$s: Auction page url, %2$s: Bid Now */
		'<a class="button" href="%1$s">%2$s</a>',
		esc_html( $auction_url ),
		esc_html( $button_text )
	);
	?>
</p>

<p>
	<?php
	printf(
		/* translators: %1$s: Login URL */
		'<a href="%1$s">Login to your site</a> to view additional auction information.',
		esc_html( $login_url ),
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
