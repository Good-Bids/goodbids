<?php
/**
 * Free Bid Earned email
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package GoodBids
 *
 * @var string $email_heading
 *
 * @var FreeBidEarned $instance
 */

use GoodBids\Plugins\WooCommerce\Emails\FreeBidEarned;

defined( 'ABSPATH' ) || exit;

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading );
?>
<p>
	<?php
	printf(
		/* translators: %1$s: Free Bid Type, %2$s: View all Auctions */
		esc_html__( 'This is confirmation that your recent %1$s has earned you a new Free Bid. Way to go! %2$s on GOODBIDS to find your next great cause to support.', 'goodbids' ),
		'{free_bid.type_action}',
		sprintf(
			'<a href="%s">%s</a>',
			'{auctions_url}',
			esc_html__( 'View all Auctions', 'goodbids' )
		),
	);
	?>
</p>

<p>
	<?php
	printf(
		/* translators: %1$s: Number of Free Bids, %2$s Refer a Friend link */
		esc_html__( 'You have %1$s Free Bids that can be used in any eligible auction. You can earn more by placing one of the first five paid bids in any auction, or by %2$s.', 'goodbids' ),
		'{user.free_bid_count}',
		sprintf(
			'<a href="%s">%s</a>.',
			'{user.referral_link}',
			esc_html__( 'referring a friend', 'goodbids' )
		)
	);
	?>
</p>

<?php

/* * @hooked WC_Emails::email_footer() Output the email footer */
do_action( 'woocommerce_email_footer' );
