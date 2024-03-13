<?php
/**
 * Auction Paid Pid Placed (plain text)
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
	/* translators: %1$s: Bid amount, %2$s: Auction Title, %3$s: Site title, %4$s: Bid date, %5$s: Site title %6$s: Total User Donated */
	esc_html__( 'This is confirmation of your bid for %1$s to the %2$s auction for %3$s on %4$s. You have supported %5$s on this auction with a total donation of %6$s.', 'goodbids' ),
	'{order.total}',
	'{auction.title}',
	'{site_title}',
	'{order.date}',
	'{site_title}',
	'{auction.user.total_donated}'
);

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Auction', 'goodbids' ) . ': ' . '{auction.title}' . "\n";
esc_html_e( 'Donation', 'goodbids' ) . ': ' . '{order.total}' . "\n";

echo "\n\n----------------------------------------\n\n";

$item_totals = $bid_order->get_order_item_totals();

if ( $item_totals ) {
	foreach ( $item_totals as $total ) {
		echo esc_html( wp_strip_all_tags( $total['label'] ) . ': ' . wp_strip_all_tags( $total['value'] ) ) . "\n";
	}
}

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Keep an eye on the Auction page for live bidding updates. We will let you know if you are outbid before the auction closes.', 'goodbids' );

$instance->plain_text_footer();
