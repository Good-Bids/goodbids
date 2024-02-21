<?php
/**
 * Auction Watchers Live email (plain text)
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
	/* translators: %1$s: Auction title, %2$s: Auction end time, %3$s: Auction total Bids, %4$s: Auction total Raised, %5$s: Site name  */
	esc_html__( 'The %1$s GOODBIDS auction ended on %2$s with %3$s placed and %4$s raised for %5$s. Check out the summary below for key auction metrics.', 'goodbids' ),
	'{auction.title}',
	'{auction.actualEndDate}',
	'{auction.totalBids}',
	'{auction.totalRaised}',
	'{site_title}'
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
	echo esc_html( $auction_goal );
endif;

echo "\n\n----------------------------------------\n\n";

if ( $auction_goal ) :
	esc_html_e( 'Expected High Bid:', 'goodbids' );
	echo esc_html( $auction_goal );
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

if ( $auction_estimated_value ) :
	esc_html_e( 'Fair Market Value:', 'goodbids' );
	echo esc_html( $auction_estimated_value );
endif;

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Actual End', 'goodbids' );
echo '{auction.actualEndDate}';

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Total Bids', 'goodbids' );
echo '{auction.totalBids}';

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Total Raised', 'goodbids' );
echo '{auction.totalRaised}';

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'High Bid', 'goodbids' );
echo '{auction.highBid}';

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'The auction has ended. The auctioneer has chosen a winner. The winner will be notified by email and will be notified by text. The winner has 40 days to claim their reward. We will send you a confirmation email once the reward has been claimed.', 'goodbids' );

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
