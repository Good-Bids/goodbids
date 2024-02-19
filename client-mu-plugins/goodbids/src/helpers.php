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

if ( ! function_exists( 'dd' ) ) {
	/**
	 * Helpful Dump & Die method for printing debug data to the screen
	 *
	 * @link https://gist.github.com/james2doyle/abfbd4dc5754712bac022faf4e2881a6
	 *
	 * @param mixed $data
	 *
	 * @return void
	 */
	function dd( mixed $data ): void {
		// Disable if not in dev environment.
		if ( ! GoodBids\Core::is_dev_env() ) {
			return;
		}

		ini_set( 'highlight.comment', '#969896; font-style: italic' );
		ini_set( 'highlight.default', '#FFFFFF' );
		ini_set( 'highlight.html', '#D16568' );
		ini_set( 'highlight.keyword', '#7FA3BC; font-weight: bold' );
		ini_set( 'highlight.string', '#F2C47E' );

		$output = highlight_string( "<?php\n\n" . var_export( $data, true ), true );
		die( "<div style=\"background-color: #1C1E21; padding: 1rem\">$output</div>" );
	}
}
