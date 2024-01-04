<?php
/**
 * Block: All Auctions
 *
 * @global array $block
 *
 * @since 1.0.0
 * @package GoodBids
 */

$all_auction = new GoodBids\Blocks\AllAuctions( $block );
?>

<section <?php block_attr( $block ); ?>>
	<ul class="grid grid-cols-1 gap-8 list-none lg:grid-cols-3 sm:grid-cols-2">
		<?php foreach ( $all_auction->get_all_auctions() as $auction ) : ?>
		<li>
			<a href="<?php echo esc_url( $auction->guid ); ?>" class="block">
				<?php if ( $auction->img ) : ?>
					<figure class="wp-block-post-featured-image aspect-square *:w-full *:h-full">
						<?php echo wp_kses_post( $auction->img ); ?>
					</figure>
				<?php endif; ?>

				<h2 class="wp-block-post-title has-large-font-size">
					<?php esc_html_e( $auction->post_title ); ?>
				</h2>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>
</section>
