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
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Initialize Submodules.
		new Assets();
	}

	/**
	 * Add an Admin Menu Separator
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $position
	 *
	 * @return void
	 */
	public function add_menu_separator( mixed $position ): void {
		$separator = '<span class="wp-menu-separator">&mdash;</span>';

		if ( is_string( $position ) ) {
			add_submenu_page(
				$position,
				__( 'Separator', 'goodbids' ),
				$separator,
				'read',
				'',
				'__return_empty_string',
			);
		} elseif ( is_numeric( $position ) ) {
			add_menu_page(
				__( 'Separator', 'goodbids' ),
				$separator,
				'read',
				'',
				'__return_empty_string',
				'',
				$position
			);
		}
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
		$required    = ! empty( $field['required'] ) && true === $field['required'];
		$placeholder = $field['placeholder'] ?? '';
		$field_id    = $prefix ? $prefix . '-' . $key : $key;
		$value       = $data[ $key ] ?? '';

		if ( ! empty( $field['default'] ) && ! $value && '0' !== $value ) {
			$value = $field['default'];
		}

		if ( in_array( $field['type'], [ 'text', 'url', 'email', 'tel', 'password', 'number' ], true ) ) {
			$view_file = 'text';
		} else {
			$view_file = $field['type'];
		}

		if ( ! file_exists( GOODBIDS_PLUGIN_PATH . 'views/admin/fields/' . $view_file . '.php' ) ) {
			$view_file = 'unsupported';
		}

		goodbids()->load_view(
			'admin/fields/' . $view_file . '.php',
			compact( 'key', 'field', 'prefix', 'data', 'required', 'placeholder', 'field_id', 'value', 'wrap' )
		);
	}
}
