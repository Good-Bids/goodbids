<?php
/**
 * User Roles and Permissions Methods
 *
 * @package GoodBids
 * @since 1.0.0
 */

namespace GoodBids\Users;

use GoodBids\Utilities\Log;
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
	private int $version = 2;

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
		Log::debug( 'Updating Permissions Version.' );
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

				Log::debug( 'Creating BDP Admin Role.' );

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

				if ( ! $role->has_cap( 'publish_auctions' ) ) {
					return;
				}

				Log::debug( 'Setting Admin Auction Capabilities.' );

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

				$this->update_role( $role, $capabilities );

				$this->update_version();
			}
		);
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

}
