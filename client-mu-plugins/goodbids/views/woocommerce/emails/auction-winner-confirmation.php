<?php
/**
 * Auction Closed email
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
		/* translators: %1$s: User last bid, %2$s: Auction Title, %3$s: Auction reward title, %4$s: Site title */
		esc_html__( 'Congratulations! Your %1$s bid was the highest GOODBID for the %2$s auction, and you are the winner. Please claim your %3$s with %4$s within 40 days.', 'goodbids' ),
		'{auction.userLastBid}',
		'{auction.title}',
		'{auction.rewardTitle}',
		'{site_title}',
	);
	?>
</p>

<p>
	{auction.rewardClaimInstructions}
</p>


<p class="button-wrapper">
	<?php
	printf(
		/* translators: %1$s: reward checkout page url, %2$s: Claim Your Reward */
		'<a class="button" href="%1$s">%2$s</a>',
		esc_html( $button_reward_url ),
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
