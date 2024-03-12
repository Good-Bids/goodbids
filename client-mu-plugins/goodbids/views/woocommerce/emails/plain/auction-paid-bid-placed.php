<?php
/**
 * Auction Reward Reminder email (plain text)
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package GoodBids
 *
 * @var AuctionRewardReminder $instance
 */

use GoodBids\Plugins\WooCommerce\Emails\AuctionRewardReminder;

defined( 'ABSPATH' ) || exit;

$instance->plain_text_header();

printf(
	/* translators: %1$s: Bid amount, %2$s: Auction Title, %3$s: Site title, %4$s: Bid date, %5$s: Site title %6$s: Bid amount */
	esc_html__( 'This is confirmation of your bid for %1$s to the %2$s auction for %3$s on %4$s. You have supported %5$s on this auction with a total donation of %6$s.', 'goodbids' ),
	'TODO{auction.bid}',
	'{auction.title}',
	'{site_title}',
	'TODO{auction.bid.date}',
	'{site_title}',
	'TODO{auction.total.donated}'
);

echo "\n\n----------------------------------------\n\n";

// TODO: ADD BID DETAILS HERE

echo "\n\n----------------------------------------\n\n";

echo esc_html__( 'Keep an eye on the Auction page for live bidding updates. We will let you know if you are outbid before the auction closes.', 'goodbids' );

$instance->plain_text_footer();
