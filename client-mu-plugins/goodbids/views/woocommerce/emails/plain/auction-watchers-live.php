<?php
/**
 * Auction Watchers Live email (plain text)
 *
 * @var string $email_heading
 * @var string $site_name
 * @var string $user_name
 * @var string $email
 * @var string $button_text
 * @var string $additional_content
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
	/* translators: %1$s: Auction title, %2$s: Site title, %3$s: Auction Goal, %4$s: Auction URL  */
	esc_html__( 'The %1$s auction you are watching is now open for bidding. Visit the auction to support %2$s goal of raising %3$s and place a bid for your chance to win the %4$s!', 'goodbids' ),
	'{auction.title}',
	esc_html( $site_name ),
	'{auction.goal}',
	'{auction.url}'
);

echo "\n\n----------------------------------------\n\n";

printf(
	/* translators: %1$s: Auction Start Date/Time */
	esc_html__( 'Bidding starts at %1$s and the first five paid bidders on this auction will earn a Free Bid.', 'goodbids' ),
	'{auction.start_date_time}',
);

echo "\n\n----------------------------------------\n\n";

printf(
	/* translators: %1$s: Site Name */
	esc_html__( 'Every GoodBid on this auction is a donation to %1$s.', 'goodbids' ),
	esc_html( $site_name ),
);

echo "\n\n----------------------------------------\n\n";

printf(
	/* translators: %1$s: Bid Now, %2$s: Auction page url */
	'%1$s at %2$s',
	esc_html( $button_text ),
	'{auction.url}'
);

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo esc_html( wp_strip_all_tags( wptexturize( $additional_content ) ) );
	echo "\n\n----------------------------------------\n\n";
}

echo wp_kses_post( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );
