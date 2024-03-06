<?php
/**
 * WooCommerce Taxes Functionality
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins\WooCommerce;

use GoodBids\Utilities\Log;

/**
 * Class for Tax Methods
 *
 * @since 1.0.0
 */
class Taxes {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const TAXES_IMPORTED_OPTION = 'goodbids_taxes_imported_';

	/**
	 * Use NY as the default state for tax rates.
	 * This allows the code to be easily updated to support more states.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	const TAX_RATE_STATE = 'ny';

	/**
	 * Initialize Taxes
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Import the State Tax Rates CSV file.
		$this->import_state_tax_rates_csv();
	}

	/**
	 * Import Latest State Tax Rates
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function import_state_tax_rates_csv(): void {
		add_action(
			'admin_init',
			function () {
				if ( is_main_site() ) {
					return;
				}

				$state = self::TAX_RATE_STATE;

				if ( ! $this->load_wc_tax() ) {
					Log::error( 'Could not locate WC Tax class.' );
					return;
				}

				// First, check to see if we already have tax rates imported.
				$current_rates = \WC_Tax::get_rates_for_tax_class( '' );
				$last_import   = get_option( self::TAXES_IMPORTED_OPTION . $state, false );
				$filename      = goodbids()->get_config( 'taxes.' . $state . '.latest' );

				// Looks like the rates have already been imported (and it wasn't by us).
				if ( ! $last_import && $current_rates ) {
					return;
				}

				// Don't re-import the same file.
				if ( $last_import && $last_import === $filename ) {
					return;
				}

				if ( ! $this->load_tax_importer() ) {
					Log::error( 'Could not locate WC Tax Rate Importer class.' );
					return;
				}

				$ny_taxes_path = GOODBIDS_PLUGIN_PATH . 'data/taxes/' . $filename;

				if ( ! file_exists( $ny_taxes_path ) ) {
					Log::error( 'Could not locate the ' . strtoupper( $state ) . ' Tax Rates file.' );
					return;
				}

				// Clear all the current rates for the state.
				if ( $current_rates && ! $this->delete_tax_rates( $state ) ) {
					Log::error( 'Could not delete existing tax rates for ' . strtoupper( $state ) . '.' );
					return;
				}

				// Catch the response.
				ob_start();
				( new \WC_Tax_Rate_Importer() )->import( $ny_taxes_path );
				$output = ob_get_clean();

				if ( ! $output ) {
					return;
				}

				goodbids()->utilities->display_admin_custom( $output );
			}
		);
	}

	/**
	 * Load the WC_Tax class.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function load_wc_tax(): bool {
		if ( ! class_exists( 'WC_Tax' ) ) {
			$wc_tax_path = dirname( WC_PLUGIN_FILE ) . '/includes/class-wc-tax.php';

			if ( ! file_exists( $wc_tax_path ) ) {
				return false;
			}

			require_once $wc_tax_path;
		}

		return true;
	}

	/**
	 * Load the WC_Tax_Rate_Importer class.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function load_tax_importer(): bool {
		if ( ! class_exists( 'WP_Importer' ) ) {
			$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';

			if ( ! file_exists( $class_wp_importer ) ) {
				return false;
			}

			require $class_wp_importer;
		}

		if ( ! class_exists( 'WC_Tax_Rate_Importer' ) ) {
			$importer_path = dirname( WC_PLUGIN_FILE ) . '/includes/admin/importers/class-wc-tax-rate-importer.php';

			if ( ! file_exists( $importer_path ) ) {
				return false;
			}

			require_once $importer_path;
		}

		return true;
	}

	/**
	 * Delete existing tax rates for a specific state.
	 *
	 * @since 1.0.0
	 *
	 * @param string $state
	 *
	 * @return bool
	 */
	private function delete_tax_rates( string $state ): bool {
		global $wpdb;

		$delete_locations = $wpdb->query(
			$wpdb->prepare(
				"DELETE locations FROM {$wpdb->prefix}woocommerce_tax_rate_locations locations
				LEFT JOIN {$wpdb->prefix}woocommerce_tax_rates rates ON rates.tax_rate_id = locations.tax_rate_id
				WHERE rates.tax_rate_state = %s;",
				strtoupper( $state )
			)
		);

		if ( false === $delete_locations ) {
			return false;
		}

		$delete_rates = $wpdb->delete(
			$wpdb->prefix . 'woocommerce_tax_rates',
			[ 'tax_rate_state' => strtoupper( $state ) ],
			[ '%s' ]
		);

		if ( false === $delete_rates ) {
			return false;
		}

		return true;
	}
}
