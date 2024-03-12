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
	/* translators: %1$s: Last Bid Amount, %2$s: View all Auctions */
	esc_html__( 'This is confirmation that your recent %1$s has earned you a new Free Bid. Way to go! %2$s on GOODBIDS to find your next great cause to support.', 'goodbids' ),
	'{user.last_bid_amount}',
	'TODO View all Auctions',
);

echo "\n\n----------------------------------------\n\n";

printf(
	/* translators: %1$s: Free Bids, %2$s: View all Auctions */
	esc_html__( 'You have %1$s Free Bids that can be used in any eligible auction. You can earn more by placing one of the first five paid bids in any auction, or by', 'goodbids' ),
	'TODO {user.free_bid_count}',
	'TODO referring to friend',
);

echo "\n\n----------------------------------------\n\n";

echo '{reward.purchase_note}';

$instance->plain_text_footer();
