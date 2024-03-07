<?php
/**
 * GoodBids Nonprofit Admin
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Nonprofits;

use WP_Admin_Bar;

/**
 * Nonprofit Admin Class
 *
 * @since 1.0.0
 */
class Admin {

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		if ( is_main_site() || is_network_admin() ) {
			return;
		}

		// Disable some Admin items.
		$this->disable_items();
	}

	/**
	 * Disable some of the admin menu items.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function disable_items(): void {
		add_action(
			'admin_menu',
			function () {
				if ( is_main_site() || is_super_admin() ) {
					return;
				}

				// Remove WP VIP Admin Menu item
				remove_menu_page( 'vip-dashboard' );

				// Remove Posts Admin Menu item
				remove_menu_page( 'edit.php' );

				// Remove Products Admin Menu item
				remove_menu_page( 'edit.php?post_type=product' );
			},
			200
		);

		// Remove WooCommerce Marketing Menu Items.
		add_filter(
			'woocommerce_admin_features',
			function ( array $features ): array {
				if ( is_main_site() || is_super_admin() ) {
					return $features;
				}

				return array_filter(
					$features,
					fn( $feature ) => $feature !== 'marketing'
				);
			}
		);

		add_action(
			'wp_before_admin_bar_render',
			function(): void {
				if ( is_main_site() || is_super_admin() ) {
					return;
				}

				/**	@var WP_Admin_Bar $wp_admin_bar */
				global $wp_admin_bar;

				// Remove items from +New
				$wp_admin_bar->remove_node( 'new-post' );
				$wp_admin_bar->remove_node( 'new-media' );
				$wp_admin_bar->remove_node( 'new-product' );
				$wp_admin_bar->remove_node( 'new-shop_order' );
				$wp_admin_bar->remove_node( 'new-shop_coupon' );
			},
			30
		);
	}
}
