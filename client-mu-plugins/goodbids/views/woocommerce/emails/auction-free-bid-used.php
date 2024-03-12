<?php
/**
 * Auction Free Bid Used email
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package GoodBids
 *
 * @var string $email_heading
 *
 * @var AuctionFreeBidUsed $instance
 */

use GoodBids\Plugins\WooCommerce\Emails\AuctionFreeBidUsed;

defined( 'ABSPATH' ) || exit;

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading );
?>
<p>
	<?php
	printf(
		/* translators: %1$s: Auction Title, %2$s: Auction Bid date, %3$s: Site title, %4$s: Auction Bid amount */
		esc_html__( 'This is your confirmation of your Free Bid for the %1$s auction on %2$s. You have supported %3$s on this auction for a total donation of %3$s.', 'goodbids' ),
		'{auction.title}',
		'TODO{auction.bid.date}',
		'{site_title}',
		'TODO{user.total.donated}'
	);
	?>
</p>


<?php // TODO: ADD BID DETAILS TABLE HERE ?>

<p>
	<?php
	printf(
		/* translators: %1$s: Free Bids, %2$s: View all Auctions */
		esc_html__( 'You have %1$s Free Bids that can be used in any eligible auction. You can earn more by placing one of the first five paid bids in any auction, or by', 'goodbids' ),
		'TODO {user.free_bid_count}',
		'TODO <a href="#">referring to friend</a>',
	);
	?>
</p>

<p>
	<?php
		echo esc_html__( 'Keep an eye on the Auction page for live bidding updates. We will let you know if you are outbid before the auction closes.', 'goodbids' );
	?>
</p>

<?php

/* * @hooked WC_Emails::email_footer() Output the email footer */
do_action( 'woocommerce_email_footer' );
