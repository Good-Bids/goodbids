<?php
/**
 * Auction Free Bid Used email (plain text)
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package GoodBids
 *
 * @var AuctionRewardReminder $instance
 */

use GoodBids\Plugins\WooCommerce\Emails\AuctionRewardReminder;

defined( 'ABSPATH' ) || exit;

/** @var WC_Order $bid_order */
$bid_order = $instance->object;

$instance->plain_text_header();

printf(
	/* translators: %1$s: Auction Title, %2$s: Bid Order Date, %3$s: Site title, %4$s: Total User Donated */
	esc_html__( 'This is your confirmation of your Free Bid for the %1$s auction on %2$s. You have supported %3$s on this auction for a total donation of %4$s.', 'goodbids' ),
	'{auction.title}',
	'{order.date}',
	'{site_title}',
	'{auction.user.total_donated}'
);

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Auction', 'goodbids' ) . ': ' . '{auction.title}' . "\n";
esc_html_e( 'Total Donated', 'goodbids' ) . ': ' . '{auction.user.total_donated}' . "\n";

echo "\n\n----------------------------------------\n\n";

$item_totals = $bid_order->get_order_item_totals();

if ( $item_totals ) {
	foreach ( $item_totals as $total ) {
		echo esc_html( wp_strip_all_tags( $total['label'] ) . ': ' . wp_strip_all_tags( $total['value'] ) ) . "\n";
	}
}

echo "\n\n----------------------------------------\n\n";

printf(
	/* translators: %1$s: Free Bids, %2$s: Referral Link */
	esc_html__( 'You have %1$s Free Bids that can be used in any eligible auction. You can earn more by placing one of the first five paid bids in any auction, or by referring a friend: %2$s', 'goodbids' ),
	'{user.free_bid_count}',
	'{user.referral_link}'
);

echo "\n\n----------------------------------------\n\n";

echo esc_html__( 'Keep an eye on the Auction page for live bidding updates. We will let you know if you are outbid before the auction closes.', 'goodbids' );

$instance->plain_text_footer();
