<?php
/**
 * Auction Debug Info Meta Box Content
 *
 * @global int $auction_id
 *
 * @since 1.0.1
 * @package GoodBids
 */

?>
<div class="gb-auction-debug-info">
	<h3><?php esc_html_e( 'Debug Info', 'goodbids' ); ?></h3>

	<?php
	$auction = goodbids()->auctions->get( $auction_id );

	printf(
		'<p><strong>%s</strong><span>%s</span></p>',
		esc_html__( 'Start Triggered', 'goodbids' ),
		esc_html( $auction->start_triggered() ? __( 'Yes', 'goodbids' ) : __( 'No', 'goodbids' ) )
	);

	printf(
		'<p><strong>%s</strong><span>%s</span></p>',
		esc_html__( 'End Triggered', 'goodbids' ),
		esc_html( $auction->end_triggered() ? __( 'Yes', 'goodbids' ) : __( 'No', 'goodbids' ) )
	);
	?>
</div>
