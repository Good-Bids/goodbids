<?php
/**
 * Auction Reward Claimed email (plain text)
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package GoodBids
 *
 * @var AuctionRewardClaimed $instance
 */

use GoodBids\Plugins\WooCommerce\Emails\AuctionRewardClaimed;

defined( 'ABSPATH' ) || exit;

/** @var WC_Order $reward_order */
$reward_order = $instance->object;

$instance->plain_text_header();

printf(
	/* translators: %1$s: Reward title, %2$s: Site title, %3$s: Auction title, %4$s: Reward Claim Date */
	esc_html__( 'This is confirmation that you claimed the %1$s reward for the %2$s %3$s auction on %4$s. Thanks again for your generosity.', 'goodbids' ),
	'{reward.title}',
	'{site_title}',
	'{auction.title}',
	'{order.date}',
);

echo "\n\n----------------------------------------\n\n";

echo '{reward.purchase_note}';

esc_html_e( 'Auction', 'goodbids' ) . ': ' . '{auction.title}' . "\n";
esc_html_e( 'Total Donated', 'goodbids' ) . ': ' . '{auction.user.total_donated}' . "\n";

echo "\n\n----------------------------------------\n\n";

$item_totals = $reward_order->get_order_item_totals();

if ( $item_totals ) {
	foreach ( $item_totals as $total ) {
		echo esc_html( wp_strip_all_tags( $total['label'] ) . ': ' . wp_strip_all_tags( $total['value'] ) ) . "\n";
	}
}

echo "\n\n----------------------------------------\n\n";

printf(
	esc_html__( 'You can view your bid history, see Auctions you\'ve won, modify your account information, and more from the Bidder Dashboard: %1$s', 'goodbids' ),
	'{user.account_url}',
);

$instance->plain_text_footer();
