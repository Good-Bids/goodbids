<?php
/**
 * Auction Outbid email (plain text)
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package GoodBids
 *
 * @var string $email
 *
 * @var AuctionOutbid $instance
 */

use GoodBids\Plugins\WooCommerce\Emails\AuctionOutbid;

defined( 'ABSPATH' ) || exit;

$instance->plain_text_header();

printf(
	/* translators: %1$s: Auction title, %2$s: Auction Reward, %3$s: Auction End Date */
	esc_html__( 'You’re no longer the highest bidder for the %1$s auction. Visit the auction to place a new bid for your chance to win the %2$s. The auction will end on %3$s if no additional bids are placed.', 'goodbids' ),
	'{auction.title}',
	'{reward.title}',
	'{auction.end_date_time}',
);

echo "\n\n----------------------------------------\n\n";

printf(
	/* translators: %1$s: Site Name */
	esc_html__( 'Every GoodBid on this auction is a donation to %1$s.', 'goodbids' ),
	'{site_title}',
);

$instance->plain_text_footer();

echo wp_kses_post( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );
