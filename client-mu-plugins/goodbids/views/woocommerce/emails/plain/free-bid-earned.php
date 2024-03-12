<?php
/**
 * Free Bid Earned email (plain text)
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package GoodBids
 *
 * @var FreeBidEarned $instance
 */

use GoodBids\Plugins\WooCommerce\Emails\FreeBidEarned;

defined( 'ABSPATH' ) || exit;

$instance->plain_text_header();

printf(
	/* translators: %1$s: Last Bid Amount, %2$s: View all Auctions URL */
	esc_html__( 'This is confirmation that your recent %1$s has earned you a new Free Bid. Way to go! View all Auctions on GOODBIDS to find your next great cause to support: %s', 'goodbids' ),
	'{free_bid.type_action}',
	'{auctions_url}'
);

echo "\n\n----------------------------------------\n\n";

printf(
	/* translators: %1$s: Free Bids, %2$s: Referral Link */
	esc_html__( 'You have %1$s Free Bids that can be used in any eligible auction. You can earn more by placing one of the first five paid bids in any auction, or by referring a friend: %2$s', 'goodbids' ),
	'{user.free_bid_count}',
	'{user.referral_link}'
);

echo "\n\n----------------------------------------\n\n";

$instance->plain_text_footer();
