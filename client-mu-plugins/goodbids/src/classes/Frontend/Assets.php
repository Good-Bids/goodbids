<?php
/**
 * Assets
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Frontend;

/**
 * This class handles loading assets built with Webpack.
 *
 * @since 1.0.0
 */
class Assets {

	/**
	 * Enqueue Assets
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_main_assets' ] );
		add_action( 'wp_enqueue_editor', [ $this, 'enqueue_editor_assets' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
	}

	/**
	 * Enqueue Main Assets
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_main_assets(): void {
		wp_enqueue_style(
			'goodbids-main-styles',
			GOODBIDS_PLUGIN_URL . 'build/main.css',
			[],
			goodbids()->get_version()
		);

		$js_handle = 'goodbids-main-scripts';
		wp_register_script(
			$js_handle,
			GOODBIDS_PLUGIN_URL . 'build/main.js',
			[],
			goodbids()->get_version(),
			[ 'strategy' => 'defer' ]
		);

		// Allow variables to be localized.
		do_action( 'goodbids_enqueue_main_scripts', $js_handle );

		wp_enqueue_script( $js_handle );
	}

	/**
	 * Enqueue Editor Assets
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_editor_assets(): void {
		wp_enqueue_style(
			'goodbids-editor-styles',
			GOODBIDS_PLUGIN_URL . 'build/editor.css',
			[],
			goodbids()->get_version()
		);

		$js_handle = 'goodbids-editor-scripts';
		wp_register_script(
			$js_handle,
			GOODBIDS_PLUGIN_URL . 'build/editor.js',
			[],
			goodbids()->get_version(),
			[ 'strategy' => 'defer' ]
		);

		// Allow variables to be localized.
		do_action( 'goodbids_enqueue_editor_scripts', $js_handle );

		wp_enqueue_script( $js_handle );
	}

	/**
	 * Enqueue Admin Assets
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_admin_assets(): void {
		wp_enqueue_style(
			'admin_styles',
			GOODBIDS_PLUGIN_URL . 'build/admin.css',
			[],
			goodbids()->get_version()
		);

		$js_deps = [];

		// Enqueue the auction wizard start script.
		$wizard_start_handle = 'goodbids-auction-wizard-start';
		wp_register_script(
			$wizard_start_handle,
			GOODBIDS_PLUGIN_URL . 'build/views/auction-wizard/start.js',
			[ 'wp-element' ],
			goodbids()->get_version(),
			[ 'strategy' => 'defer' ]
		);
		$js_deps[] = $wizard_start_handle;

		$wizard_product_handle = 'goodbids-auction-wizard-product';
		wp_register_script(
			$wizard_product_handle,
			GOODBIDS_PLUGIN_URL . 'build/views/auction-wizard/product.js',
			[ 'wp-element', 'wp-api-fetch' ],
			goodbids()->get_version(),
			[ 'strategy' => 'defer' ]
		);
		$js_deps[] = $wizard_product_handle;

		$js_handle = 'goodbids-admin-scripts';
		wp_register_script(
			$js_handle,
			GOODBIDS_PLUGIN_URL . 'build/admin.js',
			$js_deps,
			goodbids()->get_version(),
			[ 'strategy' => 'defer' ]
		);

		// Allow variables to be localized.
		do_action( 'goodbids_enqueue_admin_scripts', $js_handle );

		wp_enqueue_script( $js_handle );
	}
}
