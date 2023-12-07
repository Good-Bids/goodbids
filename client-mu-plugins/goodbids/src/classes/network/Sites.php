<?php
/**
 * Multisite Functionality
 *
 * @package GoodBids
 */

namespace GoodBids\Network;

/**
 * Network Sites Class
 *
 * @since 1.0.0
 */
class Sites {

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->validate_new_site_fields();
		$this->new_site_form_fields();
	}

	/**
	 * Add GoodBids Nonprofit fields to the new site form
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function new_site_form_fields() : void {
		add_action(
			'network_site_new_form',
			function() {
				require GOODBIDS_PLUGIN_PATH . 'views/network/new-site-fields.php';
			}
		);
	}

	/**
	 * Validate required Nonprofit custom fields.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function validate_new_site_fields() {
		add_action(
			'admin_init',
			function() {
				if ( empty( $_REQUEST['action'] ) || 'add-site' !== $_REQUEST['action'] ) {
					return;
				}

				check_admin_referer( 'add-blog', '_wpnonce_add-blog' );

				if ( empty( $_POST['blog'] ) || ! is_array( $_POST['blog'] ) ) {
					wp_die( esc_html__( 'Cannot create an empty site.' ) );
				}

				$blog = $_POST['blog']; // phpcs:ignore

				$required = [
					'gbnp-legal-name' => __( 'Nonprofit Legal Name', 'goodbids' ),
					'gbnp-ein'        => __( 'Nonprofit EIN', 'goodbids' ),
					'gbnp-website'    => __( 'Nonprofit Website', 'goodbids' ),
				];

				foreach ( $required as $field => $label ) {
					if ( empty( $blog[ $field ] ) ) {
						wp_die(
							sprintf(
								'%s %s',
								esc_html( $label ),
								esc_html__( 'is a required field.', 'goodbids' )
							)
						);
					}
				}
			}
		);
	}
}
