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

$the_block = new AllAuctions( $block );
$page_url  = $the_block->get_current_url();
$sort_url  = remove_query_arg( AllAuctions::SORT_QUERY_ARG, $page_url );

// Apply filters and sorting to auctions.
$auctions = $the_block->apply_filters();
$auctions = $the_block->apply_sort( $auctions );

// Apply pagination.
$total_count = count( $auctions );
$total_pages = ceil( $total_count / $the_block->get_auctions_per_page() );
$auctions    = $the_block->apply_pagination( $auctions );
?>
<section <?php block_attr( $block ); ?>>
	<div class="mb-12 text-center text-contrast">
		<ul class="flex flex-wrap px-0 py-4 mt-0 mb-4 text-sm list-none border-b-2 border-solid border-contrast border-t-transparent border-x-transparent lg:text-base">
			<?php
			foreach ( $the_block->get_filters() as $filter ) :
				$filter_url = add_query_arg( AllAuctions::FILTER_QUERY_ARG, $filter['value'], $page_url );
				$filter_url = remove_query_arg( AllAuctions::SORT_QUERY_ARG, $filter_url ); // Remove sorting parameter.
				$is_current = $the_block->get_current_filter() === $filter['value'];
				?>
				<li class="me-2">
					<a
						href="<?php echo esc_url( $filter_url ); ?>"
						class="<?php echo esc_attr( $is_current ? 'btn-fill-secondary' : 'btn-fill' ); ?> block my-1"
					>
						<?php echo esc_html( $filter['label'] ); ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>

		<div class="flex justify-end">
			<select
				aria-label="<?php esc_attr_e( 'Sort Auctions', 'goodbids' ); ?>"
				class="p-2 border-transparent rounded-sm bg-contrast-3 text-contrast"
				name="<?php echo esc_attr( AllAuctions::SORT_QUERY_ARG ); ?>"
				hx-get="<?php echo esc_url( $sort_url ); ?>"
				hx-select="[data-auction-grid]"
				hx-target="[data-auction-grid]"
				hx-indicator="[data-auctions-spinner]"
				hx-swap="outerHTML"
				hx-trigger="change"
				hx-push-url="true"
			>
				<?php foreach ( $the_block->get_sort_options() as $option => $label ) : ?>
					<option
						value="<?php echo esc_attr( $option ); ?>"
						<?php selected( $option, $the_block->get_current_sort() ); ?>
					>
						<?php echo esc_html( $label ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>

	<div data-auctions-spinner class="flex justify-center group">
		<svg xmlns="http://www.w3.org/2000/svg" class="w-14 h-14 animate-spin hidden group-[.htmx-request]:block" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3C16.9706 3 21 7.02944 21 12H19C19 8.13401 15.866 5 12 5V3Z"></path></svg>
	</div>

	<div data-auction-grid role="region" aria-live="polite">
		<?php
		goodbids()->load_view( 'parts/auctions-grid.php', compact( 'auctions' ) );

		if ( $total_pages > 1 ) :
			echo wp_kses_post( $the_block->get_pagination( $total_pages ) );
		endif;
		?>
	</div>

</section>
