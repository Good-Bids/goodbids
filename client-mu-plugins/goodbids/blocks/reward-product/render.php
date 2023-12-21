<?php
/**
 * Block: Reward Product
 *
 * @global array $block
 *
 * @since 1.0.0
 * @package GoodBids
 */

$goal              = goodbids()->auctions->get_goal();
$estimated_value   = goodbids()->auctions->get_estimated_value();
$expected_high_bid = goodbids()->auctions->get_expected_high_bid();
?>
<section <?php block_attr( $block ); ?>>
	<?php
	// Goal
	if ( $goal ) {
		printf(
			'<p style="text-align: center;">%s</p>',
			esc_html__( $goal, 'goodbids' )
		);
	}

	// Estimated Value
	if ( $estimated_value ) {
		printf(
			'<p style="text-align: center;">%s</p>',
			esc_html__( $estimated_value, 'goodbids' )
		);
	}

	// Expected High Bid
	if ( $expected_high_bid ) {
		printf(
			'<p style="text-align: center;">%s</p>',
			esc_html__( $expected_high_bid, 'goodbids' )
		);
	}
	?>
</section>
