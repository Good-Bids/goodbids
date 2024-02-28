<?php
/**
 * Admin Assets
 *
 * @since 1.0.0
 *
 * @package GoodBids
 */

namespace GoodBids\Admin;

/**
 * Admin Assets Class
 *
 * @since 1.0.0
 */
class Assets {

	/**
	 * Enqueue Admin Assets
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->init_assets();
	}

	/**
	 * Initialize Admin Assets
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init_assets(): void {
		add_action(
			'admin_enqueue_scripts',
			function () {
				wp_enqueue_style(
					'goodbids-admin-styles',
					GOODBIDS_PLUGIN_URL . 'build/admin.css',
					[],
					goodbids()->get_version()
				);

				$js_deps   = apply_filters( 'goodbids_admin_script_dependencies', [] );
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
		);
	}
}
