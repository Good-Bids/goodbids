<?php
/**
 * Site Grid Template
 *
 * @global array $nonprofit
 *
 * @since 1.0.0
 * @package GoodBids
 */

$image_attributes = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ) );
?>
<li>
	<a class="flex items-center gap-4 no-underline hover:underline focus:underline" href="<?php echo is_admin() ? '#' : esc_url( get_site_url() ); ?>">
		<?php if ( $image_attributes ) : ?>
			<div class="w-10 h-10 overflow-hidden rounded-full shrink-0">
				<img class="object-contain w-10 h-10" src="<?php echo esc_url( $image_attributes[0] ); ?>" width="<?php echo esc_attr( $image_attributes[1] ); ?>" height="<?php echo esc_attr( $image_attributes[2] ); ?>" />
			</div>
		<?php endif; ?>
		<h3 class="text-pretty break-words normal-case text-md ...">
			<?php echo esc_html( get_bloginfo( 'title' ) ); ?>
		</h3>
	</a>
</li>
