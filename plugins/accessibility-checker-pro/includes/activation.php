<?php
/**
 * Accessibility Checker pluign file.
 *
 * @package Accessibility_Checker
 */

/**
 * Plugin Activation
 *
 * @return void
 */
function edacp_pro_activation() {

	// set options.
	add_option( 'edacp_ignore_user_roles', array( 'administrator' ) );
}
