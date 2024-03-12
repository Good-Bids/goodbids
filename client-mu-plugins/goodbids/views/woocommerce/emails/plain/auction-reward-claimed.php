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
	/* translators: %1$s: Reward title, %2$s: Site title, %3$s: Auction title, %4$s: Bid date */
	esc_html__( 'This is confirmation that you claimed the %1$s reward for the %2$s %3$s auction on %4$s. Thanks again for your generosity.', 'goodbids' ),
	'{reward.title}',
	'{site_title}',
	'{auction.title}',
	'TODO{auction.bid.date}',
);

echo "\n\n----------------------------------------\n\n";

echo '{reward.purchase_note}';

// TODO: ADD BID DETAILS HERE

echo "\n\n----------------------------------------\n\n";

printf(
	__( 'You can view your bid history, see Auctions you\'ve won, modify your account information, and more from the <a href="%s">bidder dashboard.</a>', 'goodbids' ),
	'TODO{bidder_dashboard_url}',
);


$instance->plain_text_footer();
