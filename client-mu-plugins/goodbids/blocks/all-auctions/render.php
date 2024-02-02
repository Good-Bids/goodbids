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
$all_auctions      = $block_auctions->get_all_auctions();
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
	$auctions     = $upcoming_auctions;
	$live_btn     = false;
	$upcoming_btn = true;
	$sort_url     = $upcoming_url;
} else {
	$auctions     = $live_auctions;
	$live_btn     = true;
	$upcoming_btn = false;
	$sort_url     = $page_url;
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
						class="<?php echo esc_attr( $live_btn ? 'btn-fill-secondary' : 'btn-fill' ); ?>"
					>
						<?php esc_html_e( 'Live', 'goodbids' ); ?>
					</a>
				</li>
			<?php endif; ?>

			<?php if ( $upcoming_auctions ) : ?>
				<li class="me-2">
					<a
						href="<?php echo esc_url( $upcoming_url ); ?>"
						class="<?php echo esc_attr( $upcoming_btn ? 'btn-fill-secondary' : 'btn-fill' ); ?>"
					>
						<?php esc_html_e( 'Coming Soon', 'goodbids' ); ?>
					</a>
				</li>
			<?php endif; ?>
		</ul>
		<?php if ( ! empty( $block_auctions->get_sort_dropdown_options() ) ) : ?>
			<div class="flex justify-end">
				<select
					aria-label="Sort Auctions"
					name="<?php echo esc_attr( AllAuctions::SORT_QUERY_ARG ); ?>"
					class="p-2 border-transparent rounded-sm bg-contrast-3 text-contrast"
					hx-get="<?php echo esc_url( $sort_url ); ?>"
					hx-select="[data-all-auction]"
					hx-target="[data-all-auction]"
					hx-swap="outerHTML"
					hx-trigger="change"
					hx-push-url="true"
				>
					<?php foreach ( $block_auctions->get_sort_dropdown_options() as $option ) : ?>
						<option
							value="<?php echo esc_attr( $option['value'] ); ?>"
							<?php echo $option['value'] === $block_auctions->is_sortby() ? esc_attr( 'selected' ) : ''; ?>
						>
							<?php echo esc_html( $option['label'] ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
		<?php endif; ?>
	</div>


	<div data-all-auction="true">
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
	</div>

</section>

<script src="https://unpkg.com/htmx.org@1.9.10"
		integrity="sha384-D1Kt99CQMDuVetoL1lrYwg5t+9QdHe7NLX/SoJYkXDFfX37iInKRy5xLSi8nO7UC"
		crossorigin="anonymous"></script>
