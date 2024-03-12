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
	/* translators: %1$s: Auction Reward, %2$s: Auction title, %3$s: Site Title, %4$s: Reward Claim Deadline Date */
	esc_html__( 'You still need to claim the %1$s you earned on the %2$s auction with %3$s. You have until %4$s to claim your reward.', 'goodbids' ),
	'{reward.title}',
	'{auction.title}',
	'{site_title}',
	'{reward.claim_deadline_date}'
);

echo "\n\n----------------------------------------\n\n";

$instance->plain_text_footer();
