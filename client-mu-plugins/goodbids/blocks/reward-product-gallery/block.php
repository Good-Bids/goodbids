<?php
/**
 * Reward Product Gallery Block
 *
 * @since 1.0.0
 * @package GoodBids
 */

/**
 * Initialize the WooCommerce Gallery Assets
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		// TODO: Change this to a check to see if this block is currently being used.
		if ( ! is_singular( goodbids()->auctions->get_post_type() ) ) {
			return;
		}

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
);
