<?php
/**
 * Patterns Functions
 *
 * @package GoodBids_Nonprofit
 *
 * @since 1.0
 */

/**
 * Register Component Pattern Category
 *
 * @since 1.0
 */
add_action(
	'init',
	function() {
		register_block_pattern_category(
			'goodBids-nonprofit',
			[
				'label' => __( 'GoodBids Non-profit', 'goodbids-nonprofit' ),
			]
		);
	}
);

/**
 * Register Patterns
 *
 * @since 1.0
 */
add_action(
	'init',
	function() {
		$patterns = [
			[
				'name'        	=> 'template-home-nonprofit',
				'path'        	=> 'patterns/template-home-nonprofit.php',
				'title'       	=> __( 'Non-profit Home Template', 'goodbids-nonprofit' ),
				'description' 	=> _x( 'Template for the Non-profit Homepage', 'Block pattern description', 'goodbids-nonprofit' ),
				'categories' 	=> ['featured', 'goodBids-nonprofit'],
				'keywords'    	=> ['home', 'non-profit', 'template', 'page'],
				'templateTypes' => ['front-page', 'home', 'page'],
			]
		];

		foreach ( $patterns as $pattern ) {
			if ( ! file_exists( get_stylesheet_directory() . '/' . $pattern['path'] ) ) {
				continue;
			}

			$path      = get_stylesheet_directory() . '/' . $pattern['path'];
			$extension = pathinfo( $path, PATHINFO_EXTENSION );

			if ( 'php' === $extension ) {
				ob_start();
				include $path;
				$content = ob_get_clean();
			} else {
				$content = file_get_contents( $path );
			}

			$pattern['content'] = $content;
			unset( $pattern['path'] );

			register_block_pattern(
				'goodbids-nonprofit/' . $pattern['name'],
				$pattern
			);
		}
	}
);
