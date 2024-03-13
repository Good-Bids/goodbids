<?php
/**
 * Auction Details Meta Box Content
 *
 * @global int $auction_id
 *
 * @since 1.0.0
 * @package GoodBids
 */

$auction     = goodbids()->auctions->get( $auction_id );
$bid_product = wc_get_product( goodbids()->bids->get_product_id( $auction_id ) );
$last_bid    = $auction->get_last_bid();
?>
<div class="gb-auction-metrics">
	<h3><?php esc_html_e( 'Auction Metrics', 'goodbids' ); ?></h3>

	<?php
	printf(
		'<p><strong>%s</strong><br>%s</p>',
		esc_html__( 'Total Bids', 'goodbids' ),
		esc_html( $auction->get_bid_count() )
	);

	printf(
		'<p><strong>%s</strong><br>%s</p>',
		esc_html__( 'Available Free Bids', 'goodbids' ),
		esc_html( $auction->get_free_bids_available() )
	);

	printf(
		'<p><strong>%s</strong><br>%s</p>',
		esc_html__( 'Total Extensions', 'goodbids' ),
		esc_html( $auction->get_extensions() )
	);

	printf(
		'<p><strong>%s</strong><br>%s</p>',
		esc_html__( 'Total Raised', 'goodbids' ),
		wp_kses_post( wc_price( $auction->get_total_raised() ) )
	);

	if ( $bid_product ) :
		printf(
			'<p><strong>%s</strong><br>%s</p>',
			esc_html__( 'Current Bid', 'goodbids' ),
			wp_kses_post( wc_price( $bid_product->get_price() ) )
		);
	endif;

	if ( $last_bid ) :
		$last_bid_amount = $last_bid->get_total( 'edit' );
		printf(
			'<p><strong>%s</strong><br><a href="%s">%s%s</a></p>',
			esc_html__( 'Last Bid', 'goodbids' ),
			esc_url( get_edit_post_link( $last_bid->get_id() ) ),
			wp_kses_post( wc_price( $last_bid_amount ) ),
			! intval( $last_bid_amount ) ? sprintf( ' (%s)', wp_kses_post( wc_price( $last_bid->get_subtotal() ) ) ) : ''
		);
	endif;
	?>
</div>
