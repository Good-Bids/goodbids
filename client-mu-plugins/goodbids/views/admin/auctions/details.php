<?php
/**
 * Auction Details Meta Box Content
 *
 * @global int $auction_id
 *
 * @since 1.0.0
 * @package GoodBids
 */

?>
<h3><?php esc_html_e( 'Auction Details', 'goodbids' ); ?></h3>

<?php
$auction      = goodbids()->auctions->get( $auction_id );
$start_time   = $auction->get_start_date_time();
$current_time = strtotime( current_datetime()->format( 'Y-m-d H:i:s' ) );
$extra        = '';

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
} elseif ( ! $auction->has_ended() && $auction->is_extension_window( $auction_id ) ) {
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

if ( 'publish' === get_post_status( $auction_id ) ) {
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
