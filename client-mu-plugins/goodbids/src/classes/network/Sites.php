<?php
/**
 * Multisite Functionality
 *
 * @package GoodBids
 */

namespace GoodBids\Network;

use WP_Site;

/**
 * Network Sites Class
 *
 * @since 1.0.0
 */
class Sites {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const OPTION_SLUG = 'gbnp';

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
		$this->save_new_site_fields();
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
				'legal-name' => [
					'label'       => __( 'Nonprofit Legal Name', 'goodbids' ),
					'type'        => 'text',
					'default'     => '',
					'placeholder' => '',
					'required'    => true,
				],
				'ein'        => [
					'label'       => __( 'Nonprofit EIN', 'goodbids' ),
					'type'        => 'text',
					'default'     => '',
					'placeholder' => 'XX-XXXXXXX',
					'required'    => true,
				],
				'website'    => [
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

				if ( empty( $_POST[ self::OPTION_SLUG ] ) || ! is_array( $_POST[ self::OPTION_SLUG ] ) ) {
					wp_die( esc_html__( 'Missing required Nonprofit data.' ) );
				}

				// Grab our nonprofit data.
				$data = $_POST[ self::OPTION_SLUG ]; // phpcs:ignore

				// Validate required fields.
				foreach ( $this->get_np_fields() as $key => $field ) {
					if ( empty( $field['required'] ) || true !== $field['required'] ) {
						continue;
					}

					if ( empty( $data[ $key ] ) ) {
						wp_die(
							sprintf(
								'%s %s',
								esc_html( $field['label'] ),
								esc_html__( 'is a required field.', 'goodbids' )
							)
						);
					}
				}

				// Validate EIN as 9 digits.
				if ( ! empty( $data['ein'] ) ) {
					$ein = preg_replace( '/[^0-9]/', '', $data['ein'] );

					if ( 9 !== strlen( $ein ) ) {
						wp_die( esc_html__( 'Nonprofit EIN must include 9 digits. (##-#######)', 'goodbids' ) );
					}
				}

				// Validate Website as URL.
				if ( ! empty( $data['website'] ) ) {
					if ( ! filter_var( $data['website'], FILTER_VALIDATE_URL ) ) {
						wp_die( esc_html__( 'Nonprofit Website must be a valid URL. Be sure to include "https://".', 'goodbids' ) );
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
				$prefix = self::OPTION_SLUG;
				require GOODBIDS_PLUGIN_PATH . 'views/network/new-site-fields.php';
			}
		);
	}

	/**
	 * Save nonprofit field data to database.
	 *
	 * @return void
	 */
	private function save_new_site_fields() : void {
		add_action(
			'wp_initialize_site',

			/**
			 * @param WP_Site $new_site New site object.
			 * @param array $args Arguments for the initialization.
			 */
			function ( WP_Site $new_site, array $args ) {
				if ( empty( $_POST[ self::OPTION_SLUG ] ) ) { // phpcs:ignore
					// TODO: Log error.
					return;
				}

				$data = $_POST[ self::OPTION_SLUG ]; // phpcs:ignore

				foreach ( $this->get_np_fields() as $key => $field ) {
					$meta_key = self::OPTION_SLUG . '-' . $key;
					update_site_meta( $new_site->id, $meta_key, $data[ $key ] );
				}
			},
			10,
			2
		);
	}
}
