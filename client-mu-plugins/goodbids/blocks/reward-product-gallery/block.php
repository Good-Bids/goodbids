<?php
/**
 * Initialize the WooCommerce Gallery Script
 *
 * @since 1.0.0
 *
 * @return void
 */


add_action(
	'wp_enqueue_scripts',
	function () {
		if ( is_singular( 'gb-auction' ) ) {
			if ( current_theme_supports( 'wc-product-gallery-zoom' ) ) {
				wp_enqueue_script( 'zoom' );
			}
			if ( current_theme_supports( 'wc-product-gallery-slider' ) ) {
				wp_enqueue_script( 'flexslider' );
			}
			if ( current_theme_supports( 'wc-product-gallery-lightbox' ) ) {
				wp_enqueue_script( 'photoswipe-ui-default' );
				wp_enqueue_style( 'photoswipe-default-skin' );
				add_action( 'wp_footer', 'woocommerce_photoswipe' );
			}
			wp_enqueue_script( 'wc-single-product' );
		}
	}
);
