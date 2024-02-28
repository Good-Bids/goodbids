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
	 * Increment this number whenever changes are made to this class.
	 *
	 * @since 1.0.0
	 *
	 * @var int $version
	 */
	private int $version = 1;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Initialize custom BDP Admin role.
		$this->init_bdp_admin_role();

		// Set Admin Auction Capabilities.
		$this->set_admin_auction_capabilities();
	}

	/**
	 * Update Version
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function update_version(): void {
		update_option( self::VERSION_OPTION, $this->version );
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
				if ( $this->version <= get_option( self::VERSION_OPTION ) ) {
					return;
				}

				// Role ID and Name.
				$role_id   = 'bdp_administrator';
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

				$this->create_or_update_role( $role_id, $role_name, $role_capabilities );

				$this->update_version();
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
	 * Update Role Capabilities
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Role $role
	 * @param array   $capabilities
	 *
	 * @return void
	 */
	private function update_role( WP_Role $role, array $capabilities ): void {
		$role->capabilities = $capabilities;
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
				if ( $this->version <= get_option( self::VERSION_OPTION ) ) {
					return;
				}

				$role = get_role( 'administrator' );

				if ( ! $this->has_added_capabilities( $role ) ) {
					return;
				}

				$types = [
					goodbids()->auctions->get_post_type(),
					'auction'
				];

				$actions = [
					'read',
					'edit',
					'delete',
					'publish',
				];

				$modifiers = [
					'others',
					'private',
					'published',
				];

				$capabilities = [];

				foreach ( $actions as $action ) {
					foreach ( $types as $type ) {
						$capabilities[] = $action . '_' . $type;
						$capabilities[] = $action . '_' . $type . 's';

						foreach ( $modifiers as $modifier ) {
							if ( str_contains( $modifier, $action ) ) {
								continue;
							}

							$capabilities[] = $action . '_' . $modifier . '_' . $type;
							$capabilities[] = $action . '_' . $modifier . '_' . $type . 's';
						}
					}
				}

				$this->add_capabilities( $role, $capabilities );

				$this->update_version();
			}
		);
	}

	/**
	 * Check if capabilities have already been added.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Role $role
	 *
	 * @return bool
	 */
	private function has_added_capabilities( WP_Role $role ): bool {
		if ( ! $role->has_cap( 'publish_auction' ) ) {
			return false;
		}

		return true;
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
	private function add_capabilities( WP_Role $role, array $capabilities ): void {
		if ( function_exists( 'wpcom_vip_add_role_caps' ) ) {
			wpcom_vip_add_role_caps($role->name, $capabilities );
		} else {
			foreach ( $capabilities as $capability ) {
				$role->add_cap( $capability );
			}
		}
	}

}
