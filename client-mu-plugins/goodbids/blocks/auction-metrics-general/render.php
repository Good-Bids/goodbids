<?php
/**
 * Block: Auction Metrics (General)
 *
 * @global array $block
 *
 * @since 1.0.0
 * @package GoodBids
 */

$goal              = goodbids()->auctions->get_goal();
$estimated_value   = goodbids()->auctions->get_estimated_value();
$expected_high_bid = goodbids()->auctions->get_expected_high_bid();

// Display an Admin message to make the block easier to find.
if ( ! $goal && ! $estimated_value && ! $expected_high_bid && is_admin() ) :
	printf(
		'<p style="text-align:center;">%s</p>',
		esc_html__( 'No stats yet.', 'goodbids' )
	);
	return;
endif;
?>
<section <?php block_attr( $block ); ?>>
	<?php
	// Goal.
	if ( $goal ) :
		printf(
			'<p style="text-align: center;">%s</p>',
			esc_html__( wc_price( $goal ), 'goodbids' )
		);
	endif;

	// Estimated Value.
	if ( $estimated_value ) :
		printf(
			'<p style="text-align: center;">%s</p>',
			esc_html__( wc_price( $estimated_value ), 'goodbids' )
		);
	endif;

	// Expected High Bid.
	if ( $expected_high_bid ) :
		printf(
			'<p style="text-align: center;">%s</p>',
			esc_html__( wc_price( $expected_high_bid ), 'goodbids' )
		);
	endif;
	?>
</section>
