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
		printf(
			'<p style="text-align: center;">%s</p>',
			esc_html__( $goal, 'goodbids goal' )
		);
		printf(
			'<p style="text-align: center;">%s</p>',
			esc_html__( $estimated_value, 'goodbids estimated value' )
		);
		printf(
			'<p style="text-align: center;">%s</p>',
			esc_html__( $expected_high_bid, 'goodbids expected high bid' )
		);
	?>
</section>
