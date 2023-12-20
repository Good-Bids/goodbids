<?php
/**
 * Auction Details Meta Box Content
 *
 * @global \GoodBids\Auctions\Auctions $this
 * @global int $auction_id
 * @global WC_Product $bid_product
 * @global WC_Order $last_bid
 *
 * @since 1.0.0
 * @package GoodBids
 */

?>
<div class="gb-auction-metrics">
	<p><strong><?php esc_html_e( 'Auction Metrics', 'goodbids' ); ?></strong></p>

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

	printf(
		'<p><strong>%s</strong><br>%s</p>',
		esc_html__( 'Current Bid', 'goodbids' ),
		wp_kses_post( wc_price( $bid_product->get_price() ) )
	);

	if ( $last_bid ) {
		printf(
			'<p><strong>%s</strong><br><a href="%s">%s</a></p>',
			esc_html__( 'Last Bid', 'goodbids' ),
			esc_url( get_edit_post_link( $last_bid->get_id() ) ),
			wp_kses_post( wc_price( $last_bid->get_total() ) )
		);
	}
	?>
</div>
