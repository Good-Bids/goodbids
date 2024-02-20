<?php
/**
 * Admin Auction Live email (plain text)
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
 * @var AuctionIsLiveAdmin $instance
 */

use GoodBids\Plugins\WooCommerce\Emails\AuctionIsLiveAdmin;

defined( 'ABSPATH' ) || exit;

$instance->plain_text_header();

printf(
	/* translators: %1$s: Auction title, %2$s: Site title, %3$s: Auction Start Date  */
	esc_html__( 'Just letting you know that the %1$s auction on the %2$s GOODBIDS site went live on %3$s.', 'goodbids' ),
	'{auction.title}',
	'{site_title}',
	'{auction.start_date_time}',
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

printf(
	/* translators: %1$s: Auction Start Date/Time, %2$s: Auction Bid Extension, %3$s: Auction Bid Extension */
	esc_html__( 'The auction will end on %1$s unless a bid is placed within %2$s of the scheduled time. Each subsequent bid will extend the auction length by %3$s minutes. We will send you an auction summary when the auction has closed.', 'goodbids' ),
	'{auction.end_date_time}',
	'{auction.bid_extension}',
	'{auction.bid_extension}',
);

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Keep an eye on the auction page for live bidding updates!', 'goodbids' );

$instance->plain_text_footer();
