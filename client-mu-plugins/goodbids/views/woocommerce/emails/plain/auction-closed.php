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
	/* translators: %1$s: Auction title, %2$s: Site title, %3$s: Auction Start Date  */
	esc_html__( 'Just letting you know that the %1$s auction on the %2$s GOODBIDS site went live on %3$s.', 'goodbids' ),
	'{auction.title}',
	'{site_title}',
	'{auction.startTime}',
);

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Auction Title:', 'goodbids' );
echo '{auction.title}';

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Scheduled Start:', 'goodbids' );
echo '{auction.startTime}';

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Starting Bid:', 'goodbids' );
echo '{auction.startingBid}';

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Bid Increment:', 'goodbids' );
echo '{auction.bidIncrement}';

echo "\n\n----------------------------------------\n\n";

if ( $auction_goal ) :
	esc_html_e( 'Auction Goal:', 'goodbids' );
	echo '{auction.goal}';
endif;

echo "\n\n----------------------------------------\n\n";

if ( $auction_high_bid ) :
	esc_html_e( 'Expected High Bid:', 'goodbids' );
	echo '{auction.expectedHighBid}';
endif;

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Scheduled End:', 'goodbids' );
echo '{auction.endTime}';

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Bid Extension:', 'goodbids' );
echo '{auction.bidExtension}';

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Auction Reward:', 'goodbids' );
echo '{auction.rewardTitle}';

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Reward Type:', 'goodbids' );
echo '{auction.rewardType}';

echo "\n\n----------------------------------------\n\n";

if ( $auction_market_value ) :
	esc_html_e( 'Fair Market Value:', 'goodbids' );
	echo '{auction.estimatedValue}';
endif;

echo "\n\n----------------------------------------\n\n";

printf(
	/* translators: %1$s: Auction Start Date/Time, %2$s: Auction Bid Extension */
	esc_html__( 'The auction will end on %1$s unless a bid is placed within %2$s of the scheduled time. Each subsequent bid will extend the auction length by 15 minutes. We will send you an auction summary when the auction has closed.', 'goodbids' ),
	'{auction.endTime}',
	'{auction.bidExtension}',
);

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Keep an eye on the auction page for live bidding updates!', 'goodbids' );

echo "\n\n----------------------------------------\n\n";

printf(
	/* translators: %1$s: Bid Now, %2$s: Auction page url */
	'%1$s at %2$s',
	esc_html( $button_text ),
	'{auction.url}'
);

echo "\n\n----------------------------------------\n\n";

printf(
	/* translators: %1$s: Login URL */
	'Login to your site to view additional auction information: %1$s',
	esc_html( $login_url ),
);

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo esc_html( wp_strip_all_tags( wptexturize( $additional_content ) ) );
	echo "\n\n----------------------------------------\n\n";
}

echo wp_kses_post( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );
