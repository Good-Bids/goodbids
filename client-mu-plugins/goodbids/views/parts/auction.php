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
	<?php if ( has_post_thumbnail() ) : ?>
			<figure class="wp-block-post-featured-image aspect-square rounded overflow-hidden *:w-full *:h-full">
				<?php the_post_thumbnail( 'woocommerce_thumbnail' ); ?>
			</figure>
		<?php endif; ?>
		<h2 class="wp-block-post-title has-large-font-size">
			<?php the_title(); ?>
		</h2>
	</a>
</li>
