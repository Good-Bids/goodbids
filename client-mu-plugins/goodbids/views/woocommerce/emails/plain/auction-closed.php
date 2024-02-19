<?php
/**
 * Auction Closed email (plain text)
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package GoodBids
 *
 * @var string $email
 *
 * @var AuctionClosed $instance
 */

use GoodBids\Plugins\WooCommerce\Emails\AuctionClosed;

defined( 'ABSPATH' ) || exit;

$instance->plain_text_header();

printf(
	/* translators: %1$s: Auction title, %2$s: Auction total Bids, %3$s: Auction total Raised, %4$s: Site title, %5$s: user Total Bids, %6$s: User Total Donated */
	esc_html__( 'The %1$s auction has ended with %2$s placed and %3$s raised for %4$s. You supported this auction with %5$s for a total donation of %6$s', 'goodbids' ),
	'{auction.title}',
	'{auction.bid_count}',
	'{auction.total_raised}',
	'{site_title}',
	'{user.bid_count}',
	'{user.total_donated}'
);

echo "\n\n----------------------------------------\n\n";

esc_html_e( 'Check your email or visit the auction page to see if you won!', 'goodbids' );

$instance->plain_text_footer();

printf(
	/* translators: %1$s: Main site All Auctions page url */
	'Want to support another great GoodBids? <a class="button" href="%1$s">View all auctions</a>',
	'{auctions_url}'
);

echo wp_kses_post( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );
