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
 * Register Patterns
 *
 * @since 1.0.0
 */
add_action(
	'init',
	function () {
		$patterns = [
			[
				'name'          => 'footer-nonprofit',
				'file'          => 'patterns/footer-nonprofit.php',
				'title'         => __( 'GoodBids Footer', 'goodbids-nonprofit' ),
				'categories'    => [ 'footer', 'goodbids-np' ],
				'blockTypes'    => [ 'core/template-part/footer' ],
				'source'        => 'theme',
			],
			[
				'name'          => 'template-home-nonprofit',
				'file'          => 'patterns/template-home-nonprofit.php',
				'title'         => __( 'GoodBids Home Template', 'goodbids-nonprofit' ),
				'description'   => _x( 'Template for the Nonprofit Homepage', 'Block pattern description', 'goodbids-nonprofit' ),
				'categories'    => [ 'featured', 'goodbids-np' ],
				'keywords'      => [ 'home', 'non-profit', 'template', 'page' ],
				'templateTypes' => [ 'front-page', 'home', 'page' ],
				'source'        => 'theme',
			],
			[
				'name'          => 'about-nonprofit',
				'file'          => 'patterns/about-nonprofit.php',
				'title'         => __( 'About GoodBids', 'goodbids-nonprofit' ),
				'description'   => _x( 'Template for About Page', 'Block pattern description', 'goodbids-nonprofit' ),
				'categories'    => [ 'about','page', 'goodbids-np' ],
				'keywords'      => [ 'non-profit', 'starter', 'page' ],
				'blockTypes'    => [ 'core/post-content', 'core/group', 'core/paragraph' ],
				'postTypes'     => [ 'page', 'wp_template' ],
				'templateTypes' => [ 'front-page', 'home', 'page' ],
				'source'        => 'theme',
			],
		];

		foreach ( $patterns as $pattern ) {
			if ( ! file_exists( get_stylesheet_directory() . '/' . $pattern['file'] ) ) {
				continue;
			}

			$file      = $pattern['file'];
			$name      = $pattern['name'];
			$path      = get_stylesheet_directory() . '/' . $file;
			$extension = pathinfo( $path, PATHINFO_EXTENSION );

			unset( $pattern['file'], $pattern['name'] );

			if ( 'php' === $extension ) {
				ob_start();
				include $path;
				$content = ob_get_clean();
			} else {
				$content = wpcom_vip_file_get_contents( $path );
			}

			$pattern['content'] = $content;

			register_block_pattern(
				'goodbids-np/' . $name,
				$pattern
			);
		}
	}
);
