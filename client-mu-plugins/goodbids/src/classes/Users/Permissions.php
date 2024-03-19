<?php
/**
 * User Roles and Permissions Methods
 *
 * @package GoodBids
 * @since 1.0.0
 */

namespace GoodBids\Users;

use WP_Role;

/**
 * User Class
 *
 * @since 1.0.0
 */
class Permissions {

	/**
	 * @since 1.0.0
	 */
	const VERSION_OPTION = 'goodbids_permissions_version';

	/**
	 * @since 1.0.0
	 */
	const BDP_ADMIN_ROLE = 'bdp_administrator';

	/**
	 * @since 1.0.0
	 */
	const JR_ADMIN_ROLE = 'jr_administrator';

	/**
	 * Increment this number whenever changes are made to this class.
	 *
	 * v5: Added plugin_delete_me capability for Customers. - 3/18/2024
	 * v4: Fix for removed roles. - 3/8/2024
	 * v3: Added Jr Admin role. - 3/8/2024
	 * v2: Added BDP Admin role. - 2/26/2024
	 * v1: Initial version.
	 *
	 * @since 1.0.0
	 *
	 * @var int $version
	 */
	private int $version = 5;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Initialize custom BDP Admin role.
		$this->init_bdp_admin_role();

		// Initialize custom Jr. Site Admin role.
		$this->init_jr_admin_role();

		// Set Admin Auction Capabilities.
		$this->set_admin_auction_capabilities();

		// Remove some user roles.
		$this->remove_unused_roles();

		// Disable some user roles conditionally.
		$this->disable_some_roles();

		// Set the default role for new users.
		$this->set_default_role();

		// Set the default role for new users.
		$this->adjust_customer_capabilities();

