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
		$this->enqueue_css();
	}

	/**
	 * Enqueue Admin CSS
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function enqueue_css(): void {
		add_action(
			'admin_enqueue_scripts',
			function () {
				wp_enqueue_style(
					'goodbids-admin',
					GOODBIDS_PLUGIN_URL . asset_path( '/assets/js/admin.tsx', 'css' ),
					[],
					goodbids()->get_version()
				);
			}
		);
	}
}
