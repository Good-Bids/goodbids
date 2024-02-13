<?php
/**
 * Part: Auction block template
 *
 * Used inside of auction loop.
 *
 * @since 1.0.0
 * @package GoodBids
 */

$url              = is_admin() ? '#' : get_the_permalink();
$image_attributes = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ) );
?>
<li>
	<a href="<?php echo esc_url( $url ); ?>" class="block no-underline">
		<figure class="wp-block-post-featured-image aspect-square m-0 rounded overflow-hidden *:w-full *:h-full *:object-contain">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'woocommerce_thumbnail' ); ?>
			<?php else : ?>
				<?php echo wp_kses_post( goodbids()->auctions->rewards->get_product( get_the_ID() )->get_image() ); ?>
			<?php endif; ?>
		</figure>
		<div class="flex gap-4 py-6 border-t-0 border-b border-solid border-b-contrast-2 border-x-0">
			<?php if ( $image_attributes ) : ?>
				<img class="w-8 h-8" src="<?php echo $image_attributes[0]; ?>" width="<?php echo $image_attributes[1]; ?>" height="<?php echo $image_attributes[2]; ?>" />
			<?php endif; ?>
			<p class="m-0 font-bold">
				<?php echo esc_html( get_bloginfo( 'title' ) ); ?>
			</p>
		</div>
		<?php if ( get_the_title() ) : ?>
			<h2 class="wp-block-post-title has-large-font-size">
				<?php the_title(); ?>
			</h2>
		<?php endif; ?>

		<div class="flex gap-4">
			<div>
				<?php echo goodbids()->auctions->get_remaining_time( get_the_ID() ); ?>
			</div>
			<div>
				<?php echo esc_html( goodbids()->watchers->get_watcher_count( get_the_ID() ) ); ?>
			</div>
		</div>
	</a>
</li>
