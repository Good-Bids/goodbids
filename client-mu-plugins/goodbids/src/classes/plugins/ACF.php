<?php
/**
 * ACF Functionality
 *
 * @package GoodBids
 */

namespace GoodBids\Plugins;

/**
 * Class for Advanced Custom Fields Pro
 *
 * @since 1.0.0
 */
class ACF {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	private string $slug = 'advanced-custom-fields-pro/acf.php';

	/**
	 * Initialize ACF Functionality
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		if ( ! goodbids()->is_plugin_active( $this->slug ) ) {
			return;
		}

		$this->disable_admin();
		$this->discourage_the_field_usage();
		$this->modify_save_directory();
		$this->disable_database_storage();
	}

	/**
	 * Disable ACF Admin per WP VIP Documentation
	 *
	 * @link https://docs.wpvip.com/technical-references/plugin-incompatibilities/#acf
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function disable_admin() : void {
		if ( defined( 'VIP_GO_APP_ENVIRONMENT' ) && 'local' === VIP_GO_APP_ENVIRONMENT ) {
			return;
		}

		add_filter( 'acf/settings/show_admin', '__return_false' );
	}

	/**
	 * Save ACF JSON to the plugin directory, but only when developing locally.
	 * We don't want to break this feature if the non-profit is using ACF.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function modify_save_directory() : void {
		add_filter(
			'acf/settings/save_json',
			function( $path ) {
				if ( defined( 'VIP_GO_APP_ENVIRONMENT' ) && 'local' === VIP_GO_APP_ENVIRONMENT ) {
					return GOODBIDS_PLUGIN_PATH;
				}

				return $path;
			}
		);

		add_filter(
			'acf/settings/load_json',
			function( array $paths ) {
				$paths[] = GOODBIDS_PLUGIN_PATH;
				return $paths;
			}
		);
	}

	/**
	 * Disable database setting storage
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function disable_database_storage() : void {
		add_filter(
			'acf/prepare_field_group_for_export',
			function( array $group ) : array {
				$group['private'] = true;
				return $group;
			}
		);
	}

	/**
	 * Throw a warning if the_field is used during local development.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function discourage_the_field_usage() : void {
		if ( ! defined( 'VIP_GO_APP_ENVIRONMENT' ) || 'local' !== VIP_GO_APP_ENVIRONMENT ) {
			return;
		}

		add_filter(
			'acf/pre_load_value',
			function( $preload ) {
				if ( $this->called_from_the_field() ) {
					_doing_it_wrong( 'the_field', 'the_field() should not be used. Use get_field() instead.', '1.0.0' );
				}

				return $preload;
			}
		);
	}

	/**
	 * Check if the_field exists in the stack trace
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function called_from_the_field() : bool {
		// Create an exception
		$ex = new \Exception();

		// Call getTrace function
		$trace = $ex->getTrace();

		foreach ( $trace as $item ) {
			if ( ! empty( $item['function'] ) && 'the_field' === $item['function'] ) {
				return true;
			}
		}

		return false;
	}
}
