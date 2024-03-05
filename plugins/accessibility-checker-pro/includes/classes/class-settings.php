<?php
/**
 * Class file for scan settings
 *
 * @package Accessibility_Checker
 */

namespace EDACP;

use EDACP\Helpers;

/**
 * Class that handles scan settings
 */
class Settings {

	/**
	 * Gets a list of post types that are scannable.
	 *
	 * @return array List of post types.
	 */
	public static function get_scannable_post_types() {

		$post_types = Helpers::get_option_as_array( 'edac_post_types' );

		// remove duplicates.
		$post_types = array_unique( $post_types );

		// validate post types.
		foreach ( $post_types as $key => $post_type ) {
			if ( ! post_type_exists( $post_type ) ) {
				unset( $post_types[ $key ] );
			}
		}

		return $post_types;
	}
}
