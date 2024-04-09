<?php
/**
 * Auction Closed email
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package GoodBids
 *
 * @var string $email_heading
 *
 * @var AuctionClosed $instance
 */

use GoodBids\Plugins\WooCommerce\Emails\AuctionClosed;

defined( 'ABSPATH' ) || exit;

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading );
?>

<p>
	<?php
	printf(
		/* translators: %1$s: Auction title, %2$s: Auction total Bids, %3$s: Auction total Raised, %4$s: Site title, %5$s: user Total Bids, %6$s: User Total Donated */
		esc_html__( 'The %1$s auction has ended with %2$s bid(s) placed and %3$s raised for %4$s. You supported this auction with %5$s bid(s) for a total donation of %6$s.', 'goodbids' ),
		'{auction.title}',
		'{auction.bid_count}',
		'{auction.total_raised}',
		'{site_title}',
		'{auction.user.bid_count}',
		'{auction.user.total_donated}'
	);
	?>
</p>

<p>
	<?php
	printf(
		'%s <a href="%s">%s</a>.',
		esc_html__( 'Thank you for being a supporter. Please check our other auctions at', 'goodbids' ),
		'{auctions_url}',
		esc_html__( 'goodbids.org/explore-auctions', 'goodbids' ),
	);
	?>
</p>

<?php
/* * @hooked WC_Emails::email_footer() Output the email footer */
do_action( 'woocommerce_email_footer' );
