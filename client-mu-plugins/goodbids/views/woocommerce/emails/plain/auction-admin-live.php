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
	esc_html( $user_name )
);

echo "\n\n----------------------------------------\n\n";

printf(
	/* translators: %1$s: Auction title, %2$s: Site title, %3$s: Auction Start Date  */
	esc_html__( 'Just letting you know that the %1$s auction on the %2$s GOODBIDS site went live on %3$s.', 'goodbids' ),
	esc_html( $auction_title ),
	esc_html( $site_name ),
	esc_html( $auction_start_date ),
);

echo "\n\n----------------------------------------\n\n";

esc_html__( 'Auction Title:', 'goodbids' );
esc_html( $auction_title );

echo "\n\n----------------------------------------\n\n";

esc_html__( 'Scheduled Start:', 'goodbids' );
esc_html( $auction_start_date );

echo "\n\n----------------------------------------\n\n";

esc_html__( 'Starting Bid:', 'goodbids' );
esc_html( $auction_starting_bid );

echo "\n\n----------------------------------------\n\n";

esc_html__( 'Bid Increment:', 'goodbids' );
esc_html( $auction_bid_increment );

echo "\n\n----------------------------------------\n\n";

if ( $auction_goal ) :
	esc_html__( 'Auction Goal:', 'goodbids' );
	esc_html( $auction_goal );
endif;

echo "\n\n----------------------------------------\n\n";

if ( $auction_high_bid ) :
	esc_html__( 'Expected High Bid:', 'goodbids' );
	esc_html( $auction_high_bid );
endif;

echo "\n\n----------------------------------------\n\n";

esc_html__( 'Scheduled End:', 'goodbids' );
esc_html( $auction_end_date );

echo "\n\n----------------------------------------\n\n";

esc_html__( 'Bid Extension:', 'goodbids' );
esc_html( $auction_bid_extension );

echo "\n\n----------------------------------------\n\n";

esc_html__( 'Auction Reward:', 'goodbids' );
esc_html( $auction_reward_title );

echo "\n\n----------------------------------------\n\n";

esc_html__( 'Reward Type:', 'goodbids' );
esc_html( $auction_reward_type );

echo "\n\n----------------------------------------\n\n";

if ( $auction_market_value ) :
	esc_html__( 'Fair Market Value:', 'goodbids' );
	esc_html( $auction_market_value );
endif;

echo "\n\n----------------------------------------\n\n";

printf(
	/* translators: %1$s: Auction Start Date/Time, %1$s: Auction Bid Extension */
	esc_html__( 'The auction will end on %1$s unless a bid is placed within {auction.bidExtension} of the scheduled time. Each subsequent bid will extend the auction length by 15 minutes. We will send you an auction summary when the auction has closed.', 'goodbids' ),
	esc_html( $auction_end_date ),
	esc_html( $auction_bid_extension ),
);

echo "\n\n----------------------------------------\n\n";

esc_html__( 'Keep an eye on the auction page for live bidding updates!', 'goodbids' );

echo "\n\n----------------------------------------\n\n";

printf(
	/* translators: %1$s: Bid Now, %2$s: Auction page url */
	'%1$s at %2$s',
	esc_html( $button_text ),
	esc_html( $auction_url )
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
