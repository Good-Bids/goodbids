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
$watchers = goodbids()->watchers->get_watcher_count();
?>
<div <?php block_attr( $block ); ?> >
	<button id="gb-toggle-watching"
		data-controller="watch-auction"
		data-action="watch-auction#toggle"
		data-watch-auction-state-value="<?php echo esc_attr( intval( $watching ) ); ?>"
		data-state="<?php echo esc_attr( intval( $watching ) ); ?>"
		data-auction="<?php echo esc_attr( goodbids()->auctions->get_auction_id() ); ?>"
		class="inline-flex items-center gap-2 no-underline text-md btn-fill-secondary"
	>
		<span class="dashicons dashicons-<?php echo esc_attr( $icon ); ?>"></span>
		<span data-watch-auction-target="text">
			<?php esc_html_e( 'Watch', 'goodbids' ); ?>
		</span>
	</button>
</div>
