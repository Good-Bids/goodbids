<?php
/**
 * Auction Closed email (plain text)
 *
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n";
echo esc_html( wp_strip_all_tags( $email_heading ) );
echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";


printf(
	/* translators: %s: Customer username */
	esc_html__( 'Hi %s,', 'goodbids' ),
	'{user.firstName}'
);

echo "\n\n----------------------------------------\n\n";

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

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Check your email or visit the auction page to see if you won!', 'goodbids' );


echo "\n\n----------------------------------------\n\n";

printf(
	/* translators: %1$s: Auction page url, %2$s: Bid Now */
	'<a class="button" href="%1$s">%2$s</a>',
	'{auction.url}',
	esc_html( $button_text )
);

echo "\n\n----------------------------------------\n\n";

printf(
	/* translators: %1$s: Main site All Auctions page url */
	'Want to support another great GoodBids? <a class="button" href="%1$s">View all auctions</a>',
	'{auction.url}',
	'{main.allAuctionsUrl}'
);

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo esc_html( wp_strip_all_tags( wptexturize( $additional_content ) ) );
	echo "\n\n----------------------------------------\n\n";
}

echo wp_kses_post( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );
