<?php
/**
 * Class file for updates
 *
 * @package Accessibility_Checker
 */

namespace EDACP;

// phpcs:disable Squiz.PHP.CommentedOutCode.Found

/**
 * Class that handles updates
 */
class Updates {

	/**
	 * Updade was run and completed.
	 *
	 * @var boolean
	 */
	public $updated = false;

	/**
	 * Runs updates that may need to happen.
	 *
	 * @return void
	 */
	public function run() {

		// Run updates that may need to happen on all version.
		$this->all_versions();

		// Note - all update function must return TRUE on success, or FALSE on failure.
		$updates = array(
			'1.5.2' => 'update_1',
		);

		array_walk(
			$updates,
			function ( $routine, $version ) {
				static $fail = false;
				if ( $fail ) {
					return;
				}

				$retval = $this->run_update_routine( $routine, $version );

				if ( ! $retval ) {
					$fail = true;
					return;
				}
			}
		);
	}

	/**
	 * Runs the update routine.
	 *
	 * @param string $routine                The method to call.
	 * @param string $update_version        The new version.
	 *
	 * @return mixed boolean | WP_Error
	 */
	public function run_update_routine( $routine, $update_version ) {
	
		$option_name = 'edacp_version';

		$last_completed_update = get_option( $option_name, 0 );
	
		if ( version_compare( $last_completed_update, $update_version, '<' ) ) {

			$results = $this->$routine();

			if ( $results ) {

				update_option( $option_name, $update_version );

				$this->updated = true;

			} else {

				return false;
			
			}       
		}

		return true;
	}




	/**
	 * Code that should run on all versions
	 *
	 * @return void
	 */
	private function all_versions() {
		// Note, code here runs on all versions.
		// Flags (ie. see existing_action below ) MUST be used
		// to make sure code is not run multiple times.

		if ( class_exists( 'ActionScheduler' ) ) {

			// Add the ActionScheduler hook that calculates scans_stats.
			$existing_action = as_next_scheduled_action( 'edacp_scans_stats_runner_hook' );
			if ( ! $existing_action ) {
				as_schedule_recurring_action( time(), 60, 'edacp_scans_stats_runner_hook', array(), 'edacp' );
			}
		}

		$this->migrate_legacy_authorization_options();
		$this->maybe_update_edacah();
	}

	/**
	 * Migrate legacy edac_authorization_ options to edacp_authorization_.
	 *
	 * @return void
	 */
	private function migrate_legacy_authorization_options() {

		$is_migrated = get_option( 'edacp_migrated_legacy_authorization', false );

		if ( ! $is_migrated ) {

			// Migrate legacy username.
			$edac_username  = get_option( 'edac_authorization_username', false );
			$edacp_username = get_option( 'edacp_authorization_username', false );

			if ( $edac_username ) {

				if ( ! $edacp_username ) { // edac_ exists and edacp_ is empty, so migrate.

					update_option( 'edacp_authorization_username', $edac_username );

					// Make sure the migration worked.
					$edacp_username = get_option( 'edacp_authorization_username', false );

					if ( $edac_username === $edacp_username ) { // migration worked. Cleanup.
						delete_option( 'edac_authorization_username' );
					}
				} else { // edac_ exists and edacp_ is not empty. Cleanup.
					delete_option( 'edac_authorization_username' );
				}
			}

			// Migrate legacy password.
			$edac_password  = get_option( 'edac_authorization_password', false );
			$edacp_password = get_option( 'edacp_authorization_password', false );

			if ( $edac_password ) {

				if ( ! $edacp_password ) { // edac_ exists and edacp_ is empty, so migrate.

					update_option( 'edacp_authorization_password', $edac_password );

					// Make sure the migration worked.
					$edacp_password = get_option( 'edacp_authorization_password', false );

					if ( $edac_password === $edacp_password ) { // migration worked. Cleanup.
						delete_option( 'edac_authorization_password' );
					}
				} else { // edac_ exists and edacp_ is not empty. Cleanup.
					delete_option( 'edac_authorization_password' );
				}
			}

			// If both migrations were successful, set the migrated flag.
			if ( ! get_option( 'edac_authorization_username', false )
				&& ! get_option( 'edac_authorization_password', false )
			) {
				update_option( 'edacp_migrated_legacy_authorization', true );
			}
		}
	}

	/**
	 * Update edacah v1.0.0 or v1.0.1
	 *
	 * @return void
	 */
	private function maybe_update_edacah() {
		
		if ( ! defined( '\EDACAH_VERSION' ) ) {
			return;
		}

		if ( ! class_exists( '\EDACAP\AddOns\AuditHistory\Helpers\EDD_Plugin_Updater' ) ) {
			return;
		}

		if ( '1.0.0' === \EDACAH_VERSION || '1.0.1' === \EDACAH_VERSION ) {


			// retrieve our license key from the DB.
			$license_key = trim( get_option( 'edacp_license_key' ) );
		
			// setup the updater.
			new \EDACAP\AddOns\AuditHistory\Helpers\EDD_Plugin_Updater(
				'https://my.equalizedigital.com',
				EDACAH_PLUGIN_FILE,
				array(
					'version' => \EDACAH_VERSION,        // Current version number.
					'license' => $license_key,           // License key (used get_option above to retrieve from DB).
					'item_id' => 11970,                  // ID of the product.
					'author'  => 'Equalize Digital',     // Author of this plugin.
					'beta'    => false,
				)
			);

		
		}
	}



	/**
	 * Handle update 1.
	 *
	 * @return boolean
	 */
	public static function update_1() {
		
		// We've made changes to Scans(), resetting to prevent possibly hung scans.
		$scans = new Scans();
		$scans->cancel_current_scan();

		return true;
	}
}
