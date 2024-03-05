<?php
/**
 * Accessibility Checker pluign file.
 *
 * @package Accessibility_Checker
 */

/**
 * Custom Admin Columns
 *
 * @return mixed
 */
function edacp_custom_columns() {

	// call columns hooks by post type.
	$post_types = get_option( 'edac_post_types' );
	if ( $post_types ) {
		foreach ( $post_types as $post_type ) {
			add_filter( "manage_{$post_type}_posts_columns", 'edacp_set_custom_columns' );
			add_action( "manage_{$post_type}_posts_custom_column", 'edacp_display_custom_column', 10, 2 );
			add_filter( "manage_edit-{$post_type}_sortable_columns", 'edacp_sortable_columns', 10, 2 );
		}
	}

	/**
	 * Set Custom Columns
	 *
	 * @param array $columns columns array.
	 * @return array
	 */
	function edacp_set_custom_columns( $columns ) {

		if ( EDACP_KEY_VALID !== true ) {
			return $columns;
		}

		$extra_columns = array(
			'edacp_passed_column'   => array(
				'label' => __( 'Passed Accessibility Tests', 'edacp' ),
				'icon'  => 'edacp-column-icon-passed',
			),
			'edacp_errors_column'   => array(
				'label' => __( 'Accessibility Errors', 'edacp' ),
				'icon'  => 'edacp-column-icon-errors',
			),
			'edacp_contrast_column' => array(
				'label' => __( 'Color Contrast Errors', 'edacp' ),
				'icon'  => 'edacp-column-icon-contrast',
			),
			'edacp_warnings_column' => array(
				'label' => __( 'Accessibility Warnings', 'edacp' ),
				'icon'  => 'edacp-column-icon-warnings',
			),
			'edacp_ignored_column'  => array(
				'label' => __( 'Ignored Accessibility Issues', 'edacp' ),
				'icon'  => 'edacp-column-icon-ignored',
			),
		);

		foreach ( $extra_columns as $id => $data ) {
			$columns[ $id ] = '
			<span class="edacp-column-icon ' . $data['icon'] . '">
				<span class="edacp-column-icon-tooltip"></span>
				<span class="screen-reader-text">' . $data['label'] . '</span>
			</span>';
		}

		return $columns;
	}

	/**
	 * Custom Columns
	 *
	 * @param array $column array of columns.
	 * @param int   $post_id post identifier.
	 * @return void
	 */
	function edacp_display_custom_column( $column, $post_id ) {

		if ( EDACP_KEY_VALID === false ) {
			return;
		}

		switch ( $column ) {

			case 'edacp_passed_column':
				// Passed Tests: shows the percentage, I.e. 90% as plain text.
				$summary = get_post_meta( $post_id, '_edac_summary', true );
				echo ( $summary ) ? esc_html( $summary['passed_tests'] ) . '%' : '0%';
				break;

			case 'edacp_errors_column':
				// Errors: Number in a red or green circle (green if 0).
				$summary = get_post_meta( $post_id, '_edac_summary', true );
				echo ( $summary ) ? '<span class="edacp-column-count edacp-errors-column-count' . ( ( $summary['errors'] > 0 ) ? ' edacp-column-count-over' : '' ) . '">' . esc_html( $summary['errors'] ) . '</span>' : '<span class="edacp-column-count edacp-column-count-empty"></span>';
				break;

			case 'edacp_contrast_column':
				// Contrast Errors: Number in a red or green circle (green if 0).
				$summary = get_post_meta( $post_id, '_edac_summary', true );
				echo ( $summary ) ? '<span class="edacp-column-count edacp-contrast-column-count' . ( ( $summary['contrast_errors'] > 0 ) ? ' edacp-column-count-over' : '' ) . '">' . esc_html( $summary['contrast_errors'] ) . '</span>' : '<span class="edacp-column-count edacp-column-count-empty"></span>';
				break;

			case 'edacp_warnings_column':
				// Warnings: Number in a yellow or green circle (green if 0).
				$summary = get_post_meta( $post_id, '_edac_summary', true );
				echo ( $summary ) ? '<span class="edacp-column-count edacp-warnings-column-count' . ( ( $summary['warnings'] > 0 ) ? ' edacp-column-count-over' : '' ) . '">' . esc_html( $summary['warnings'] ) . '</span>' : '<span class="edacp-column-count edacp-column-count-empty"></span>';
				break;

			case 'edacp_ignored_column':
				// Ignored items: Number, I.e. 5, as plain text.
				$summary = get_post_meta( $post_id, '_edac_summary', true );
				echo ( $summary ) ? '<span class="edacp-ignored-column-count">' . esc_html( $summary['ignored'] ) . '</span>' : '';
				break;
		}
	}

	/**
	 * Set Columns as Sortable
	 *
	 * @param array $columns array of columns.
	 * @return array
	 */
	function edacp_sortable_columns( $columns ) {

		$columns['edacp_passed_column']   = 'edacp_passed_column';
		$columns['edacp_errors_column']   = 'edacp_errors_column';
		$columns['edacp_contrast_column'] = 'edacp_contrast_column';
		$columns['edacp_warnings_column'] = 'edacp_warnings_column';
		$columns['edacp_ignored_column']  = 'edacp_ignored_column';

		return $columns;
	}
}
