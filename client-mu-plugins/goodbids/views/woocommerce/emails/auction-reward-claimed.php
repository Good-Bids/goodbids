<?php
/**
 * Auction Reward Claimed email
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package GoodBids
 *
 * @var string $email_heading
 *
 * @var AuctionRewardClaimed $instance
 */

use GoodBids\Plugins\WooCommerce\Emails\AuctionRewardClaimed;

defined( 'ABSPATH' ) || exit;

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading );
?>
<p>
	<?php
	printf(
		/* translators: %1$s: Reward title, %2$s: Site title, %3$s: Auction title, %4$s: Bid date */
		esc_html__( 'This is confirmation that you claimed the %1$s reward for the %2$s %3$s auction on %4$s. Thanks again for your generosity.', 'goodbids' ),
		'{reward.title}',
		'{site_title}',
		'{auction.title}',
		'TODO{auction.bid.date}',
	);
	?>
</p>

<p>
	<?php
	printf(
		__( 'You can view your bid history, see Auctions you\'ve won, modify your account information, and more from the <a href="%s">bidder dashboard.</a>', 'goodbids' ),
		'TODO{bidder_dashboard_url}',
	);
	?>
</p>



<?php

/* * @hooked WC_Emails::email_footer() Output the email footer */
do_action( 'woocommerce_email_footer' );
