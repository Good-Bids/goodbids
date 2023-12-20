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

$bid_product = wc_get_product( $this->get_bid_product_id( $auction_id ) );
$last_bid    = $this->get_last_bid( $auction_id );
?>
<div class="gb-auction-metrics">
	<h3><?php esc_html_e( 'Auction Metrics', 'goodbids' ); ?></h3>

	<?php
	printf(
		'<p><strong>%s</strong><br>%s</p>',
		esc_html__( 'Total Bids', 'goodbids' ),
		esc_html( $this->get_bid_count( $auction_id ) )
	);

	printf(
		'<p><strong>%s</strong><br>%s</p>',
		esc_html__( 'Total Raised', 'goodbids' ),
		wp_kses_post( wc_price( $this->get_total_raised( $auction_id ) ) )
	);

	if ( $bid_product ) :
		printf(
			'<p><strong>%s</strong><br>%s</p>',
			esc_html__( 'Current Bid', 'goodbids' ),
			wp_kses_post( wc_price( $bid_product->get_price() ) )
		);
	endif;

	if ( $last_bid ) :
		printf(
			'<p><strong>%s</strong><br><a href="%s">%s</a></p>',
			esc_html__( 'Last Bid', 'goodbids' ),
			esc_url( get_edit_post_link( $last_bid->get_id() ) ),
			wp_kses_post( wc_price( $last_bid->get_total() ) )
		);
	endif;
	?>
</div>
