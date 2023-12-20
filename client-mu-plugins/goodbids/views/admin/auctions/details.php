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
<p><strong><?php esc_html_e( 'Auction Details', 'goodbids' ); ?></strong></p>

<?php
printf(
	'<p><strong>%s</strong><br>%s</p>',
	esc_html__( 'Status', 'goodbids' ),
	esc_html( $this->get_status( $auction_id ) )
);
?>
