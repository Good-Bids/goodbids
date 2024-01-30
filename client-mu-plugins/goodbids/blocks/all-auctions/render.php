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
	$auctions           = $upcoming;
	$live_btn_class     = 'btn-fill';
	$upcoming_btn_class = 'btn-fill-secondary';
} else {
	$auctions           = $live;
	$live_btn_class     = 'btn-fill-secondary';
	$upcoming_btn_class = 'btn-fill';
}

$total_query = count( $auctions );
$total_pages = ceil( $total_query / $block_auctions::AUCTION_PER_PAGE );

$auctions = $block_auctions->filter_auctions( $auctions );

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
	<div class="text-base text-center text-contrast">
		<ul class="flex flex-wrap px-0 py-4 mt-0 mb-12 list-none border-b-2 border-solid border-contrast border-t-transparent border-x-transparent">
			<?php if ( $live ) : ?>
				<li class="me-2">
					<a
						href="<?php echo esc_url( $page_url ); ?>"
						class="<?php echo esc_attr( $live_btn_class ); ?>"
					>
						Live
					</a>
				</li>
			<?php endif; ?>
			<?php if ( $upcoming ) : ?>
				<li class="me-2">
					<a
						href="<?php echo esc_url( add_query_arg( $block_auctions::UPCOMING_QUERY_ARG, 1, $page_url ) ); ?>"
						class="<?php echo esc_attr( $upcoming_btn_class ); ?>"
					>
						Coming Soon
					</a>
				</li>
			<?php endif; ?>
		</ul>
	</div>
	<?php if ( ! count( $auctions ) ) : ?>
		<p>No auctions found.</p>
	<?php else : ?>
		<?php goodbids()->load_view( 'parts/auctions-grid.php', compact( 'auctions' ) ); ?>
		<?php echo wp_kses_post( $block_auctions->get_pagination( $page_url, $total_pages ) ); ?>
	<?php endif; ?>
	<?php $post = $og_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
</section>
