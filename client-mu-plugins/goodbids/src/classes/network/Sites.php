<?php
/**
 * Multisite Functionality
 *
 * @since 1.0.0
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
		$this->save_edit_site_fields();
		$this->new_site_form_fields();
		$this->edit_site_form_fields();

		// New Site Actions
		$this->activate_child_theme_on_new_site();
	}

	/**
	 * Initialize Nonprofit custom fields.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init_np_fields(): void {
		$this->np_fields = apply_filters(
			'goodbids_nonprofit_custom_fields',
			[
				'legal-name' => [
					'label'       => __( 'Nonprofit Legal Name', 'goodbids' ),
					'type'        => 'text',
					'default'     => '',
					'placeholder' => '',
					'required'    => true,
					'context'     => 'both',
				],
				'ein'        => [
					'label'       => __( 'Nonprofit EIN', 'goodbids' ),
					'type'        => 'text',
					'default'     => '',
					'placeholder' => 'XX-XXXXXXX',
					'required'    => true,
					'context'     => 'both',
				],
				'website'    => [
					'label'       => __( 'Nonprofit Website', 'goodbids' ),
					'type'        => 'url',
					'default'     => '',
					'placeholder' => 'https://',
					'required'    => true,
					'context'     => 'both',
				],
				'status'     => [
					'label'       => __( 'Site Status', 'goodbids' ),
					'type'        => 'select',
					'default'     => 'pending',
					'placeholder' => '',
					'required'    => true,
					'context'     => 'edit',
					'options'     => [
						[
							'label' => __( 'Pending', 'goodbids' ),
							'value' => 'pending',
						],
						[
							'label' => __( 'Published', 'goodbids' ),
							'value' => 'published',
						],
						[
							'label' => __( 'Disabled', 'goodbids' ),
							'value' => 'disabled',
						],
					],
				],
			]
		);
	}

	/**
	 * Retrieve array of Nonprofit custom fields.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context Context for the fields. Default 'both'.
	 *                        Accepts 'create', 'edit', or 'both'.
	 *
	 * @return array
	 */
	public function get_np_fields( string $context = 'both' ): array {
		if ( empty( $this->np_fields ) ) {
			$this->init_np_fields();
		}

		if ( 'both' === $context ) {
			return $this->np_fields;
		}

		$fields = [];

		foreach ( $this->np_fields as $key => $field ) {
			if ( empty( $field['context'] ) ) {
				continue;
			}

			if ( in_array( $field['context'], [ $context, 'both' ], true ) ) {
				$fields[ $key ] = $field;
			}
		}

		return $fields;
	}

	/**
	 * Retrieve Nonprofit custom field data.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $site_id Site ID.
	 * @param string $field_id The Field ID to retrieve. Default empty.
	 *
	 * @return mixed
	 */
	public function get_np_data( int $site_id, string $field_id = '' ): mixed {
		$data = [];

		foreach ( $this->get_np_fields() as $key => $field ) {
			$field_key   = self::OPTION_SLUG . '-' . $key;
			$field_value = get_site_meta( $site_id, $field_key, true );

			if ( ! $field_value && ! empty( $field['default'] ) ) {
				$field_value = $field['default'];
			}

			if ( $key === $field_id ) {
				return $field_value;
			}

			$data[ $key ] = $field_value;
		}

		return $data;
	}

	/**
	 * Validate required Nonprofit custom fields.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function validate_new_site_fields(): void {
		add_action(
			'admin_init',
			function () {
				if ( empty( $_REQUEST['action'] ) || 'add-site' !== $_REQUEST['action'] ) {
					return;
				}

				check_admin_referer( 'add-np-site', '_wpnonce_add-np-site' );

				if ( empty( $_POST[ self::OPTION_SLUG ] ) || ! is_array( $_POST[ self::OPTION_SLUG ] ) ) {
					wp_die( esc_html__( 'Missing required Nonprofit data.' ) );
				}

				// Grab our nonprofit data.
				$data = $_POST[ self::OPTION_SLUG ]; // phpcs:ignore

				// Validate required fields.
				foreach ( $this->get_np_fields( 'create' ) as $key => $field ) {
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
	private function new_site_form_fields(): void {
		add_action(
			'network_site_new_form',
			function () {
				$fields = $this->get_np_fields( 'create' );
				$prefix = self::OPTION_SLUG;
				require GOODBIDS_PLUGIN_PATH . 'views/network/new-site-fields.php';
			}
		);
	}

	/**
	 * Add GoodBids Nonprofit fields to the edit site form
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function edit_site_form_fields(): void {
		add_action(
			'network_site_info_form',
			function ( $site_id ) {
				$data   = $this->get_np_data( $site_id );
				$fields = $this->get_np_fields( 'edit' );
				$prefix = self::OPTION_SLUG;
				require GOODBIDS_PLUGIN_PATH . 'views/network/edit-site-fields.php';
			}
		);
	}

	/**
	 * Save nonprofit field data to database.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function save_new_site_fields(): void {
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

				foreach ( $this->get_np_fields( 'create' ) as $key => $field ) {
					if ( ! isset( $data[ $key ] ) ) {
						continue;
					}

					$meta_key   = self::OPTION_SLUG . '-' . $key;
					$meta_value = sanitize_text_field( $data[ $key ] );
					update_site_meta( $new_site->id, $meta_key, $meta_value );
				}

				$this->init_site_defaults( $new_site->id );
			},
			10,
			2
		);
	}

	/**
	 * Update nonprofit field data to database.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function save_edit_site_fields(): void {
		add_action(
			'wp_initialize_site',
			/**
			 * @param WP_Site $new_site New site object.
			 * @param WP_Site $old_site Old site object.
			 */
			function ( WP_Site $new_site, WP_Site $old_site ) {
				if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
					return;
				}

				if ( empty( $_POST[ self::OPTION_SLUG ] ) ) {
					// TODO: Log error.
					return;
				}

				check_admin_referer( 'edit-site' );

				$data = $_POST[ self::OPTION_SLUG ]; // phpcs:ignore

				foreach ( $this->get_np_fields( 'edit' ) as $key => $field ) {
					if ( ! isset( $data[ $key ] ) ) {
						continue;
					}

					$meta_key   = self::OPTION_SLUG . '-' . $key;
					$meta_value = sanitize_text_field( $data[ $key ] );
					update_site_meta( $new_site->id, $meta_key, $meta_value );
				}
			},
			10,
			2
		);
	}

	/**
	 * Activate nonprofit child theme.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function activate_child_theme_on_new_site(): void {
		add_action(
			'goodbids_init_site',
			function ( $site_id ) {
				$stylesheet = 'goodbids-nonprofit';

				// Check if the Goodbids child theme exists first.
				if ( wp_get_theme( $stylesheet )->exists() ) {
					switch_theme( $stylesheet );
				}
			}
		);
	}

	/**
	 * Initialize new site defaults.
	 *
	 * @since 1.0.0
	 *
	 * @param int $site_id
	 *
	 * @return void
	 */
	private function init_site_defaults( int $site_id ): void {
		switch_to_blog( $site_id );

		do_action( 'goodbids_init_site', $site_id );

		restore_current_blog();
	}
}
