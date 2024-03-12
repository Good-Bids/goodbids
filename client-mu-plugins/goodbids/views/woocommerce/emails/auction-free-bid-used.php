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
		'TODO{auction.bid.amount}'
	);
	?>
</p>

<?php // TODO: ADD BID DETAILS TABLE HERE ?>

<?php

/* * @hooked WC_Emails::email_footer() Output the email footer */
do_action( 'woocommerce_email_footer' );
