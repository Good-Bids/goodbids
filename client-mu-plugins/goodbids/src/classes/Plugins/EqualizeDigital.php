<?php
/**
 * Equalize Digital Accessibility plugin settings
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins;

/**
 * This class handles setting accessibility settings.
 *
 * @since 1.0.0
 */
class EqualizeDigital {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	private string $slug = 'accessibility-checker-pro';

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		if ( ! goodbids()->is_plugin_active( $this->slug ) ) {
			return;
		}

		// Set License key.
		$this->set_license_key();

		// Adjust Post Types
		$this->set_default_settings();
	}

	/**
	 * Set which Post Types should use Equalize Digital Accessibility
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	private function set_default_settings(): void {
		$add_auction_cpt = function ( array|bool $post_types ): array {
			if ( ! is_array( $post_types ) ) {
				$post_types = [];
			}

			if ( ! in_array( goodbids()->auctions->get_post_type(), $post_types, true ) ) {
				$post_types[] = goodbids()->auctions->get_post_type();
			}
			return $post_types;
		};

		add_filter(
			'edac_filter_post_types',
			$add_auction_cpt
		);

		add_filter(
			'option_edac_post_types',
			$add_auction_cpt
		);

		add_filter(
			'default_option_edac_post_types',
			$add_auction_cpt
		);
	}

	/**
	 * Set the Equalize Digital license key
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	private function set_license_key(): void {
		add_filter(
			'default_option_edacp_license_key',
			function ( string $value ): string {
				if ( $value || $this->is_license_page() ) {
					return $value;
				}

				$license_key = $this->get_license_key();

				if ( ! $license_key ) {
					return $value;
				}

				return $license_key;
			}
		);

		add_filter(
			'option_edacp_license_key',
			function ( string $value ): string {
				$license_key = $this->get_license_key();

				if ( ! $this->is_license_page() ) {
					if ( $value ) {
						return $value;
					}

					return $license_key;
				}

				if ( $value === $license_key ) {
					return '';
				}

				return $value;
			}
		);

		add_action(
			'admin_init',
			function (): void {
				if ( $this->is_license_page() ) {
					return;
				}

				if ( empty( $_POST['edd_license_activate'] ) || empty( $_POST['edd_license_deactivate'] ) ) { // phpcs:ignore
					return;
				}

				$_POST['edacp_license_key'] = $this->get_license_key();
			},
			2
		);
	}

	/**
	 * Get the license key
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	private function get_license_key(): string {
		$key = vip_get_env_var( 'GOODBIDS_EDACP_LICENSE_KEY' );
		if ( ! $key ) {
			return '';
		}

		return $key;
	}

	/**
	 * Check if we're on the license page.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function is_license_page(): bool {
		global $pagenow;

		if ( 'admin.php' !== $pagenow || empty( $_GET['page'] ) ) { // phpcs:ignore
			return false;
		}

		$page = sanitize_text_field( wp_unslash( $_GET['page'] ) ); // phpcs:ignore
		$tab  = ! empty( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : ''; // phpcs:ignore

		if ( 'accessibility_checker_settings' !== $page || 'license' !== $tab ) {
			return false;
		}

		return true;
	}
}
