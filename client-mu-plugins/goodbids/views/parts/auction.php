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
	<a href="<?php echo esc_url( $url ); ?>" class="block overflow-hidden no-underline group hover:text-black">
		<figure class="m-0 overflow-hidden rounded wp-block-post-featured-image aspect-square">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'woocommerce_thumbnail', [ 'class' => 'w-full h-full object-cover' ] ); ?>
			<?php else : ?>
				<?php echo wp_kses_post( goodbids()->auctions->rewards->get_product( get_the_ID() )->get_image( null, [ 'class' => 'w-full h-full object-cover' ] ) ); ?>
			<?php endif; ?>
		</figure>
		<div class="flex items-center gap-4 py-4 border-t-0 border-b border-solid border-b-contrast-2 border-x-0">
			<?php if ( $image_attributes ) : ?>
				<div class="w-10 h-10 overflow-hidden rounded-full">
					<img class="object-contain w-10 h-10" src="<?php echo $image_attributes[0]; ?>" width="<?php echo $image_attributes[1]; ?>" height="<?php echo $image_attributes[2]; ?>" />
				</div>
			<?php endif; ?>
			<p class="m-0 text-sm font-bold">
				<?php echo esc_html( get_bloginfo( 'title' ) ); ?>
			</p>
		</div>
		<?php if ( get_the_title() ) : ?>
			<h2 class="mt-4 mb-0 normal-case wp-block-post-title has-large-font-size">
				<?php the_title(); ?>
			</h2>
		<?php endif; ?>

		<div class="flex mt-4 text-xs font-bold gap-7">
			<?php echo esc_html( goodbids()->auctions->get_remaining_time( get_the_ID(), 'clock' ) ); ?>

			<div class="flex items-center gap-2">
				<?php echo goodbids()->sites->get_svg( 'eye' ); ?>
				<?php echo esc_html( goodbids()->watchers->get_watcher_count( get_the_ID() ) ); ?>
			</div>
		</div>
	</a>
</li>
