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

		// Add the Delete Me form to the My Account > Account Details page.
		$this->insert_delete_account_form();
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

	/**
	 * Add the Delete Me link to the My Account > Account Details page.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function insert_delete_account_form(): void {
		add_action(
			'woocommerce_after_edit_account_form',
			function (): void {
				if ( ! current_user_can( 'customer' ) || $this->current_user_is_admin() ) {
					return;
				}

				printf(
					'<h3>%s</h3>',
					esc_html__( 'Delete Your Account', 'goodbids' )
				);

				echo do_shortcode( '[plugin_delete_me]' ); // phpcs:ignore
			}
		);
	}

	/**
	 * Check if Current user is Admin on any Nonprofit
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function current_user_is_admin(): bool {
		if ( is_super_admin() ) {
			return true;
		}

		if ( current_user_can( 'administrator' ) ) {
			return true;
		}

		$is_admin = false;

		goodbids()->sites->loop(
			function () use ( &$is_admin ) {
				if ( current_user_can( 'administrator' ) ) {
					$is_admin = true;
				}
			}
		);

		return $is_admin;
	}
}
