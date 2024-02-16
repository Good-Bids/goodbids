<?php
/**
 * Built Assets
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Frontend;

use GoodBids\Core;

/**
 * This class handles loading assets built with Webpack.
 */

class BuiltAssets {
	public function __construct() {


		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_main_assets' ] );
		add_action( 'wp_enqueue_editor', [ $this, 'enqueue_editor_assets' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
	}

	public function enqueue_main_assets() {
		wp_enqueue_style(
			'main_styles',
			GOODBIDS_PLUGIN_PATH . 'build/main.css',
			[],
			goodbids()->get_version()
		);

		wp_enqueue_script(
			'main_scripts',
			GOODBIDS_PLUGIN_PATH . 'build/main.js',
			[],
			goodbids()->get_version(),
			['strategy'  => 'defer',]
		);
	}

	public function enqueue_editor_assets() {
		wp_enqueue_style(
			'editor_styles',
			GOODBIDS_PLUGIN_PATH . 'build/editor.css',
			[],
			goodbids()->get_version()
		);

		wp_enqueue_script(
			'editor_scripts',
			GOODBIDS_PLUGIN_PATH . 'build/editor.js',
			[],
			goodbids()->get_version(),
			['strategy'  => 'defer',]
		);
	}

	public function enqueue_admin_assets() {
		wp_enqueue_style(
			'admin_styles',
			GOODBIDS_PLUGIN_PATH . 'build/admin.css',
			[],
			goodbids()->get_version()
		);

		wp_enqueue_script(
			'admin_scripts',
			GOODBIDS_PLUGIN_PATH . 'build/admin.js',
			[],
			goodbids()->get_version(),
			['strategy'  => 'defer',]
		);

		wp_enqueue_script(
			'auction_wizard_start',
			GOODBIDS_PLUGIN_PATH . 'build/views/auction-wizard/start.js',
			['wp-element'],
			goodbids()->get_version(),
			['strategy'  => 'defer',]
		);

		wp_enqueue_script(
			'auction_wizard_product',
			GOODBIDS_PLUGIN_PATH . 'build/views/auction-wizard/product.js',
			['wp-element', 'wp-api-fetch'],
			goodbids()->get_version(),
			['strategy'  => 'defer',]
		);
	}
}
