<?php
/**
 * Auction Winner Confirmation email
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package GoodBids
 *
 * @var string $email_heading
 * @var string $email
 *
 * @var AuctionWinnerConfirmation $instance
 */

use GoodBids\Plugins\WooCommerce\Emails\AuctionWinnerConfirmation;

defined( 'ABSPATH' ) || exit;

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email );
?>
<p>
	<?php
	printf(
		/* translators: %1$s: User last bid, %2$s: Auction Title, %3$s: Auction reward title, %4$s: Site title */
		esc_html__( 'Congratulations! Your %1$s bid was the highest GOODBID for the %2$s auction, and you are the winner. Please claim your %3$s with %4$s within 40 days.', 'goodbids' ),
		'{auction.userLastBid}',
		'{auction.title}',
		'{reward.title}',
		'{site_title}',
	);
	?>
</p>

<p>
	{reward.purchase_note}
</p>

<?php

/* * @hooked WC_Emails::email_footer() Output the email footer */
do_action( 'woocommerce_email_footer', $email );
