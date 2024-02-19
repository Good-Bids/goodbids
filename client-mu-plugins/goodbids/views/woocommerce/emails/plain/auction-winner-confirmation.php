<?php
/**
 * Auction Winner Confirmation email (plain text)
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package GoodBids
 *
 * @var string $email
 *
 * @var AuctionWinnerConfirmation $instance
 */

use GoodBids\Plugins\WooCommerce\Emails\AuctionWinnerConfirmation;

defined( 'ABSPATH' ) || exit;

$instance->plain_text_header();

echo "\n\n----------------------------------------\n\n";

printf(
	/* translators: %1$s: User last bid, %2$s: Auction Title, %3$s: Auction reward title, %4$s: Site title */
	esc_html__( 'Congratulations! Your %1$s bid was the highest GOODBID for the %2$s auction, and you are the winner. Please claim your %3$s with %4$s within 40 days.', 'goodbids' ),
	'{auction.userLastBid}',
	'{auction.title}',
	'{reward.title}',
	'{site_title}',
);

echo "\n\n----------------------------------------\n\n";

echo '{reward.purchase_note}';

$instance->plain_text_footer();

echo wp_kses_post( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );
