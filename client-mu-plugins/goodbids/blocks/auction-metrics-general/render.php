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
<section <?php block_attr( $block, 'grid grid-cols-3 gap-2' ); ?>>
	<?php
	// Goal.
	if ( $goal ) :
		?>
		<div class="rounded bg-contrast-5 px-4 py-2">
			<span class="font-bold text-sm block"><?php esc_html_e( 'Goal', 'goodbids' ); ?></span>
			<?php
				printf(
					'<span class="text-sm block">%s</span>',
					wp_kses_post( wc_price( $goal ) )
				);
			?>
		</div>
	<?php endif; ?>

	<?php
	// Expected High Bid.
	if ( $expected_high_bid ) :
		?>
		<div class="rounded bg-contrast-5 px-4 py-2">
			<span class="font-bold text-sm block"><?php esc_html_e( 'High Bid Goal', 'goodbids' ); ?></span>
			<?php
				printf(
					'<span class="text-sm block">%s</span>',
					wp_kses_post( wc_price( $expected_high_bid ) )
				);
			?>
		</div>
	<?php endif; ?>

	<?php
	// Estimated Value.
	if ( $estimated_value ) :
		?>
		<div class="rounded bg-contrast-5 px-4 py-2">
			<span class="font-bold text-sm block"><?php esc_html_e( 'Est. Value', 'goodbids' ); ?></span>
			<?php
				printf(
					'<span class="text-sm block">%s</span>',
					wp_kses_post( wc_price( $estimated_value ) )
				);
			?>
		</div>
	<?php endif; ?>
</section>
