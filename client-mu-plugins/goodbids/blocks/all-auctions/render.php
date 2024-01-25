<?php
/**
 * Block: All Auctions
 *
 * @global array $block
 *
 * @since 1.0.0
 * @package GoodBids
 */

$block_auctions = new GoodBids\Blocks\AllAuctions( $block );
$all_auctions   = goodbids()->sites->get_all_auctions();
$live           = $block_auctions->get_live( $all_auctions );
$upcoming       = $block_auctions->get_upcoming( $all_auctions );
$page_url       = get_permalink( get_queried_object_id() );

// Determine which auctions to display
if ( $block_auctions->is_upcoming() || ! $live ) {
	$auctions = $upcoming;
} else {
	$auctions = $live;
}

$total_query = count( $auctions );
$total_pages = ceil( $total_query / $block_auctions::AUCTION_PER_PAGE );

$auctions = collect( $auctions )->slice( $block_auctions->get_offset(), $block_auctions::AUCTION_PER_PAGE )->all();

if ( ! count( $auctions ) ) :
	if ( is_admin() ) :
		printf(
			'<p>%s</p>',
			esc_html__( 'No auctions found.', 'goodbids' )
		);
	endif;

	return;
endif;
?>
<section <?php block_attr( $block ); ?>>
	<div class="text-base text-center border-b-2 border-contrast text-contrast">
		<ul class="flex flex-wrap my-0 list-none">
			<?php if ( $live ) : ?>
				<li class="me-2">
					<a href="<?php echo esc_url( $page_url ); ?>" class="btn-fill">Live</a>
				</li>
			<?php endif; ?>
			<?php if ( $upcoming ) : ?>
				<li class="me-2">
					<a href="<?php echo esc_url( add_query_arg( $block_auctions::UPCOMING_QUERY_ARG, 1, $page_url ) ); ?>" class="btn-fill">Coming Soon</a>
				</li>
			<?php endif; ?>
		</ul>
	</div>
	<?php
	if ( ! count( $auctions ) ) :
		?>
		<p>No auctions found.</p>
	<?php else : ?>
	<ul class="grid grid-cols-1 gap-8 list-none lg:grid-cols-3 sm:grid-cols-2">
		<?php goodbids()->load_view( 'parts/auctions-grid.php', compact( 'auctions' ) ); ?>
	</ul>
		<?php echo wp_kses_post( $block_auctions->get_pagination( $page_url, $total_pages ) ); ?>
	<?php endif; ?>
	<?php $post = $og_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
</section>
