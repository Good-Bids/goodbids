<?php
/**
 * GoodBids Network Settings
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Network;

/**
 * Network Admin Settings Class
 *
 * @since 1.0.0
 */
class Settings {

	/**
	 * Settings Page Slug
	 *
	 * @since 1.0.0
	 * @var string
	 */
	const PAGE_SLUG = 'gb-settings';

	/**
	 * Settings Meta Key
	 *
	 * @since 1.0.0
	 * @var string
	 */
	const SETTINGS_META_KEY = 'goodbids';

	/**
	 * Settings Nonce verification key
	 *
	 * @since 1.0.0
	 * @var string
	 */
	const SETTINGS_NONCE = '_goodbids_nonce';

	/**
	 * @since 1.0.0
	 * @var array
	 */
	private array $settings = [];

	/**
	 * @since 1.0.0
	 * @var array
	 */
	private array $data = [];

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		// Initialize Settings.
		$this->init_settings();

		// Maybe save settings.
		$this->maybe_save_settings();
	}

	/**
	 * Initialize Settings
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init_settings(): void {
		$this->settings = [
			'environment' => [
				'label'       => __( 'Environment', 'goodbids' ),
				'type'        => 'select',
				'default'     => 'staging',
				'required'    => true,
				'section'     => 'auctioneer',
				'options'     => [
					[
						'label' => __( 'Develop', 'goodbids' ),
						'value' => 'develop',
					],
					[
						'label' => __( 'Staging', 'goodbids' ),
						'value' => 'staging',
					],
					[
						'label' => __( 'Production', 'goodbids' ),
						'value' => 'production',
					],
				],
			],
		];

		add_action(
			'admin_init',
			function (): void {
				$this->load_settings();

				add_settings_section(
					'auctioneer',
					__( 'Auctioneer', 'goodbids' ),
					'__return_empty_string',
					self::PAGE_SLUG
				);

				foreach ( $this->settings as $id => $setting ) {
					$key = $setting['section'] . '.' . $id;

					add_settings_field(
						$key,
						$setting['label'],
						function () use ( $key, $setting ): void {
							$setting = $this->handle_overridden( $setting, $key );

							goodbids()->admin->render_field(
								$key,
								$setting,
								self::SETTINGS_META_KEY,
								$this->data,
								false
							);
						},
						self::PAGE_SLUG,
						$setting['section']
					);

					register_setting( $key, self::SETTINGS_META_KEY );
				}
			}
		);
	}

	/**
	 * Handle Overridden field values.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $setting
	 * @param string $key
	 *
	 * @return array
	 */
	private function handle_overridden( array $setting, string $key ): array {
		$stored = $this->get_setting( $key );

		if ( $stored && $stored !== goodbids()->get_config( $key ) ) {
			$setting['after'] = sprintf(
				'<span class="dashicons dashicons-saved" style="margin-top:4px" title="%s"></span>',
				esc_attr__( 'The config.json setting has been overridden.', 'goodbids' )
			);
		}

		return $setting;
	}

	/**
	 * Load Settings
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function load_settings(): void {
		$settings = get_site_meta( get_main_site_id(), self::SETTINGS_META_KEY, true );

		if ( ! is_array( $settings ) ) {
			$settings = [];
		}

		$defaults = [];

		foreach ( array_keys( $settings ) as $key ) {
			$defaults[ $key ] = goodbids()->get_config( $key );
		}

		$this->data = array_merge( $defaults, $settings );
	}

	/**
	 * Get a Network setting value.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function get_setting( string $key ): mixed {
		return $this->data[ $key ] ?? null;
	}

	/**
	 * Maybe Save Settings
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function maybe_save_settings(): void {
		add_action(
			'admin_init',
			function (): void {
				if ( empty( $_POST ) ) {
					return;
				}

				if ( ! empty( $_POST[ self::SETTINGS_NONCE ] ) && ! wp_verify_nonce( sanitize_text_field( $_POST[ self::SETTINGS_NONCE ] ), self::PAGE_SLUG ) ) {
					add_settings_error(
						self::PAGE_SLUG,
						'nonce_error',
						__( 'Nonce verification failed.', 'goodbids' )
					);
					return;
				}

				$data = [];

				if ( ! empty( $_POST[ self::SETTINGS_META_KEY ] ) && is_array( $_POST[ self::SETTINGS_META_KEY ] ) ) {
					foreach ( $_POST[ self::SETTINGS_META_KEY ] as $key => $value ) { // phpcs:ignore
						$data[ $key ]       = sanitize_text_field( $value );
						$this->data[ $key ] = $data[ $key ];
					}
				}

				if ( ! empty( $data ) ) {
					$prev = get_site_meta( get_main_site_id(), self::SETTINGS_META_KEY, true );

					if ( $prev ) {
						update_site_meta( get_main_site_id(), self::SETTINGS_META_KEY, $data, $prev );
					} else {
						add_site_meta( get_main_site_id(), self::SETTINGS_META_KEY, $data );
					}

					add_settings_error(
						self::PAGE_SLUG,
						'settings_updated',
						__( 'GoodBids settings saved.', 'goodbids' ),
						'updated'
					);
				}
			}
		);
	}
}
