<?php
/**
 * Auction Reward Reminder
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
	// TODO: Change out '{auction.endTime}' to the auction end time plus 15 days
	printf(
		/* translators: %1$s: Auction Reward, %2$s: Auction title, %3$s: Site Title, %4$s: Auction end time plus 15 days */
		esc_html__( 'You still need to claim the %1$s you earned on the %2$s auction with %3$s. You have until %4$s to claim your reward.', 'goodbids' ),
		'{auction.rewardTitle}',
		'{auction.title}',
		'{site_title}',
		'{auction.endTime}',
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
