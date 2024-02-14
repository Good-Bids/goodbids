<?php
/**
 * Block: Auction User Actions
 *
 * @global array $block
 *
 * @since 1.0.0
 * @package GoodBids
 */
?>
<div <?php block_attr( $block ); ?>>
	<a class="inline-flex items-center gap-2 no-underline btn-fill-secondary">
		<span class="dashicons dashicons-<?php echo esc_attr( $icon ); ?>"></span>
		<span class="">
			<?php esc_html_e( 'Share for free bids', 'goodbids' ); ?>
		</span>
	</a>
</div>
