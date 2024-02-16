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
<li class="block overflow-hidden">
	<a href="<?php echo esc_url( $url ); ?>" class="no-underline hover:underline hover:text-black">
		<figure class="m-0 overflow-hidden rounded wp-block-post-featured-image aspect-square">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'woocommerce_thumbnail', [ 'class' => 'w-full h-full object-cover' ] ); ?>
			<?php else : ?>
				<?php echo wp_kses_post( goodbids()->auctions->rewards->get_product( get_the_ID() )->get_image( null, [ 'class' => 'w-full h-full object-cover' ] ) ); ?>
			<?php endif; ?>
		</figure>
	</a>
	<a href="<?php echo esc_url( get_site_url() ); ?>" class="flex items-center gap-4 py-4 no-underline border-t-0 border-b border-solid border-b-contrast-2 border-x-0 hover:underline">
		<?php if ( $image_attributes ) : ?>
			<div class="w-10 h-10 overflow-hidden rounded-full shrink-0">
				<img class="object-contain w-10 h-10" src="<?php echo esc_url( $image_attributes[0] ); ?>" width="<?php echo esc_attr( $image_attributes[1] ); ?>" height="<?php echo esc_attr( $image_attributes[2] ); ?>" />
			</div>
		<?php endif; ?>
		<p class="text-sm font-bold line-clamp-1 my-1.5 mx-0">
			<?php echo esc_html( get_bloginfo( 'title' ) ); ?>
		</p>
	</a>
	<a href="<?php echo esc_url( $url ); ?>" class="no-underline hover:underline">
		<?php if ( get_the_title() ) : ?>
			<h2 class="mt-4 mb-0 normal-case wp-block-post-title has-large-font-size line-clamp-3">
				<?php the_title(); ?>
			</h2>
		<?php endif; ?>
	</a>
	<div class="flex mt-4 text-xs font-bold gap-7">
		<?php echo wp_kses_post( goodbids()->auctions->get_remaining_time( get_the_ID(), 'clock' ) ); ?>

		<div class="flex items-center gap-2">
			<img class="w-5 h-5" src="<?php echo esc_url( goodbids()->utilities->get_svg_path( 'eye' ) ); ?> " />
			<?php echo esc_html( goodbids()->watchers->get_watcher_count( get_the_ID() ) ); ?>
		</div>
	</div>
</li>
