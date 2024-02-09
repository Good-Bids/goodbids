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
 *
 * @since 1.0.0
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
		// Initialize Submodules.
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
	 * @param bool   $wrap
	 *
	 * @return void
	 */
	public function render_field( string $key, array $field, string $prefix = '', array $data = [], bool $wrap = true ) : void {
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

		goodbids()->load_view(
			'admin/fields/' . $view_file . '.php',
			compact( 'key', 'field', 'prefix', 'data', 'required', 'placeholder', 'field_id', 'value', 'wrap' )
		);
	}
}
