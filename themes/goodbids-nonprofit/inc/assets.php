<?php
/**
 * Pulls in custom assets for the GoodBids child theme.
 *
 * @package GoodBids_Nonprofit
 */

namespace GoodBids_Nonprofit\Assets;

/**
 * Enqueue Theme Styles.
 *
 * @since 1.0.0
 *
 * @return void
 */

function enqueue_styles(): void {
	wp_enqueue_style(
		'goodbids-nonprofit',
		get_stylesheet_directory_uri() . '/assets/css/theme.css',
		array(),
		wp_get_theme()->get( 'Version' )
	);
}

add_action( 'wp_enqueue_scripts', 'GoodBids_Nonprofit\Assets\enqueue_styles' );
