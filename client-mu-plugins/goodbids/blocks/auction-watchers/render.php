<?php
/**
 * Block: Auction Watchers
 *
 * @global array $block
 *
 * @since 1.0.0
 * @package GoodBids
 */

$watching = goodbids()->watchers->is_watching();
$icon     = $watching ? 'visibility' : 'hidden';
$watchers = goodbids()->watchers->get_watcher_count();
?>
<div <?php block_attr( $block ); ?>>
	<div id="gb-toggle-watching"
		data-state="<?php echo esc_attr( intval( $watching ) ); ?>"
		data-auction="<?php echo esc_attr( goodbids()->auctions->get_auction_id() ); ?>"
		class="inline-flex items-center gap-2 no-underline"
	>
		<span class="dashicons dashicons-<?php echo esc_attr( $icon ); ?>"></span>
		<span class="text-xs font-bold">
			<span data-watch-auction-target="watchers"><?php echo esc_html( $watchers ); ?></span>
			<?php esc_html_e( 'Watchers', 'goodbids' ); ?>
		</span>
	</div>
</div>
