<?php
/**
 * Auction Details Meta Box Content
 *
 * @global int $auction_id
 *
 * @since 1.0.0
 * @package GoodBids
 */

use GoodBids\Auctions\Auction;
use GoodBids\Auctions\Wizard;

?>
<h3><?php esc_html_e( 'Auction Details', 'goodbids' ); ?></h3>

<?php
$auction      = goodbids()->auctions->get( $auction_id );
$start_time   = $auction->get_start_date_time();
$current_time = strtotime( current_datetime()->format( 'Y-m-d H:i:s' ) );
$extra        = '';

$reward_product = $auction->get_reward();
$bid_product    = $auction->get_bid_product();

// Customize the Status Display.
if ( $start_time && ! $auction->has_started() ) {
	$extra = sprintf(
		' (%s)',
		esc_html( human_time_diff( $current_time, strtotime( $start_time ) ) )
	);
} elseif ( ! $auction->has_ended() && $auction->get_extensions() ) {
	$extra = sprintf(
		' (%s)',
		__( 'Extended', 'goodbids' )
	);
} elseif ( ! $auction->has_ended() && $auction->is_extension_window() ) {
	$extra = sprintf(
		' (%s)',
		__( 'Extension Window', 'goodbids' )
	);
}

printf(
	'<p><strong>%s</strong><br>%s%s</p>',
	esc_html__( 'Status', 'goodbids' ),
	esc_html( $auction->get_status() ),
	esc_html( $extra )
);

if ( $reward_product ) {
	$reward_title = $reward_product->get_name();
	$reward_title = wp_trim_words( $reward_title, 10, '...' );

	if ( in_array( $auction->get_status(), [ Auction::STATUS_LIVE, Auction::STATUS_CLOSED ] ) && ! is_super_admin() ) {
		printf(
			'<p><strong>%s</strong><br>%s</p>',
			esc_html__( 'Reward Product', 'goodbids' ),
			esc_html( $reward_title )
		);
	} else {
		printf(
			'<p><strong>%s</strong><br>%s (<a href="%s">%s</a>)</p>',
			esc_html__( 'Reward Product', 'goodbids' ),
			esc_html( $reward_title ),
			esc_html( goodbids()->auctions->wizard->get_wizard_url( Wizard::EDIT_MODE_OPTION, $auction_id, $reward_product->get_id() ) ),
			esc_html__( 'Edit', 'goodbids' )
		);
	}
}

if ( $bid_product && 'publish' === get_post_status( $auction_id ) ) {
	if ( ! is_super_admin() ) {
		printf(
			'<p><strong>%s</strong><br>#%s</p>',
			esc_html__( 'Bid Product', 'goodbids' ),
			esc_html( $bid_product->get_name() )
		);
	} else {
		printf(
			'<p><strong>%s</strong><br>#%s (<a href="%s">%s</a>)</p>',
			esc_html__( 'Bid Product', 'goodbids' ),
			esc_html( $bid_product->get_id() ),
			esc_html( get_edit_post_link( $bid_product->get_id() ) ),
			esc_html__( 'Edit', 'goodbids' )
		);
	}
}

if ( 'publish' === get_post_status( $auction_id ) ) {
	if ( in_array( $auction->get_status(), [ Auction::STATUS_LIVE, Auction::STATUS_CLOSED ] ) && ! is_super_admin() ) {
		printf(
			'<p><strong>%s</strong><br>%s</p>',
			esc_html__( 'Close Date/Time', 'goodbids' ),
			esc_html( $auction->get_end_date_time( 'n/j/Y g:i:s a' ) )
		);
	} else {
		printf(
			'<p><strong>%s</strong> <a href="%s" data-auction-id="%s" data-nonce="%s">%s</a><br><span id="gb-close-date">%s</span></p>',
			esc_html__( 'Close Date/Time', 'goodbids' ),
			'#gb-force-update-close-date',
			esc_attr( $auction_id ),
			esc_attr( wp_create_nonce( 'gb-force-update-close-date' ) ),
			esc_html__( 'Force Update', 'goodbids' ),
			esc_html( $auction->get_end_date_time( 'n/j/Y g:i:s a' ) )
		);
	}
}
