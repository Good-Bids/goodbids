<?php
/**
 * miniOrange OAuth SSO plugin settings
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Plugins;

use GoodBids\Utilities\Log;

/**
 * This class handles SSO settings.
 *
 * @since 1.0.0
 */
class MiniOrange {

	/**
	 * @since 1.0.0
	 * @var string
	 */
	private string $slug = 'miniorange-oauth-oidc-single-sign-on/mo_oauth_settings.php';

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		if ( ! goodbids()->is_plugin_active( $this->slug ) ) {
			return;
		}

		// Enable SSO for Nonprofit on Verification.
		$this->enable_sso_on_verification();
	}

	/**
	 * Enable SSO for Nonprofit on Verification.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	private function enable_sso_on_verification(): void {
		add_action(
			'goodbids_nonprofit_verified',
			function ( int $site_id ) {
				if ( $site_id === get_main_site_id() ) {
					return;
				}

				/*
				 Sample Data (array of Site IDs):
				array(16) {
				  [0]=>
				  string(2) "40"
				  [1]=>
				  string(2) "44"
				  [2]=>
				  string(2) "30"
				  ...
				}
				*/

				// Table: wp_sitemeta
				// Site ID: 1 (main site)

				// Option Name: mo_oauth_c3Vic2l0ZXNzZWxlY3RlZA
				// Sample Encrypted Value: 1XCthJdfi6GVjHyJmJRnnZyCrHahVZuhg5ZynIeQZ6WcepuHqVWVj5OjcpOHlWednIWbgJdloY+NjIOch5BnpKxwpXapbIuZg5+AicI=

				// Source: plugins/miniorange-oauth-oidc-single-sign-on/classes/Premium/Multisite/class-multisitesettings.php
				// Method: save_multisite_settings
				// Encoded Option Name: Look for $Yh->mo_oauth_client_update_option() in the method

				// Decrypt: json_decode( ( new MOUtils() )->mooauthdecrypt( $value ) )
				// Encrypt: $Yh->mooauthencrypt( json_encode( $value ) )

				// Class: MOUtils
				// Path: plugins/miniorange-oauth-oidc-single-sign-on/classes/common/class-moutils.php
				// Access: global $Yh;

				global $Yh;

				$no_of_sites_key = 'noOfSubSites';
				$no_of_sites     = intval( $Yh->mo_oauth_client_get_option( $no_of_sites_key ) );

				$sites_key = 'mo_oauth_c3Vic2l0ZXNzZWxlY3RlZA';
				$sites_val = $Yh->mo_oauth_client_get_option( $sites_key );
				$sites     = json_decode( $Yh->mooauthdecrypt( $sites_val ) );

				if ( ! $sites || ! is_array( $sites ) ) {
					Log::error( 'Unable to decode the SSO settings.', compact( 'sites_val', 'sites' ) );
					return;
				}

				// Cast to string.
				$site_id = (string) $site_id;

				if ( in_array( $site_id, $sites, true ) ) {
					return;
				}

				if ( count( $sites ) + 1 > $no_of_sites ) {
					Log::warning( 'You have reached the limit of sub-sites allowed for SSO.' );
					return;
				}

				$sites[] = $site_id;
				$updated = $Yh->mooauthencrypt( json_encode( $sites ) ); // phpcs:ignore

				$Yh->mo_oauth_client_update_option( $sites_key, $updated );

				// :crossed_fingers:
			}
		);
	}
}
