<?php
/**
 * Auction Ending Soon email (plain text)
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package GoodBids
 *
 * @var AuctionIsEndingSoon $instance
 */

use GoodBids\Plugins\WooCommerce\Emails\AuctionIsEndingSoon;

defined( 'ABSPATH' ) || exit;

/** @var WC_Order $bid_order */
$bid_order = $instance->object;

$instance->plain_text_header();

printf(
	/* translators: %1$s: Auction Title, %2$s: total raised, %3$s: Site title, %4$s: High bid, %5$s: auction url */
	esc_html__( 'The auction you\'re watching, %1$s, is ending soon. It\'s already raised %2$s for %3$s, with a current high bid of %4$s. Let\'s see how much more can we raise: %5$s', 'goodbids' ),
	'{auction.title}',
    '{auction.total_raised}',
    '{site_title}',
    '{auction.high_bid}',
    '{auction.url}'
);

echo "\n\n----------------------------------------\n\n";

echo esc_html__( 'Auction', 'goodbids' ) . ': {auction.title}' . "\n";
echo esc_html__( 'Total Raised', 'goodbids' ) . ': {auction.total_raised}' . "\n";
echo esc_html__( 'High Bid', 'goodbids' ) . ': {auction.high_bid}' . "\n";

echo "\n\n----------------------------------------\n\n";

$instance->plain_text_footer();
