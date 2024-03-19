<?php
/**
 * Front-end Forms Functionality
 *
 * @since 1.0.0
 *
 * @package GoodBids
 */

namespace GoodBids\Frontend;

class Forms {

	/**
	 * Render a form Field
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
		if ( ! empty( $field['hidden'] ) ) {
			return;
		}

		$required    = ! empty( $field['required'] );
		$placeholder = $field['placeholder'] ?? '';
		$field_id    = $prefix ? $prefix . '-' . $key : $key;
		$value       = $data[ $key ] ?? '';

		if ( ! empty( $field['default'] ) && ! $value && '0' !== $value ) {
			$value = $field['default'];
		}

		$name = $key;

		if ( $prefix ) {
			$name = $prefix . '[' . $key . ']';
		}

		if ( 'callback' === $field['type'] && ! empty( $field['callback'] ) && is_callable( $field['callback'] ) ) {
			/**
			 * Callback Field
			 *
			 * @since 1.0.0
			 *
			 * @param array  $field
			 * @param string $key
			 * @param string $prefix
			 * @param string $name
			 * @param array  $data
			 * @param bool   $wrap
			 */
			call_user_func( $field['callback'], $field, $key, $prefix, $name, $data, $wrap );
			return;
		}

		if ( in_array( $field['type'], [ 'text', 'url', 'email', 'tel', 'password', 'number' ], true ) ) {
			$view_file = 'text';
		} else {
			$view_file = $field['type'];
		}

		if ( ! file_exists( GOODBIDS_PLUGIN_PATH . 'views/forms/' . $view_file . '.php' ) ) {
			$view_file = 'unsupported';
		}

		goodbids()->load_view(
			'forms/' . $view_file . '.php',
			compact( 'key', 'field', 'prefix', 'name', 'data', 'required', 'placeholder', 'field_id', 'value', 'wrap' )
		);
	}
}
