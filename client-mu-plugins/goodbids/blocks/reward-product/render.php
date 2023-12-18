<?php
/**
 * Block: Reward Product
 *
 * @global array $block
 *
 * @since 1.0.0
 * @package GoodBids
 */
$auction_id        = is_admin() ? intval( sanitize_text_field( $_GET['post'] ) ) : get_queried_object_id();
$goal              = goodbids()->auctions->get_goal( $auction_id );
$estimated_value   = goodbids()->auctions->get_estimated_value( $auction_id );
$expected_high_bid = goodbids()->auctions->get_expected_high_bid( $auction_id );
?>


<section <?php block_attr( $block ); ?>>
<?php
// Goal
if ( $goal ) {
	printf(
		'<p style="text-align: center;">%s</p>',
		esc_html__( $goal, 'goodbids' )
	);
} else {
	printf(
		'<p style="text-align: center;">%s</p>',
		esc_html__( 'No Goal Set', 'goodbids' )
	);
}

// Estimated Value
if ( $estimated_value ) {
	printf(
		'<p style="text-align: center;">%s</p>',
		esc_html__( $estimated_value, 'goodbids' )
	);
} else {
	printf(
		'<p style="text-align: center;">%s</p>',
		esc_html__( 'No Estimated Value Set', 'goodbids' )
	);
}

// Expected High Bid
if ( $expected_high_bid ) {
	printf(
		'<p style="text-align: center;">%s</p>',
		esc_html__( $expected_high_bid, 'goodbids' )
	);
} else {
	printf(
		'<p style="text-align: center;">%s</p>',
		esc_html__( 'No Expected High Bid Set', 'goodbids' )
	);
}
?>
</section>
