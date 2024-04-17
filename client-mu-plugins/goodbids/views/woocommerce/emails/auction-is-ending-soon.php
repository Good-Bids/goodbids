<?php
/**
 * Auction Ending Soon email
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package GoodBids
 *
 * @var string $email_heading
 *
 * @var AuctionIsEndingSoon $instance
 */

use GoodBids\Plugins\WooCommerce\Emails\AuctionIsEndingSoon;

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
			esc_html__( 'The %1$s auction is ending soon.', 'goodbids' ),
			'{auction.title}'
		),

		// URL
		'{auction.url}',

		// Link Text
		esc_html__( 'Visit the auction', 'goodbids' ),

		// After.
		sprintf(
			/* translators: %1$s: Site Title, %2$s: Reward Product Title */
			esc_html__( 'to support %1$s\'s mission and place a bid for your chance to win the %2$s!', 'goodbids' ),
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
		esc_html__( 'We\'ve already raised', 'goodbids' ),
		'{auction.total_raised}',
		esc_html__( 'and the', 'goodbids' ),
		esc_html__( 'current high bid is', 'goodbids' ),
		'{auction.high_bid}'
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
