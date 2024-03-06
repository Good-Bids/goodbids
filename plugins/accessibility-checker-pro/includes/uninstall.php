<?php
/**
 * Accessibility Checker pluign file.
 *
 * @package Accessibility_Checker
 */

/**
 * Plugin Uninstall
 *
 * @return void
 */
function edacp_pro_uninstall() {

	// check if the delte data option is checked. If not, don't delete data.
	$delete_data = (bool) get_option( 'edac_delete_data' );
	if ( true === $delete_data ) {
		global $wpdb;

		// drop logs database table.
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Using direct query for table drop in uninstall script, caching not required for one time operation.
		$wpdb->query(
			$wpdb->prepare(
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.SchemaChange
				'DROP TABLE IF EXISTS %i',
				$wpdb->prefix . 'accessibility_checker_logs'
			)
		);

		// drop ignores database table.
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Using direct query for table drop in uninstall script, caching not required for one time operation.
		$wpdb->query(
			$wpdb->prepare(
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.SchemaChange
				'DROP TABLE IF EXISTS %i',
				$wpdb->prefix . 'accessibility_checker_global_ignores'
			)
		);

		// delete options.
		$options = array(
			'edacp_ignore_user_roles',
			'edacp_simplified_summary_heading',
			'edacp_background_scan_schedule',
			'edacp_db_version',
			'edacp_global_ignores',
			'edacp_license_error',
			'edacp_license_key',
			'edacp_license_status',
			'edacp_license_url',
			'edacp_authorization_password',
			'edacp_authorization_username',
		);
		if ( $options ) {
			foreach ( $options as $option ) {
				delete_option( $option );
				delete_site_option( $option );
			}
		}
	}
}
