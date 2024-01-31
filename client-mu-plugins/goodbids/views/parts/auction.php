<?php
/**
 * Part: Auction block template
 *
 * Used inside of auction loop.
 *
 * @since 1.0.0
 * @package GoodBids
 */

$url = is_admin() ? '#' : get_the_permalink();
?>
<li>
	<a href="<?php echo esc_url( $url ); ?>" class="block">
		<figure class="wp-block-post-featured-image aspect-square rounded overflow-hidden *:w-full *:h-full">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'woocommerce_thumbnail' ); ?>
			<?php else : ?>
				<?php echo wp_kses_post( goodbids()->auctions->rewards->get_product( get_the_ID() )->get_image() ); ?>
			<?php endif; ?>
		</figure>
		<?php if ( get_the_title() ) : ?>
			<h2 class="wp-block-post-title has-large-font-size">
				<?php the_title(); ?>
			</h2>
		<?php endif; ?>
	</a>
</li>
