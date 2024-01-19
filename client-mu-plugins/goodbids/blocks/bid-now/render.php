<?php
/**
 * Block: Bid Now
 *
 * @global array $block
 *
 * @since 1.0.0
 * @package GoodBids
 */

$bid_now = new GoodBids\Blocks\BidNow( $block );

// Bail early if initial requirements are not met.
if ( ! $bid_now->display() ) :
	return;
endif;

// Make sure the auction has started.
if ( ! $bid_now->has_auction_started() ) :
	$bid_now->render_auction_not_started();
	return;
endif;
?>
<div <?php block_attr( $block, $bid_now->get_block_classes() ); ?>>
	<div>
		<a
			href="<?php echo esc_url( $bid_now->get_button_url() ); ?>"
			class="wp-block-button__link wp-element-button w-full block text-center"
		>
			<?php echo wp_kses_post( $bid_now->get_button_text() ); ?>
		</a>
	</div>

	<?php if ( $bid_now->show_free_bid_button() ) : ?>
		<div>
			<a
				href="<?php echo esc_url( $bid_now->get_button_url( true ) ); ?>"
				class="wp-block-button__link wp-element-button w-full block text-center"
			>
				<?php echo wp_kses_post( $bid_now->get_button_text( true ) ); ?>
			</a>
		</div>
	<?php endif; ?>
</div>
