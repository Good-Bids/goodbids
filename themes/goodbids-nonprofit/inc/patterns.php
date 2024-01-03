<?php
/**
 * Patterns Functions
 *
 * @package GoodBids_Nonprofit
 *
 * @since 1.0.0
 */

/**
 * Register Component Pattern Category
 *
 * @since 1.0.0
 */
add_action(
	'init',
	function () {
		register_block_pattern_category(
			'goodbids-np',
			[
				'label' => __( 'GoodBids Nonprofit', 'goodbids-nonprofit' ),
			]
		);
	}
);

/**
 * Register Theme Patterns
 *
 * @since 1.0.0
 */
add_filter(
	'goodbids_block_patterns',
	function ( array $patterns ): array {
		$theme_patterns = [
			[
				'name'       => 'goodbids-np/header-nonprofit',
				'path'       => get_stylesheet_directory() . '/patterns/header-nonprofit.php',
				'title'      => __( 'GoodBids Header', 'goodbids-nonprofit' ),
				'categories' => [ 'header', 'goodbids-np' ],
				'keywords'   => [ 'header', 'non-profit', 'template' ],
				'blockTypes' => [ 'core/template-part/header' ],
				'source'     => 'theme',
				'inserter'   => true,
			],
			[
				'name'       => 'goodbids-np/footer-nonprofit',
				'path'       => get_stylesheet_directory() . '/patterns/footer-nonprofit.php',
				'title'      => __( 'GoodBids Footer', 'goodbids-nonprofit' ),
				'categories' => [ 'footer', 'goodbids-np' ],
				'blockTypes' => [ 'core/template-part/footer' ],
				'source'     => 'theme',
				'inserter'   => true,
			],
			[
				'name'          => 'goodbids-np/template-home-nonprofit',
				'path'          => get_stylesheet_directory() . '/patterns/template-home-nonprofit.php',
				'title'         => __( 'GoodBids Home Template', 'goodbids-nonprofit' ),
				'description'   => _x( 'Template for the Nonprofit Homepage', 'Block pattern description', 'goodbids-nonprofit' ),
				'categories'    => [ 'featured', 'goodbids-np' ],
				'keywords'      => [ 'home', 'non-profit', 'template', 'page' ],
				'templateTypes' => [ 'front-page', 'home', 'page' ],
				'source'        => 'theme',
				'inserter'      => true,
			],
		];

		return array_merge( $patterns, $theme_patterns );
	}
);
