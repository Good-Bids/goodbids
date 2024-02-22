<?php
/**
 * Admin Auction Summary email (plain text)
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package GoodBids
 *
 * @var ?string $auction_estimated_value
 * @var ?string $auction_estimated_value_formatted
 * @var ?string $auction_goal
 * @var ?string $auction_goal_formatted
 * @var ?string $auction_expected_high_bid
 * @var ?string $auction_expected_high_bid_formatted
 *
 * @var AuctionSummaryAdmin $instance
 */

use GoodBids\Plugins\WooCommerce\Emails\AuctionSummaryAdmin;

defined( 'ABSPATH' ) || exit;

$instance->plain_text_header();

printf(
	/* translators: %1$s: Auction title, %2$s: Auction end time, %3$s: Auction total Bids, %4$s: Auction total Raised, %5$s: Site name  */
	esc_html__( 'The %1$s GOODBIDS auction ended on %2$s with %3$s placed and %4$s raised for %5$s. Check out the summary below for key auction metrics.', 'goodbids' ),
	'{auction.title}',
	'{auction.end_date_time}',
	'{auction.bid_count}',
	'{auction.total_raised}',
	'{site_title}'
);

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Auction Title:', 'goodbids' );
echo '{auction.title}';

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Scheduled Start:', 'goodbids' );
echo '{auction.start_date_time}';

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Starting Bid:', 'goodbids' );
echo '{auction.starting_bid}';

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Bid Increment:', 'goodbids' );
echo '{auction.bid_increment}';

echo "\n\n----------------------------------------\n\n";

if ( $auction_goal ) :
	esc_html_e( 'Auction Goal:', 'goodbids' );
	echo esc_html( $auction_goal_formatted );
endif;

echo "\n\n----------------------------------------\n\n";

if ( $auction_expected_high_bid ) :
	esc_html_e( 'Expected High Bid:', 'goodbids' );
	echo esc_html( $auction_expected_high_bid_formatted );
endif;

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Scheduled End:', 'goodbids' );
echo '{auction.end_date_time}';

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Bid Extension:', 'goodbids' );
echo '{auction.bid_extension}';

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Auction Reward:', 'goodbids' );
echo '{reward.title}';

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Reward Type:', 'goodbids' );
echo '{reward.type}';

echo "\n\n----------------------------------------\n\n";

if ( $auction_estimated_value ) :
	esc_html_e( 'Fair Market Value:', 'goodbids' );
	echo esc_html( $auction_estimated_value_formatted );
endif;

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Actual End', 'goodbids' );
echo '{auction.end_date_time}';

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Total Bids', 'goodbids' );
echo '{auction.bid_count}';

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Total Raised', 'goodbids' );
echo '{auction.total_raised}';

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'High Bid', 'goodbids' );
echo '{auction.high_bid}';

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'The auction has ended. The auctioneer has chosen a winner. The winner will be notified by email and will be notified by text. The winner has 40 days to claim their reward. We will send you a confirmation email once the reward has been claimed.', 'goodbids' );

echo "\n\n----------------------------------------\n\n";

printf(
	/* translators: %1$s: Login URL */
	'Login to your site to view additional auction information: %1$s',
	'{login_url}'
);

$instance->plain_text_footer();
