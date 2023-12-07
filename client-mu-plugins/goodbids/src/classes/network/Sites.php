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
	 * Nonprofit custom fields
	 *
	 * @since 1.0.0
	 * @var array
	 */
	private array $np_fields = [];

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->init_np_fields();
		$this->validate_new_site_fields();
		$this->new_site_form_fields();
	}

	/**
	 * Initialize Nonprofit custom fields.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init_np_fields() : void {
		$this->np_fields = apply_filters(
			'goodbids_nonprofit_custom_fields',
			[
				'gbnp-legal-name' => [
					'label'       => __( 'Nonprofit Legal Name', 'goodbids' ),
					'type'        => 'text',
					'default'     => '',
					'placeholder' => '',
					'required'    => true,
				],
				'gbnp-ein'        => [
					'label'       => __( 'Nonprofit EIN', 'goodbids' ),
					'type'        => 'text',
					'default'     => '',
					'placeholder' => 'XX-XXXXXXX',
					'required'    => true,
				],
				'gbnp-website'    => [
					'label'       => __( 'Nonprofit Website', 'goodbids' ),
					'type'        => 'url',
					'default'     => '',
					'placeholder' => 'https://',
					'required'    => true,
				],
			]
		);
	}

	/**
	 * Retrieve array of Nonprofit custom fields.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_np_fields() : array {
		if ( empty( $this->np_fields ) ) {
			$this->init_np_fields();
		}

		return $this->np_fields;
	}

	/**
	 * Validate required Nonprofit custom fields.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function validate_new_site_fields() : void {
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

				foreach ( $this->get_np_fields() as $key => $field ) {
					if ( empty( $field['required'] ) || true !== $field['required'] ) {
						continue;
					}

					if ( empty( $blog[ $key ] ) ) {
						wp_die(
							sprintf(
								'%s %s',
								esc_html( $field['label'] ),
								esc_html__( 'is a required field.', 'goodbids' )
							)
						);
					}
				}
			}
		);
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
				$fields = $this->get_np_fields();
				require GOODBIDS_PLUGIN_PATH . 'views/network/new-site-fields.php';
			}
		);
	}
}
