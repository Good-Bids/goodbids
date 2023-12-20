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
	 * @param array  $block
	 * @param string $addl_class
	 * @param array  $attr
	 *
	 * @return void
	 */
	function block_attr( array $block, string $addl_class = '', array $attr = [] ): void {
		goodbids()->acf->blocks()->block_attr( $block, $addl_class, $attr );
	}
}

/**
 * Render assets files from manifest
 *
 * @since 1.0.0
 *
 * @param  string $asset
 * @return string
 */
function asset_path( $asset_path, $file ) {
	$manifest_path = GOODBIDS_PLUGIN_PATH . 'dist/.vite/manifest.json';

	if ( ! file_exists( $manifest_path ) ) {
		return false;
	}

	$manifest = json_decode( file_get_contents( $manifest_path ), true );

	// var_dump( $manifest[ $asset_path ][ $file ] );
	return 'dist/' . $manifest[ $asset_path ][ $file ];
}
