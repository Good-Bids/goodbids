<?php
/**
 * Auction Winner Confirmation email (plain text)
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package GoodBids
 *
 * @var AuctionWinnerConfirmation $instance
 */

use GoodBids\Plugins\WooCommerce\Emails\AuctionWinnerConfirmation;

defined( 'ABSPATH' ) || exit;

$instance->plain_text_header();

echo "\n\n----------------------------------------\n\n";

printf(
	/* translators: %1$s: User last bid, %2$s: Auction Title, %3$s: Auction reward title, %4$s: Site title, %5$s days to claim reward */
	esc_html__( 'Congratulations! Your %1$s bid was the highest GOODBID for the %2$s auction, and you are the winner. Please claim your %3$s with %4$s within %5$s days.', 'goodbids' ),
	'{user.last_bid_amount}',
	'{auction.title}',
	'{reward.title}',
	'{site_title}',
	'{reward.days_to_claim_setting}'
);

echo "\n\n----------------------------------------\n\n";

$instance->plain_text_footer();
