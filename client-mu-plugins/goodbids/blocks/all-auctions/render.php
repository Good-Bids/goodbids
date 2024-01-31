<?php
/**
 * Block: All Auctions
 *
 * @global array $block
 *
 * @since 1.0.0
 * @package GoodBids
 */

use GoodBids\Blocks\AllAuctions;
use GoodBids\Auctions\Auctions;

$block_auctions    = new GoodBids\Blocks\AllAuctions( $block );
$all_auctions      = goodbids()->sites->get_all_auctions();
$live_auctions     = $block_auctions->get_live_auctions( $all_auctions );
$upcoming_auctions = $block_auctions->get_upcoming_auctions( $all_auctions );


if ( is_post_type_archive( Auctions::POST_TYPE ) ) {
	$page_url = get_post_type_archive_link( Auctions::POST_TYPE );
} else {
	$page_url = get_permalink( get_queried_object_id() );
}

$upcoming_url = add_query_arg( AllAuctions::UPCOMING_QUERY_ARG, 1, $page_url );

// Determine which auctions to display.
if ( $block_auctions->is_displaying_upcoming() || ! $live_auctions ) {
	$auctions           = $upcoming_auctions;
	$live_btn_class     = 'btn-fill';
	$upcoming_btn_class = 'btn-fill-secondary';
} else {
	$auctions           = $live_auctions;
	$live_btn_class     = 'btn-fill-secondary';
	$upcoming_btn_class = 'btn-fill';
}

// Apply filters and pagination.
$auctions    = $block_auctions->apply_filters( $auctions );
$total_query = count( $auctions );
$total_pages = ceil( $total_query / $block_auctions->get_auctions_per_page() );
$auctions    = $block_auctions->apply_pagination( $auctions );
?>
<section <?php block_attr( $block ); ?>>
	<div class="mb-12 text-center text-contrast">
		<ul class="flex flex-wrap px-0 py-4 mt-0 mb-4 list-none border-b-2 border-solid border-contrast border-t-transparent border-x-transparent">
			<?php if ( $live_auctions ) : ?>
				<li class="me-2">
					<a
						href="<?php echo esc_url( $page_url ); ?>"
						class="<?php echo esc_attr( $live_btn_class ); ?>"
					>
						<?php esc_html_e( 'Live', 'goodbids' ); ?>
					</a>
				</li>
			<?php endif; ?>

			<?php if ( $upcoming_auctions ) : ?>
				<li class="me-2">
					<a
						href="<?php echo esc_url( $upcoming_url ); ?>"
						class="<?php echo esc_attr( $upcoming_btn_class ); ?>"
					>
						<?php esc_html_e( 'Coming Soon', 'goodbids' ); ?>
					</a>
				</li>
			<?php endif; ?>
		</ul>
		<?php if ( ! empty( $block_auctions->get_sort_options() ) ) : ?>
			<div class="flex justify-end">
				<select
					class="p-2 border-transparent rounded-sm bg-contrast-3 text-contrast"
					aria-label="Sort Auctions"
					onchange="window.location.href = '<?php echo esc_url( $page_url ); ?>' + '?' + this.value + '=1'"
				>
					<?php foreach ( $block_auctions->get_sort_options() as $option ) : ?>
						<option
							value="<?php echo esc_attr( $option['value'] ); ?>"
							<?php selected( $option['selected'] ); ?>
						>
							<?php echo esc_html( $option['label'] ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
		<?php endif; ?>
	</div>


	<?php
	if ( ! count( $auctions ) ) :
		printf(
			'<p>%s</p>',
			esc_html__( 'No auctions found.', 'goodbids' )
		);
	else :
		goodbids()->load_view( 'parts/auctions-grid.php', compact( 'auctions' ) );

		if ( $total_pages > 1 ) :
			echo wp_kses_post( $block_auctions->get_pagination( $page_url, $total_pages ) );
		endif;
	endif;
	?>
</section>
