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
	 * @param mixed   $data
	 * @param ?string $method
	 * @param bool    $die
	 *
	 * @return void
	 */
	function dd( mixed $data, ?string $method = 'dump', bool $die = true ): void {
		// Disable if not in dev environment.
		if ( ! GoodBids\Core::is_local_env() ) {
			return;
		}

		ini_set( 'highlight.comment', '#969896; font-style: italic' );
		ini_set( 'highlight.default', '#FFFFFF' );
		ini_set( 'highlight.html', '#D16568' );
		ini_set( 'highlight.keyword', '#7FA3BC; font-weight: bold' );
		ini_set( 'highlight.string', '#F2C47E' );

		/**
		 * Dump the data.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed  $data
		 * @param string $method
		 * @param bool   $return
		 *
		 * @return ?string
		 */
		$do_dump = function( mixed $data, string $method = 'dump', bool $return = false ): ?string {
			if ( $return ) {
				ob_start();
			}

			if ( in_array( $method, [ 'var_dump', 'dump' ], true ) ) {
				var_dump( $data ); // phpcs:ignore
			} elseif ( in_array( $method, [ 'print_r', 'printr' ], true ) ) {
				print_r( $data ); // phpcs:ignore
			} elseif ( in_array( $method, [ 'export', 'var_export' ], true ) ) {
				var_export( $data ); // phpcs:ignore
			} elseif ( in_array( $method, [ 'json', 'json_encode' ], true ) ) {
				echo wp_json_encode( $data ); // phpcs:ignore
			}

			if ( $return ) {
				return ob_get_clean();
			}

			return null;
		};

		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			$do_dump( $data, $method );
		} else {
			echo '<div style="background-color: #1C1E21; padding: 1rem">';
			highlight_string( "<?php\n\n" );
			highlight_string( $do_dump( $data, $method, true ) );
			echo '</div>';
		}

		if ( ! $die ) {
			return;
		}

		die();
	}
}
