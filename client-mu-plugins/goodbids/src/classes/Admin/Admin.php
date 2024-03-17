<?php
/**
 * Admin Functionality
 *
 * @package GoodBids
 * @since 1.0.0
 */

namespace GoodBids\Admin;

use GoodBids\Network\Sites;
use GoodBids\Users\Permissions;

/**
 * Admin Main Class
 *
 * @since 1.0.0
 */
class Admin {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Initialize Submodules.
		new Assets();

		// Remove Nav Items for BDP Admins
		$this->bdp_admin_nav_cleanup();

		// Remove Nav Items for Jr Admins
		$this->jr_admin_nav_cleanup();

		// Limit access to pages
		$this->limit_access_to_pages();
	}

	/**
	 * Add an Admin Menu Separator
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $position
	 *
	 * @return void
	 */
	public function add_menu_separator( mixed $position ): void {
		$separator = '<span class="wp-menu-separator">&mdash;</span>';

		if ( is_string( $position ) ) {
			add_submenu_page(
				$position,
				__( 'Separator', 'goodbids' ),
				$separator,
				'read',
				'',
				'__return_empty_string',
			);
		} elseif ( is_numeric( $position ) ) {
			add_menu_page(
				__( 'Separator', 'goodbids' ),
				$separator,
				'read',
				'',
				'__return_empty_string',
				'',
				$position
			);
		}
	}

	/**
	 * Render an Admin Setting Field
	 *
	 * @since 1.0.0
	 *
	 * @param string $key
	 * @param array  $field
	 * @param string $prefix
	 * @param array  $data
	 * @param bool   $wrap
	 *
	 * @return void
	 */
	public function render_field( string $key, array $field, string $prefix = '', array $data = [], bool $wrap = true ) : void {
		$required    = ! empty( $field['required'] );
		$placeholder = $field['placeholder'] ?? '';
		$field_id    = $prefix ? $prefix . '-' . $key : $key;
		$value       = $data[ $key ] ?? '';

		if ( ! empty( $field['default'] ) && ! $value && '0' !== $value ) {
			$value = $field['default'];
		}

		if ( 'callback' === $field['type'] && ! empty( $field['callback'] ) && is_callable( $field['callback'] ) ) {
			/**
			 * Callback Field
			 *
			 * @since 1.0.0
			 *
			 * @param array  $field
			 * @param string $key
			 * @param string $prefix
			 * @param array  $data
			 * @param bool   $wrap
			 */
			call_user_func( $field['callback'], $field, $key, $prefix, $data, $wrap );
			return;
		}

		if ( in_array( $field['type'], [ 'text', 'url', 'email', 'tel', 'password', 'number' ], true ) ) {
			$view_file = 'text';
		} else {
			$view_file = $field['type'];
		}

		if ( ! file_exists( GOODBIDS_PLUGIN_PATH . 'views/admin/fields/' . $view_file . '.php' ) ) {
			$view_file = 'unsupported';
		}

		goodbids()->load_view(
			'admin/fields/' . $view_file . '.php',
			compact( 'key', 'field', 'prefix', 'data', 'required', 'placeholder', 'field_id', 'value', 'wrap' )
		);
	}

	/**
	 * Hide Nav Items for BDP Admins
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function bdp_admin_nav_cleanup(): void {
		add_action(
			'admin_menu',
			function () {
				if ( ! current_user_can( Permissions::BDP_ADMIN_ROLE ) || ! is_main_site() || is_super_admin() ) {
					return;
				}

				// Remove Media Admin Menu item.
				remove_menu_page( 'upload.php' );

				// Remove WooCommerce Admin Menu item.
				remove_menu_page( 'woocommerce' );

				// Remove Tools
				remove_menu_page( 'export-personal-data.php' );

				// Remove Accessibility Checker Admin Menu item.
				remove_menu_page( 'accessibility_checker' );
			},
			200
		);

		// Remove WooCommerce Marketing Menu Items.
		add_filter(
			'woocommerce_admin_features',
			function ( array $features ): array {
				if ( ! current_user_can( Permissions::BDP_ADMIN_ROLE ) || ! is_main_site() || is_super_admin() ) {
					return $features;
				}

				return array_filter(
					$features,
					fn( $feature ) => $feature !== 'marketing'
				);
			}
		);
	}

	/**
	 * Hide Nav Items for Jr Admins
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function jr_admin_nav_cleanup(): void {
		add_action(
			'admin_menu',
			function () {
				if ( ! current_user_can( Permissions::JR_ADMIN_ROLE ) || is_main_site() || is_super_admin() ) {
					return;
				}

				// Remove Media Admin Menu item.
				remove_menu_page( 'upload.php' );

				// Remove WooCommerce Admin Menu item.
				remove_menu_page( 'woocommerce' );

				// Remove Tools Admin Menu item.
				remove_menu_page( 'tools.php' );
				remove_menu_page( 'export-personal-data.php' );
			},
			200
		);

		// Remove WooCommerce Marketing Menu Items.
		add_filter(
			'woocommerce_admin_features',
			function ( array $features ): array {
				if ( ! current_user_can( Permissions::BDP_ADMIN_ROLE ) || ! is_main_site() || is_super_admin() ) {
					return $features;
				}

				return array_filter(
					$features,
					fn( $feature ) => $feature !== 'marketing'
				);
			}
		);
	}

	/**
	 * Limit access to specific pages for non-Super Admins.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function limit_access_to_pages(): void {
		add_action(
			'admin_init',
			function () {
				$pages = apply_filters(
					'goodbids_restrict_pages',
					array_filter(
						[
							intval( get_option( Sites::SUPPORT_OPTION ) ),
						]
					)
				);

				// Block access to Specific Pages
				add_filter(
					'user_has_cap',
					function ( array $all_caps, array $caps, array $args ) use ( $pages ) {
						if ( is_super_admin() ) {
							return $all_caps;
						}

						$post_id = get_the_ID();

						if ( ! $post_id && isset( $args[2] ) ) {
							$post_id = $args[2];
						}

						if ( ! $post_id || 'page' !== get_post_type( $post_id ) ) {
							return $all_caps;
						}

						if ( ! in_array( $post_id, $pages, true ) ) {
							return $all_caps;
						}

						$all_caps['publish_pages']          = false;
						$all_caps['edit_pages']             = false;
						$all_caps['edit_others_pages']      = false;
						$all_caps['edit_published_pages']   = false;
						$all_caps['edit_private_pages']     = false;
						$all_caps['delete_pages']           = false;
						$all_caps['delete_private_pages']   = false;
						$all_caps['delete_others_pages']    = false;
						$all_caps['delete_published_pages'] = false;

						return $all_caps;
					},
					10,
					3
				);
			}
		);
	}
}
