<?php
/**
 * Auction Watchers Live email
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package GoodBids
 *
 * @var string $email_heading
 * @var string $email
 *
 * @var AuctionWatchersLive $instance
 */

use GoodBids\Plugins\WooCommerce\Emails\AuctionWatchersLive;

defined( 'ABSPATH' ) || exit;

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading );
?>

<p>
	<?php
	printf(
		/* translators: %1$s: Auction title, %2$s: Site title, %3$s: Auction Goal, %4$s: Auction URL  */
		esc_html__( 'The %1$s auction you are watching is now open for bidding. Visit the auction to support %2$s goal of raising %3$s and place a bid for your chance to win the %4$s!', 'goodbids' ),
		'{auction.title}',
		'{site_title}',
		'{auction.goal}',
		'{auction.url}'
	);
	?>
</p>

<p>
	<?php
	printf(
		/* translators: %1$s: Auction Start Date/Time */
		esc_html__( 'Bidding starts at %1$s and the first five paid bidders on this auction will earn a Free Bid.', 'goodbids' ),
		'{auction.start_date_time}'
	);
	?>
</p>

<p>
	<?php
	printf(
		/* translators: %1$s: Site Name */
		esc_html__( 'Every GoodBid on this auction is a donation to %1$s.', 'goodbids' ),
		'{site_title}'
	);
	?>
</p>

<?php

/* * @hooked WC_Emails::email_footer() Output the email footer */
do_action( 'woocommerce_email_footer', $email );