		// Perform the update.
		$this->maybe_do_update_version();
	}

	/**
	 * Update Version
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function maybe_do_update_version(): void {
		add_action(
			'admin_init',
			function () {
				if ( ! $this->needs_update() ) {
					return;
				}

				update_option( self::VERSION_OPTION, $this->version );
			},
			800
		);
	}

	/**
	 * Check if we need an update.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function needs_update(): bool {
		return version_compare( $this->version, get_option( self::VERSION_OPTION ), '>' );
	}

	/**
	 * Initialize BDP Admin Role
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init_bdp_admin_role(): void {
		add_action(
			'admin_init',
			function () {
				if ( ! $this->needs_update() ) {
					return;
				}

				// Role Name.
				$role_name = __( 'BDP Administrator', 'goodbids' );

				// Limited capabilities to Add Sites from Network Admin.
				$role_capabilities = [
					'read',
					'upload_files',
					'manage_network',
					'manage_sites',
					'create_sites',
					'manage_woocommerce',
				];

				$this->create_or_update_role( self::BDP_ADMIN_ROLE, $role_name, $role_capabilities );
			}
		);
	}

	/**
	 * Initialize Jr Admin Role
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init_jr_admin_role(): void {
		add_action(
			'admin_init',
			function () {
				if ( ! $this->needs_update() ) {
					return;
				}

				// Role Name.
				$role_name = __( 'Junior Site Admin', 'goodbids' );
				$core_caps = [
					// Core Capabilities
					'export'                  => false,
					'import'                  => false,
					'update_core'             => false,
					'manage_links'            => false,
					'manage_categories'       => false,
					'manage_options'          => false,
					'view_site_health_checks' => false,
					'install_languages'       => false,
				];

				$combo_caps   = $this->cascade_capabilities(
					[ 'user', 'plugin', 'theme' ],
					[ 'edit', 'delete' ],
					[],
					false
				);
				$user_caps   = $this->cascade_capabilities(
					[ 'user' ],
					[ 'create', 'list', 'promote', 'remove' ],
					[],
					false
				);
				$plugin_caps = $this->cascade_capabilities(
					[ 'plugin' ],
					[ 'activate', 'install', 'resume', 'update' ],
					[],
					false
				);
				$theme_caps  = $this->cascade_capabilities(
					[ 'theme' ],
					[ 'install' ],
					[],
					false
				);

				$cap_changes = array_merge(
					$core_caps,
					$combo_caps,
					$user_caps,
					$plugin_caps,
					$theme_caps
				);

				$this->duplicate_role( 'administrator', self::JR_ADMIN_ROLE, $role_name, $cap_changes );
			}
		);
	}

	/**
	 * Grant the Customer role the ability to delete their account.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function adjust_customer_capabilities(): void {
		add_action(
			'init',
			function () {
				if ( ! $this->needs_update() ) {
					return;
				}

				$role = get_role( 'customer' );

				if ( ! $role ) {
					return;
				}

				$role->add_cap( 'plugin_delete_me' );
			}
		);
	}

	/**
	 * Create or Update Role
	 *
	 * @since 1.0.0
	 *
	 * @param string $role_id
	 * @param string $role_name
	 * @param array  $capabilities
	 *
	 * @return void
	 */
	private function create_or_update_role( string $role_id, string $role_name, array $capabilities ): void {
		$role = get_role( $role_id );

		if ( ! $role ) {
			$this->create_role( $role_id, $role_name, $capabilities );
		} else {
			$this->update_role( $role, $capabilities );
		}
	}

	/**
	 * Create a Role
	 *
	 * @since 1.0.0
	 *
	 * @param string $role_id
	 * @param string $role_name
	 * @param array  $capabilities
	 *
	 * @return void
	 */
	private function create_role( string $role_id, string $role_name, array $capabilities ): void {
		if ( function_exists( 'wpcom_vip_add_role' ) ) {
			wpcom_vip_add_role( $role_id, $role_name, $capabilities );
		} else {
			add_role( $role_id, $role_name, $capabilities );
		}
	}

	/**
	 * Duplicate an existing role.
	 *
	 * @since 1.0.0
	 *
	 * @param string $original
	 * @param string $new_role
	 * @param string $role_name
	 * @param array $modified_caps
	 *
	 * @return void
	 */
	private function duplicate_role( string $original, string $new_role, string $role_name, array $modified_caps = [] ): void {
		if ( function_exists( 'wpcom_vip_duplicate_role' ) ) {
			wpcom_vip_duplicate_role(
				$original,
				$new_role,
				$role_name,
				$modified_caps
			);
		} else {
			$admin_caps = get_role( $original )->capabilities;
			$new_caps   = array_merge( $admin_caps, $modified_caps );

			$this->create_or_update_role( $new_role, $role_name, $new_caps );
		}
	}

	/**
	 * Set Administrator Capabilities for Auctions
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function set_admin_auction_capabilities(): void {
		add_action(
			'admin_init',
			function () {
				if ( ! $this->needs_update() ) {
					return;
				}

				$role = get_role( 'administrator' );

				if ( ! $role->has_cap( 'publish_auctions' ) ) {
					return;
				}

				$types = [
					goodbids()->auctions->get_post_type(),
					'auction'
				];

				$capabilities = $this->cascade_capabilities( $types );

				$this->update_role( $role, $capabilities );
			}
		);
	}

	/**
	 * Generate array of capabilities
	 *
	 * @param array $types
	 * @param mixed $actions
	 * @param mixed $modifiers
	 * @param mixed $value
	 *
	 * @return array
	 */
	private function cascade_capabilities( array $types, mixed $actions = 'all', mixed $modifiers = 'all', mixed $value = null ): array {
		$capabilities = [];

		if ( 'all' === $actions ) {
			$actions = [
				'read',
				'edit',
				'delete',
				'publish',
			];
		}

		if ( 'all' === $modifiers ) {
			$modifiers = [
				'others',
				'private',
				'published',
			];
		}

		foreach ( $actions as $action ) {
			foreach ( $types as $type ) {
				if ( ! is_null( $value ) ) {
					$capabilities[ $action . '_' . $type ]      = $value;
					$capabilities[ $action . '_' . $type . 's' ] = $value;
				} else {
					$capabilities[] = $action . '_' . $type;
					$capabilities[] = $action . '_' . $type . 's';
				}

				foreach ( $modifiers as $modifier ) {
					if ( str_contains( $modifier, $action ) ) {
						continue;
					}

					if ( ! is_null( $value ) ) {
						$capabilities[ $action . '_' . $modifier . '_' . $type ]       = $value;
						$capabilities[ $action . '_' . $modifier . '_' . $type . 's' ] = $value;
					} else {
						$capabilities[] = $action . '_' . $modifier . '_' . $type;
						$capabilities[] = $action . '_' . $modifier . '_' . $type . 's';
					}
				}
			}
		}

		return $capabilities;
	}

	/**
	 * Add capabilities to role
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Role $role
	 * @param array   $capabilities
	 *
	 * @return void
	 */
	private function update_role( WP_Role $role, array $capabilities ): void {
		$role->capabilities = array_merge( $role->capabilities, $capabilities );

		if ( function_exists( 'wpcom_vip_add_role_caps' ) ) {
			wpcom_vip_add_role_caps( $role->name, $capabilities );
		} else {
			foreach ( $capabilities as $capability ) {
				$role->add_cap( $capability );
			}
		}
	}

	/**
	 * Remove Unused Roles for Nonprofit sites.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function remove_unused_roles(): void {
		add_action(
			'init',
			function () {
				if ( ! $this->needs_update() ) {
					return;
				}

				// This permanently removes the roles.
				remove_role( 'subscriber' );
				remove_role( 'contributor' );
				remove_role( 'shop_manager' );
				remove_role( 'author' );
				remove_role( 'editor' );
				remove_role( 'vip_support' );
				remove_role( 'vip_support_inactive' );
			}
		);
	}

	/**
	 * Conditionally disable some Roles for Nonprofit sites.
	 * This hides roles without deleting them.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function disable_some_roles(): void {
		add_filter(
			'editable_roles',
			function ( array $roles ): array {
				if ( goodbids()->utilities->network_is_main_site() ) {
					return $roles;
				}

				unset( $roles[ self::BDP_ADMIN_ROLE ] );

				return $roles;
			}
		);
	}

	/**
	 * Set the default role for Nonprofits to Administrator.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function set_default_role(): void {
		add_filter(
			'option_default_role',
			function ( string $role ): string {
				if ( is_main_site() ) {
					return $role;
				}

				return 'administrator';
			}
		);
	}

}
