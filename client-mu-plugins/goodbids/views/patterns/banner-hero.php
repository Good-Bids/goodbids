<?php
/**
 * Pattern: GoodBids Hero
 *
 * @since 1.0.0
 * @package GoodBids
 */

?>
<!-- wp:media-text {"align":"wide","mediaPosition":"right","mediaSizeSlug":"full","verticalAlignment":"top","imageFill":false,"style":{"spacing":{"margin":{"bottom":"80px"},"padding":{"top":"0","bottom":"0"}}}} -->
<div class="wp-block-media-text alignwide has-media-on-the-right is-stacked-on-mobile is-vertically-aligned-top" style="margin-bottom:80px;padding-top:0;padding-bottom:0">
	<div class="wp-block-media-text__content">
		<!-- wp:heading {"level":1} -->
		<h1 class="wp-block-heading"><?php esc_html_e( 'Lorem ipsum dolor sit amet', 'goodbids' ); ?></h1>
		<!-- /wp:heading -->

		<!-- wp:paragraph -->
		<p>
			<?php esc_html_e( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam', 'goodbids' ); ?>
		</p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons {"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|40"}}}} -->
		<div class="wp-block-buttons" style="margin-bottom:var(--wp--preset--spacing--40)">
			<!-- wp:button -->
			<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#"><?php esc_html_e( 'Lorem Ipsum', 'goodbids' ); ?></a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<figure class="wp-block-media-text__media"></figure>
</div>
<!-- /wp:media-text -->
