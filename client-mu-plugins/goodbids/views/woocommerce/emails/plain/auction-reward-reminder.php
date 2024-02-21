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

// TODO: Change out '{auction.endTime}' to the auction end time plus 15 days
printf(
	/* translators: %1$s: Auction Reward, %2$s: Auction title, %3$s: Site Title, %4$s: Auction end time plus 15 days */
	esc_html__( 'You still need to claim the %1$s you earned on the %2$s auction with %3$s. You have until %4$s to claim your reward.', 'goodbids' ),
	'{auction.rewardTitle}',
	'{auction.title}',
	'{site_title}',
	'{auction.endTime}',
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

echo "\n\n----------------------------------------\n\n";

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo esc_html( wp_strip_all_tags( wptexturize( $additional_content ) ) );
	echo "\n\n----------------------------------------\n\n";
}

echo wp_kses_post( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );
