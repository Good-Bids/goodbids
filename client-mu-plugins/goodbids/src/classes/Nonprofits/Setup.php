<?php
/**
 * GoodBids Nonprofit Setup Dashboard
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Nonprofits;

/**
 * Setup Class
 *
 * @since 1.0.0
 */
class Setup {

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->add_menu_dashboard_page();
	}


	/**
	 * Add the menu page for the setup dashboard
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function add_menu_dashboard_page(): void {
		add_action(
			'admin_menu',
			function () {
				add_menu_page(
					__( 'Nonprofit Site Onboarding', 'goodbids' ),
					__( 'Site Setup', 'goodbids' ),
					'manage_options',
					'site-setup',
					[ $this, 'setup_content' ],
					'dashicons-admin-site-alt3',
					'1.1'
				);
			}
		);
	}

	/**
	 * Include the setup content view
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function setup_content(): void {
		require GOODBIDS_PLUGIN_PATH . 'views/admin/setup/site-setup.php';
	}
}
