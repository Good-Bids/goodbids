<?php
/**
 * Auction Outbid email (plain text)
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
	/* translators: %1$s: Auction title, %2$s: Auction Reward, %3$s: Auction End Date */
	esc_html__( 'You’re no longer the highest bidder for the %1$s auction. Visit the auction to place a new bid for your chance to win the %2$s. The auction will end on %3$s if no additional bids are placed.', 'goodbids' ),
	'{auction.title}',
	'{auction.rewardTitle}',
	'{auction.endTime}',
);

echo "\n\n----------------------------------------\n\n";

printf(
	/* translators: %1$s: Site Name */
	esc_html__( 'Every GoodBid on this auction is a donation to %1$s.', 'goodbids' ),
	'{site_name}',
);

echo "\n\n----------------------------------------\n\n";

printf(
	/* translators: %1$s: Bid Now, %2$s: Auction page url */
	'%1$s at %2$s',
	esc_html( $button_text ),
	esc_html( $auction_url )
);

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo esc_html( wp_strip_all_tags( wptexturize( $additional_content ) ) );
	echo "\n\n----------------------------------------\n\n";
}

echo wp_kses_post( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );
