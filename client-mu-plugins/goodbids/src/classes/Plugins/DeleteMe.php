<?php
/**
 * Delete Me plugin settings
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins;

use WP_User;

/**
 * This class handles Delete Me settings.
 *
 * @since 1.0.0
 */
class DeleteMe {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	private string $slug = 'delete-me';

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		if ( ! goodbids()->is_plugin_active( $this->slug ) ) {
			return;
		}

		// Adjust the settings.
		$this->adjust_settings();

		// Enable Customers to Delete their Account.
		$this->set_customer_cap();
	}

	/**
	 * Adjust the DeleteMe default settings.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function adjust_settings(): void {
		add_filter(
			'option_plugin_delete_me',
			function ( mixed $value ): mixed {
				if ( ! empty( $value['settings'] ) ) {
					$value['settings']['delete_comments']        = true;
					$value['settings']['ms_delete_from_network'] = true;
				}

				// Include Customer Role
				if ( is_array( $value ) ) {
					if ( ! isset( $value['network_selected_roles'] ) || ! in_array( 'customer', $value['network_selected_roles'], true ) ) {
						$value['network_selected_roles'][] = 'customer';
					}
				}

				return $value;
			}
		);
	}

	/**
	 * Change the customer role capabilities globally.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function set_customer_cap(): void {
		add_action(
			'plugins_loaded',
			function () {
				global $wp_roles;
				if ( empty( $wp_roles->roles['customer'] ) ) {
					return;
				}

				if ( ! is_array( $wp_roles->roles['customer']['capabilities'] ) ) {
					$wp_roles->roles['customer']['capabilities'] = [ 'read' => true ];
				}

				$wp_roles->roles['customer']['capabilities']['plugin_delete_me'] = true;
			}
		);
	}
}
