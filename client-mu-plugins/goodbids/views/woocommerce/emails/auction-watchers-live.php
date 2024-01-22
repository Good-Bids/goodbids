<?php
/**
 * Auction Watchers Live email
 *
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>


<?php /* translators: %s: Customer username */ ?>
<p><?php printf( esc_html__( 'Hi %s,', 'woocommerce' ), esc_html( $user_login ) ); ?></p>

<p>
	<?php
		/* translators: %1$s: Auction title, %2$s: Site title, %3$s: Auction Goal, %4$s: Auction URL  */
		printf(
			esc_html__( 'The %1$s auction you are watching is now open for bidding. Visit the auction to support %2$s goal of raising %3$s and place a bid for your chance to win the %4$s!', 'goodbids' ),
			esc_html( get_the_title( $auction_id ) ),
			esc_html( $blogname ),
			esc_html( $auction_goal ),
			esc_html( get_the_permalink( $auction_id ) )
		);
		?>
</p>

<p>
	<?php
	/* translators: %1$s: Auction Starting Date */
	printf(
		esc_html__( 'Bidding starts at %1$s and the first five paid bidders on this auction will earn a Free Bid.', 'goodbids' ),
		// TODO update to starting date
	);
	?>
</p>


<?php
/** * Show user-defined additional content - this is set in each email's settings. */ if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
} /* * @hooked WC_Emails::email_footer() Output the email footer */ do_action( 'woocommerce_email_footer', $email );
