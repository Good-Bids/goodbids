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
$start_time = $this->get_start_date_time();

printf(
	'<p><strong>%s</strong><br>%s%s</p>',
	esc_html__( 'Status', 'goodbids' ),
	esc_html( $this->get_status( $auction_id ) ),

	$start_time ? sprintf(
		' (%s)',
		esc_html( human_time_diff( time(), strtotime( $start_time ) ) )
	) : ''
);
