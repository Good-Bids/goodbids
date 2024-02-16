<?php
/**
 * GoodBids Network Settings
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Network;

use GoodBids\Utilities\Log;

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

		// Override the Config file values.
		$this->override_config();
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
				'default'     => goodbids()->get_config( 'auctioneer.environment', false ),
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
			'logging' => [
				'label'    => __( 'Logging', 'goodbids' ),
				'type'     => 'toggle',
				'default'  => goodbids()->get_config( 'advanced.logging', false ),
				'section'  => 'advanced',
			],
			'debug-mode' => [
				'label'    => __( 'Debug Mode', 'goodbids' ),
				'type'     => 'toggle',
				'default'  => goodbids()->get_config( 'advanced.debug-mode', false ),
				'section'  => 'advanced',
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

				add_settings_section(
					'advanced',
					__( 'Advanced', 'goodbids' ),
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
	 * Checks if a config value is is overridden.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key
	 * @param mixed $config_value
	 *
	 * @return bool
	 */
	private function is_overridden( string $key, mixed $config_value = null ): bool {
		$stored = $this->get_setting( $key );

		if ( is_null( $config_value ) ) {
			$config_value = goodbids()->get_config( $key, false );
		}

		$stored_has_value = $stored || '0' === $stored;
		$stored_is_bool   = $stored_has_value && in_array( $stored, [ '1', '0' ], true );

		if ( $stored_is_bool ) {
			$stored = boolval( $stored );
		}

		return $stored_has_value && $stored !== $config_value;
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
		if ( $this->is_overridden( $key ) ) {
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
	 * @param bool $use_post
	 *
	 * @return void
	 */
	private function load_settings( bool $use_post = false ): void {
		$settings = get_site_meta( get_main_site_id(), self::SETTINGS_META_KEY, true );

		if ( ! is_array( $settings ) ) {
			$settings = [];
		}

		$defaults = [];

		foreach ( array_keys( $settings ) as $key ) {
			$defaults[ $key ] = goodbids()->get_config( $key, false );
		}

		$this->data = array_merge( $defaults, $settings );

		if ( ! $use_post || empty( $_POST ) ) { // phpcs:ignore
			return;
		}

		if ( ! empty( $_POST[ self::SETTINGS_META_KEY ] ) && is_array( $_POST[ self::SETTINGS_META_KEY ] ) ) { // phpcs:ignore
			foreach ( $_POST[ self::SETTINGS_META_KEY ] as $key => $value ) { // phpcs:ignore
				$this->data[ $key ] = sanitize_text_field( $value );
			}
		}
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
		if ( empty( $this->data ) ) {
			$this->load_settings( true );
		}

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

				$nonce = ! empty( $_POST[ self::SETTINGS_NONCE ] ) ? sanitize_text_field( $_POST[ self::SETTINGS_NONCE ] ) : null; // phpcs:ignore

				if ( $nonce && ! wp_verify_nonce( $nonce, self::PAGE_SLUG ) ) {
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

	/**
	 * Override Config values with Network Admin Settings.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function override_config(): void {
		add_filter(
			'goodbids_config_var',
			function ( mixed $value, string $config_key ): mixed {
				$setting = $this->get_setting( $config_key );

				if ( ! $this->is_overridden( $config_key, $value ) ) {
					return $value;
				}

				Log::info( 'Network Admin Setting overriding Config for: ' . $config_key, compact( 'value', 'setting' ) );

				return $setting;
			},
			10,
			2
		);
	}
}
