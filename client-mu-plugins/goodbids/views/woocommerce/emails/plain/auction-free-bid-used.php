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
	/* translators: %1$s: Auction Title, %2$s: Auction Bid date, %3$s: Site title, %4$s: Auction Bid amount */
	esc_html__( 'This is your confirmation of your Free Bid for the %1$s auction on %2$s. You have supported %3$s on this auction for a total donation of %3$s.', 'goodbids' ),
	'{auction.title}',
	'TODO{auction.bid.date}',
	'{site_title}',
	'TODO{auction.bid.amount}'
);

echo "\n\n----------------------------------------\n\n";

// TODO: ADD BID DETAILS TABLE HERE

echo "\n\n----------------------------------------\n\n";

echo '{reward.purchase_note}';

$instance->plain_text_footer();
