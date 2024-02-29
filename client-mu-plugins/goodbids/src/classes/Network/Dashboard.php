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

				if ( ! is_super_admin() ) {
					return;
				}

				// Nonprofits Page
				add_submenu_page(
					'goodbids',
					esc_html__( 'Nonprofits', 'goodbids' ),
					esc_html__( 'Nonprofits', 'goodbids' ),
					'manage_sites',
					Nonprofits::PAGE_SLUG,
					[ $this, 'nonprofits_page' ]
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

				// Auctions Page
				add_submenu_page(
					'goodbids',
					esc_html__( 'Auctions', 'goodbids' ),
					esc_html__( 'Auctions', 'goodbids' ),
					'manage_network',
					Auctions::PAGE_SLUG,
					[ $this, 'auctions_page' ]
				);

				goodbids()->admin->add_menu_separator( self::PAGE_SLUG );

				// Settings Page
				add_submenu_page(
					'goodbids',
					esc_html__( 'Settings', 'goodbids' ),
					esc_html__( 'Settings', 'goodbids' ),
					'manage_network',
					Settings::PAGE_SLUG,
					[ $this, 'settings_page' ]
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
	 * Network Admin Nonprofits Page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function nonprofits_page(): void {
		require GOODBIDS_PLUGIN_PATH . 'views/network/nonprofits.php';
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
	 * Network Admin Auctions Page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function auctions_page(): void {
		require GOODBIDS_PLUGIN_PATH . 'views/network/auctions.php';
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
