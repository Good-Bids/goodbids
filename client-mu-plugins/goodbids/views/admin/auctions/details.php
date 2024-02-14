<?php
/**
 * Auction Details Meta Box Content
 *
 * @global \GoodBids\Auctions\Auctions $this
 * @global int $auction_id
 *
 * @since 1.0.0
 * @package GoodBids
 */

?>
<h3><?php esc_html_e( 'Auction Details', 'goodbids' ); ?></h3>

<?php
$start_time   = goodbids()->auctions->get_start_date_time();
$current_time = strtotime( current_datetime()->format( 'Y-m-d H:i:s' ) );
$extra        = '';

// Customize the Status Display.
if ( $start_time && ! goodbids()->auctions->has_started() ) {
	$extra = sprintf(
		' (%s)',
		esc_html( human_time_diff( $current_time, strtotime( $start_time ) ) )
	);
} elseif ( ! goodbids()->auctions->has_ended() && goodbids()->auctions->get_extensions( $auction_id ) ) {
	$extra = sprintf(
		' (%s)',
		__( 'Extended', 'goodbids' )
	);
} elseif ( ! goodbids()->auctions->has_ended() && goodbids()->auctions->is_extension_window( $auction_id ) ) {
	$extra = sprintf(
		' (%s)',
		__( 'Extension Window', 'goodbids' )
	);
}

printf(
	'<p><strong>%s</strong><br>%s%s</p>',
	esc_html__( 'Status', 'goodbids' ),
	esc_html( goodbids()->auctions->get_status( $auction_id ) ),
	esc_html( $extra )
);

if ( 'publish' === get_post_status( $auction_id ) ) {
	printf(
		'<p><strong>%s</strong> <a href="%s" data-auction-id="%s" data-nonce="%s">%s</a><br><span id="gb-close-date">%s</span></p>',
		esc_html__( 'Close Date/Time', 'goodbids' ),
		'#gb-force-update-close-date',
		esc_attr( $auction_id ),
		esc_attr( wp_create_nonce( 'gb-force-update-close-date' ) ),
		esc_html__( 'Force Update', 'goodbids' ),
		esc_html( goodbids()->auctions->get_end_date_time( $auction_id, 'n/j/Y g:i:s a' ) )
	);
}
