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
	/* translators: %1$s: User last bid, %2$s: Auction Title, %3$s: Auction reward title, %4$s: Site title */
	esc_html__( 'Congratulations! Your %1$s bid was the highest GOODBID for the %2$s auction, and you are the winner. Please claim your %3$s with %4$s within 40 days.', 'goodbids' ),
	'{auction.userLastBid}',
	'{auction.title}',
	'{auction.rewardTitle}',
	'{site_title}',
);

echo "\n\n----------------------------------------\n\n";

echo '{auction.rewardClaimInstructions}';

echo "\n\n----------------------------------------\n\n";

printf(
	/* translators: %1$s: reward checkout page url, %2$s: Claim Your Reward */
	'<a class="button" href="%1$s">%2$s</a>',
	esc_html( $button_reward_url ),
	esc_html( $button_text )
);

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo esc_html( wp_strip_all_tags( wptexturize( $additional_content ) ) );
	echo "\n\n----------------------------------------\n\n";
}

echo wp_kses_post( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );
