<?php
/**
 * General Network Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Network;

use WP_Role;

/**
 * Network Class
 *
 * @since 1.0.0
 */
class Network {

	/**
	 * Invoices
	 *
	 * @since 1.0.0
	 * @var ?Invoices
	 */
	public ?Invoices $invoices = null;

	/**
	 * Logs
	 *
	 * @since 1.0.0
	 * @var ?Logs
	 */
	public ?Logs $logs = null;

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		// Init Submodules.
		$this->invoices = new Invoices();
		$this->logs     = new Logs();

		// Initialize custom BDP Admin role.
		$this->init_bdp_admin_role();
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
			'init',
			function () {
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
}
