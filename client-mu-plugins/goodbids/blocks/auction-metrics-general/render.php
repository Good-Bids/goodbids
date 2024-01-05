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
<section <?php block_attr( $block, 'flex gap-5 my-4 flex-wrap' ); ?>>
	<?php
	// Goal.
	if ( $goal ) :
		?>
		<div class="flex flex-col text-center">
			<p class="m-0 font-thin uppercase has-x-small-font-size"><?php esc_html_e( 'Auction Goal', 'goodbids' ); ?></p>
			<?php
				printf(
					'<p class="m-1 font-extrabold">%s</p>',
					wp_kses_post( wc_price( $goal ) )
				);
			?>
		</div>
	<?php endif; ?>

	<?php
	// Estimated Value.
	if ( $estimated_value ) :
		?>
		<div class="flex flex-col text-center">
			<p class="m-0 font-thin uppercase has-x-small-font-size"><?php esc_html_e( 'Auction Goal', 'goodbids' ); ?></p>
			<?php
				printf(
					'<p class="m-1 font-extrabold">%s</p>',
					wp_kses_post( wc_price( $estimated_value ) )
				);
			?>
		</div>
	<?php endif; ?>

	<?php
	// Expected High Bid.
	if ( $expected_high_bid ) :
		?>
		<div class="flex flex-col text-center">
			<p class="m-0 font-thin uppercase has-x-small-font-size"><?php esc_html_e( 'Auction Goal', 'goodbids' ); ?></p>
			<?php
				printf(
					'<p class="m-1 font-extrabold">%s</p>',
					wp_kses_post( wc_price( $expected_high_bid ) )
				);
			?>
		</div>
	<?php endif; ?>
</section>
