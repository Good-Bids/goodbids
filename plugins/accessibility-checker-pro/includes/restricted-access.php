<?php
/**
 * Accessibility Checker pluign file.
 *
 * @package Accessibility_Checker
 */

/**
 * Restricted Site Access - override
 *
 * URL: https://wordpress.org/plugins/restricted-site-access/
 *
 * @param bool $is_restricted whether is restricted.
 * @return bool
 */
function edac_rsa_feed_override( $is_restricted ) {

	if ( get_transient( 'edac_public_draft' ) ) {
		$is_restricted = false;
	}

	return $is_restricted;
}
add_filter( 'restricted_site_access_is_restricted', 'edac_rsa_feed_override' );
