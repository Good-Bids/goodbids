<?php
/**
 * Accessibility Checker pluign file.
 *
 * @package Accessibility_Checker
 */

/**
 * Plugin Deactivation
 *
 * @return void
 */
function edacp_pro_deactivation() {

	remove_filter( 'edac_filter_post_types', 'edacp_post_types' );
	$post_types_option = get_option( 'edac_post_types' );
	$post_types        = function_exists( 'edac_post_types' ) ? edac_post_types() : array();
	if ( is_array( $post_types ) && is_array( $post_types_option ) ) {
		foreach ( $post_types as $key => $post_type ) {
			if ( ! in_array( $post_type, $post_types_option, true ) ) {
				unset( $post_types[ $key ] );
			}
		}
		update_option( 'edac_post_types', $post_types );
	}
}
