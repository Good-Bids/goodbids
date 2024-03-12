<?php
/**
 * Auction Reward Reminder
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package GoodBids
 *
 * @var string $email_heading
 *
 * @var AuctionRewardReminder $instance
 */

use GoodBids\Plugins\WooCommerce\Emails\AuctionRewardReminder;

defined( 'ABSPATH' ) || exit;

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading );
?>

<p>
	<?php
	printf(
		/* translators: %1$s: Auction Reward, %2$s: Auction title, %3$s: Site Title, %4$s: Reward Claim Deadline Date */
		esc_html__( 'You still need to claim the %1$s you earned on the %2$s auction with %3$s. You have until %4$s to claim your reward.', 'goodbids' ),
		'{reward.title}',
		'{auction.title}',
		'{site_title}',
		'{reward.claim_deadline_date}'
	);
	?>
</p>

<?php

/* * @hooked WC_Emails::email_footer() Output the email footer */
do_action( 'woocommerce_email_footer' );
