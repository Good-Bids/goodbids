<?php
/**
 * Admin Functionality
 *
 * @package GoodBids
 * @since 1.0.0
 */

namespace GoodBids\Admin;

/**
 * Admin Main Class
 */
class Admin {

	/**
	 * @since 1.0.0
	 * @var Assets
	 */
	public Assets $assets;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->assets = new Assets();
	}

	/**
	 * Render an Admin Setting Field
	 *
	 * @since 1.0.0
	 *
	 * @param string $key
	 * @param array  $field
	 * @param string $prefix
	 * @param array  $data
	 *
	 * @return void
	 */
	public function render_field( string $key, array $field, string $prefix = '', array $data = [] ) : void {
		$view_file   = 'unsupported';
		$required    = ! empty( $field['required'] ) && true === $field['required'];
		$placeholder = $field['placeholder'] ?? '';
		$field_id    = $prefix ? $prefix . '-' . $key : $key;
		$value       = ! empty( $data[ $key ] ) ? $data[ $key ] : '';

		if ( ! empty( $field['default'] ) && ! $value ) {
			$value = $field['default'];
		}

		if ( in_array( $field['type'], [ 'text', 'url', 'email', 'tel', 'password', 'number' ], true ) ) {
			$view_file = 'text';
		} elseif ( 'select' === $field['type'] ) {
			$view_file = 'select';
		}

		require GOODBIDS_PLUGIN_PATH . 'views/admin/fields/' . $view_file . '.php';
	}
}
