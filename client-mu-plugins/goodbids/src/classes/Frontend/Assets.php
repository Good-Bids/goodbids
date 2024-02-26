<?php
/**
 * Assets
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Frontend;

/**
 * This class handles loading front-end assets built with Webpack.
 *
 * @since 1.0.0
 */
class Assets {

	/**
	 * Enqueue Assets
	 */
	public function __construct() {
		// Main CSS/JS.
		$this->enqueue_main_assets();

		// Editor CSS/JS.
		$this->enqueue_editor_assets();
	}

	/**
	 * Enqueue Main Assets
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function enqueue_main_assets(): void {
		add_action(
			'wp_enqueue_scripts',
			function () {
				wp_enqueue_style(
					'goodbids-main-styles',
					GOODBIDS_PLUGIN_URL . 'build/main.css',
					[],
					goodbids()->get_version()
				);

				$js_deps   = apply_filters( 'goodbids_main_script_dependencies', [] );
				$js_handle = 'goodbids-main-scripts';
				wp_register_script(
					$js_handle,
					GOODBIDS_PLUGIN_URL . 'build/main.js',
					$js_deps,
					goodbids()->get_version(),
					[ 'strategy' => 'defer' ]
				);

				// Allow variables to be localized.
				do_action( 'goodbids_enqueue_main_scripts', $js_handle );

				wp_enqueue_script( $js_handle );
			}
		);
	}

	/**
	 * Enqueue Editor Assets
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function enqueue_editor_assets(): void {
		add_action(
			'wp_enqueue_editor',
			function () {
				wp_enqueue_style(
					'goodbids-editor-styles',
					GOODBIDS_PLUGIN_URL . 'build/editor.css',
					[],
					goodbids()->get_version()
				);

				$js_deps   = apply_filters( 'goodbids_editor_script_dependencies', [] );
				$js_handle = 'goodbids-editor-scripts';
				wp_register_script(
					$js_handle,
					GOODBIDS_PLUGIN_URL . 'build/editor.js',
					$js_deps,
					goodbids()->get_version(),
					[ 'strategy' => 'defer' ]
				);

				// Allow variables to be localized.
				do_action( 'goodbids_enqueue_editor_scripts', $js_handle );

				wp_enqueue_script( $js_handle );
			}
		);
	}
}
