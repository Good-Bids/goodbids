<?php
/**
 * Pattern: GoodBids Hero
 *
 * @since 1.0.0
 * @package GoodBids
 */

?>

<!-- wp:media-text {"align":"full","mediaPosition":"right","mediaId":123,"mediaLink":"http://nonprofit.goodbids.vipdev.lndo.site/?attachment_id=123","mediaType":"image","mediaWidth":43,"mediaSizeSlug":"full","imageFill":false,"style":{"spacing":{"margin":{"bottom":"80px"}}}} -->
<div class="wp-block-media-text alignfull has-media-on-the-right is-stacked-on-mobile" style="margin-bottom:80px;grid-template-columns:auto 43%">
	<div class="wp-block-media-text__content">
		<!-- wp:heading {"level":1} -->
		<h1 class="wp-block-heading">
			<?php esc_html_e( 'Lorem ipsum dolor sit amet', 'goodbids' ); ?>
		</h1>
		<!-- /wp:heading -->

		<!-- wp:paragraph -->
		<p><?php esc_html_e( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam', 'goodbids' ); ?></p>
		<!-- /wp:paragraph -->

	<!-- wp:buttons {"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|40"}}}} -->
	<div class="wp-block-buttons" style="margin-bottom:var(--wp--preset--spacing--40)">
		<!-- wp:button -->
		<div class="wp-block-button">
			<a class="wp-block-button__link wp-element-button" href="http://nonprofit.goodbids.vipdev.lndo.site/shop/">
				<?php esc_html_e( 'Lorem Ipsum', 'goodbids' ); ?>
			</a>
		</div>
		<!-- /wp:button -->
		</div>
	<!-- /wp:buttons -->
	</div>

	<figure class="wp-block-media-text__media">
	<img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/images/goodbids-icon.png" alt="<?php esc_attr_e( '', 'goodbids-main' ); ?>"  class="wp-image-123 size-full"/>
	</figure>
</div>
<!-- /wp:media-text -->
