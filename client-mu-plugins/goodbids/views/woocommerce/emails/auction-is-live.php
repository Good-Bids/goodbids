<?php
/**
 * Auction Watchers Live email
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package GoodBids
 *
 * @var string $email_heading
 *
 * @var AuctionIsLive $instance
 */

use GoodBids\Plugins\WooCommerce\Emails\AuctionIsLive;

defined( 'ABSPATH' ) || exit;

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading );
?>

<p>
	<?php
	printf(
		'%s <a href="%s">%s</a> %s',

		// Before
		sprintf(
			/* translators: %1$s: Auction Title  */
			esc_html__( 'The %1$s auction you are watching is now open for bidding.', 'goodbids' ),
			'{auction.title}'
		),

		// URL
		'{auction.url}',

		// Link Text
		esc_html__( 'Visit the auction', 'goodbids' ),

		// After.
		sprintf(
			/* translators: %1$s: Site Title, Reward Product Title */
			esc_html__( 'to support %1$s\'s mission and place a bid for your chance to win the %3$s!', 'goodbids' ),
			'{site_title}',
			'{reward.title}'
		)
	);
	?>
</p>

<p>
	<?php
	printf(
		'%s %s %s <strong>%s</strong> %s',
		/* translators: %1$s: Auction Starting Bid */
		esc_html__( 'Bidding starts at', 'goodbids' ),
		'{auction.starting_bid}',
		esc_html__( 'and the', 'goodbids' ),
		esc_html__( 'first five paid bidders', 'goodbids' ),
		esc_html__( 'on this auction will earn a Free Bid.', 'goodbids' )
	);
	?>
</p>

<p>
	<?php
	printf(
		/* translators: %1$s: Site Name */
		esc_html__( 'Every GOODBID on this auction is a donation to %1$s.', 'goodbids' ),
		'{site_title}'
	);
	?>
</p>

<?php

/* * @hooked WC_Emails::email_footer() Output the email footer */
do_action( 'woocommerce_email_footer' );
