<?php
/**
 * Auction Outbid email
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
		/* translators: %1$s: Auction title, %2$s: Auction Reward, %3$s: Auction End Date */
		esc_html__( 'You’re no longer the highest bidder for the %1$s auction. Visit the auction to place a new bid for your chance to win the %2$s. The auction will end on %3$s if no additional bids are placed.', 'goodbids' ),
		'{auction.title}',
		'{auction.rewardTitle}',
		'{auction.endTime}',
	);
	?>
</p>

<p>
	<?php
	printf(
		/* translators: %1$s: Site Name */
		esc_html__( 'Every GoodBid on this auction is a donation to %1$s.', 'goodbids' ),
		'{site_title}',
	);
	?>
</p>

<p class="button-wrapper">
	<?php
	printf(
		/* translators: %1$s: Auction page url, %2$s: Bid Now */
		'<a class="button" href="%1$s">%2$s</a>',
		'{auction.url}',
		esc_html( $button_text )
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
