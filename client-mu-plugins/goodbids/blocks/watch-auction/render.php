<?php
/**
 * Block: Watch Auction
 *
 * @global array $block
 *
 * @since 1.0.0
 * @package GoodBids
 */

$watching = goodbids()->watchers->is_watching();
$icon     = $watching ? 'visibility' : 'hidden';
$watchers = goodbids()->watchers->get_watcher_count()
?>
<div <?php block_attr( $block ); ?>>
	<a href="#gb-toggle-watching"
		data-state="<?php echo esc_attr( intval( $watching ) ); ?>"
		data-auction="<?php echo esc_attr( goodbids()->auctions->get_auction_id() ); ?>"
		class="flex gap-2 no-underline items-center"
	>
		<span class="dashicons dashicons-<?php echo esc_attr( $icon ); ?>"></span>
		<span>
			<span class="watch-count"><?php echo esc_html( $watchers ); ?></span>
			<?php esc_html_e( 'Watching', 'goodbids' ); ?>
		</span>
	</a>
</div>
