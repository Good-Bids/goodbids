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
	 * Dashboard Page Slug
	 *
	 * @since 1.0.0
	 * @var string
	 */
	const PAGE_SLUG = 'goodbids';

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		// Create the Main Admin Pages.
		$this->create_admin_pages();
	}

	/**
	 * Create the Main Admin Pages.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function create_admin_pages(): void {
		add_action(
			'network_admin_menu',
			function (): void {
				$icon = GOODBIDS_PLUGIN_PATH . 'assets/images/Goodbids-Icon-white.svg';
				$icon = file_get_contents( $icon ); // phpcs:ignore

				// Main Dashboard.
				add_menu_page(
					esc_html__( 'GoodBids', 'goodbids' ),
					esc_html__( 'GoodBids', 'goodbids' ),
					'manage_network',
					self::PAGE_SLUG,
					[ $this, 'dashboard_page' ],
					'data:image/svg+xml;base64,' . base64_encode( $icon )
				);

				// Settings Page
				add_submenu_page(
					'goodbids',
					esc_html__( 'Settings', 'goodbids' ),
					esc_html__( 'Settings', 'goodbids' ),
					'manage_network',
					Settings::PAGE_SLUG,
					[ $this, 'settings_page' ]
				);

				// Invoices Page
				add_submenu_page(
					'goodbids',
					esc_html__( 'Invoices', 'goodbids' ),
					esc_html__( 'Invoices', 'goodbids' ),
					'manage_network',
					Invoices::PAGE_SLUG,
					[ $this, 'invoices_page' ]
				);

				if ( goodbids()->get_config( 'advanced.logging' ) ) {
					// Logs Page
					add_submenu_page(
						'goodbids',
						esc_html__( 'Logs', 'goodbids' ),
						esc_html__( 'Logs', 'goodbids' ),
						'manage_network',
						Logs::PAGE_SLUG,
						[ $this, 'logs_page' ]
					);
				}
			}
		);
	}

	/**
	 * Network Admin Dashboard Page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function dashboard_page(): void {
		require GOODBIDS_PLUGIN_PATH . 'views/network/dashboard.php';
	}

	/**
	 * Network Admin Settings Page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function settings_page(): void {
		require GOODBIDS_PLUGIN_PATH . 'views/network/settings.php';
	}

	/**
	 * Network Admin Invoices Page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function invoices_page(): void {
		require GOODBIDS_PLUGIN_PATH . 'views/network/invoices.php';
	}

	/**
	 * Network Admin Logs Page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function logs_page(): void {
		require GOODBIDS_PLUGIN_PATH . 'views/network/logs.php';
	}
}
