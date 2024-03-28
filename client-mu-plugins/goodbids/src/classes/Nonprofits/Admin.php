<?php
/**
 * GoodBids Nonprofit Admin
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Nonprofits;

use GoodBids\Network\Nonprofit;
use GoodBids\Users\Permissions;
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

		// Update the Stripe Email Address if the Admin Email changes.
		$this->handle_admin_email_change();
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
				if ( is_main_site() ) {
					return;
				}

				global $submenu;

				// Remove WP VIP Admin Menu item
				remove_menu_page( 'vip-dashboard' );

				// Remove Tidio Menu Item
				remove_menu_page('tidio-live-chat');

				// Remove miniorange OAuth Server
				remove_menu_page('mo_oauth_server_settings');

				// Remove miniorange OAuth Server
				remove_menu_page('topbar-options-menu');

				// Remove Posts Admin Menu item
				remove_menu_page( 'edit.php' );

				if ( is_super_admin() || current_user_can( Permissions::BDP_ADMIN_ROLE ) ) {
					return;
				}

				// Remove My Sites under Dashboard
				remove_menu_page(  'my-sites.php' );
				unset( $submenu['index.php'][5] );
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
				if ( is_main_site() ) {
					return;
				}

				/**	@var WP_Admin_Bar $wp_admin_bar */
				global $wp_admin_bar;

				$wp_admin_bar->remove_node( 'wp-logo' );

				if ( ! is_front_page() ) {
					$wp_admin_bar->remove_node( 'site-editor' );
				}

				// Remove items from +New
				$wp_admin_bar->remove_node( 'new-post' );
				$wp_admin_bar->remove_node( 'new-media' );
				$wp_admin_bar->remove_node( 'new-product' );
				$wp_admin_bar->remove_node( 'new-shop_order' );
				$wp_admin_bar->remove_node( 'new-shop_coupon' );

				// Remove Main Admin Bar Items
				$wp_admin_bar->remove_node( 'view-store' );
				$wp_admin_bar->remove_node( 'edit-site' );
				$wp_admin_bar->remove_node( 'view-site' );
				$wp_admin_bar->remove_node( 'dashboard' );
				$wp_admin_bar->remove_node( 'appearance' );

				if ( is_super_admin() || current_user_can( Permissions::BDP_ADMIN_ROLE ) ) {
					return;
				}

				$wp_admin_bar->remove_node( 'my-sites' );
			},
			30
		);
	}

	/**
	 * Potentially update Stripe Email if Admin Email changes.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function handle_admin_email_change(): void {
		add_action( 'update_option',
			function ( string $option_name, mixed $old_value, mixed $new_value ): void {
				if ( 'admin_email' !== $option_name || $old_value === $new_value ) {
					return;
				}

				$nonprofit = new Nonprofit( get_current_blog_id() );

				if ( $nonprofit->get_finance_contact_email() || $nonprofit->get_primary_contact_email() ) {
					return;
				}

				$nonprofit->update_stripe_email( $new_value );
			},
			10,
			3
		);
	}
}
