<?php
/**
 * Helper Functions
 *
 * @since 1.0.0
 * @package GoodBids
 */

if ( ! function_exists( 'block_attr' ) ) {
	/**
	 * Render the ACF block attributes
	 *
	 * @since 1.0.0
	 *
	 * @param array $block
	 * @param string $addl_class
	 * @param array $attr
	 *
	 * @return void
	 */
	function block_attr( array $block, string $addl_class = '', array $attr = [] ) : void {
		goodbids()->acf->blocks()->block_attr( $block, $addl_class, $attr );
	}
}
