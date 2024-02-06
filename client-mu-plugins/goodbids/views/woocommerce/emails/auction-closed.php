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
		/* translators: %1$s: Auction title, %2$s: Auction total Bids, %3$s: Auction total Raised, %4$s: Site title, %5$s: user Total Bids, %6$s: User Total Donated */
		esc_html__( 'The %1$s auction has ended with %2$s placed and %3$s raised for %4$s. You supported this auction with %5$s for a total donation of %6$s', 'goodbids' ),
		'{auction.title}',
		'{auction.totalBids}',
		'{auction.totalRaised}',
		'{site_title}',
		'{auction.userTotalBids}',
		'{auction.userTotalDonated}'
	);
	?>
</p>

<p>
	<?php
		esc_html_e( 'Check your email or visit the auction page to see if you won!', 'goodbids' );
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

<p>
	<?php
	printf(
		/* translators: %1$s: Main site Auction page url */
		'Want to support another great GoodBids? <a href="%1$s">View all auctions</a>',
		'{main.auctionsUrl}'
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
