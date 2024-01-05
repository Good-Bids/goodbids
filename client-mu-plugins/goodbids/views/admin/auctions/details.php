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
$start_time   = $this->get_start_date_time();
$current_time = strtotime( current_datetime()->format( 'Y-m-d H:i:s' ) );
$extra        = '';

// Customize the Status Display.
if ( $start_time && ! $this->has_started() ) {
	$extra = sprintf(
		' (%s)',
		esc_html( human_time_diff( $current_time, strtotime( $start_time ) ) )
	);
} elseif ( ! $this->has_ended() && $this->get_extensions( $auction_id ) ) {
	$extra = sprintf(
		' (%s)',
		__( 'Extended', 'goodbids' )
	);
} elseif ( ! $this->has_ended() && $this->is_extension_window( $auction_id ) ) {
	$extra = sprintf(
		' (%s)',
		__( 'Extension Window', 'goodbids' )
	);
}

printf(
	'<p><strong>%s</strong><br>%s%s</p>',
	esc_html__( 'Status', 'goodbids' ),
	esc_html( $this->get_status( $auction_id ) ),
	esc_html( $extra )
);

printf(
	'<p><strong>%s</strong><br>%s</p>',
	esc_html__( 'Close Date/Time', 'goodbids' ),
	esc_html( $this->get_end_date_time( $auction_id, 'n/j/Y g:i:s a' ) )
);
