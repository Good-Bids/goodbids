<?php
/**
 * GoodBids Network Dashboard
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Network;

/**
 * Network Admin Dashboard Class
 *
 * @since 1.0.0
 */
class Dashboard {

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		// Create the Main Dashboard Settings Page.
		$this->create_settings_page();
	}

	/**
	 * Create the Main Dashboard Settings Page.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function create_settings_page(): void {
		add_action(
			'network_admin_menu',
			function (): void {
				$icon = GOODBIDS_PLUGIN_PATH . 'assets/images/Goodbids-Icon-white.svg';
				$icon = file_get_contents( $icon ); // phpcs:ignore
				add_menu_page(
					esc_html__( 'GoodBids', 'goodbids' ),
					esc_html__( 'GoodBids', 'goodbids' ),
					'manage_network',
					'goodbids',
					[ $this, 'dashboard' ],
					'data:image/svg+xml;base64,' . base64_encode( $icon ),
				);
			}
		);
	}

	/**
	 * Network Admin Dashboard
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function dashboard(): void {
		require GOODBIDS_PLUGIN_PATH . 'views/admin/network/dashboard.php';
	}
}
