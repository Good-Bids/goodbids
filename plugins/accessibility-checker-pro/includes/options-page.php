<?php
/**
 * Accessibility Checker pluign file.
 *
 * @package Accessibility_Checker
 */

/**
 * Add an options page under the Settings submenu
 */
function edacp_add_options_page() {

	if ( EDACP_KEY_VALID === false ) {
		return;
	}

	if ( function_exists( 'edac_user_can_ignore' ) && ! edac_user_can_ignore() ) {
		return;
	}

	$admin_page = add_submenu_page(
		'accessibility_checker',
		__( 'Open Issues', 'edacp' ),
		__( 'Open Issues', 'edacp' ),
		'read',
		'accessibility_checker_issues',
		'edacp_display_issues_page',
		1,
		'dashicons-universal-access-alt'
	);

	// screen options.
	add_action( "load-$admin_page", 'edacp_issues_add_option' );
	/**
	 * Issues screen optionss
	 *
	 * @return void
	 */
	function edacp_issues_add_option() {
		add_screen_option(
			'per_page',
			array(
				'label'   => __( 'Number of items per page:', 'edacp' ),
				'default' => 20,
				'option'  => 'edacp_issues_per_page',
			)
		);
	}

	$admin_page = add_submenu_page(
		'accessibility_checker',
		__( 'Ignore Log', 'edacp' ),
		__( 'Ignore Log', 'edacp' ),
		'read',
		'accessibility_checker_ignored',
		'edacp_display_ignored_page',
		2,
		'dashicons-universal-access-alt'
	);

	// screen options.
	add_action( "load-$admin_page", 'edacp_ignore_add_option' );
	/**
	 * Ignore screen options
	 *
	 * @return void
	 */
	function edacp_ignore_add_option() {
		add_screen_option(
			'per_page',
			array(
				'label'   => __( 'Number of items per page:', 'edacp' ),
				'default' => 20,
				'option'  => 'edacp_ignore_per_page',
			)
		);
	}

	// custom menu link to scan tab.
	global $submenu;
	$url                                = admin_url( 'admin.php?page=accessibility_checker_settings&tab=scan' );
	$submenu['accessibility_checker'][] = array( // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- future fix.
		__( 'Full Site Scan', 'edacp' ),
		'manage_options',
		$url,
	);
}

add_filter( 'set-screen-option', 'edacp_issues_set_option', 10, 3 );
/**
 * Issues set option
 *
 * @param string $status status of issue.
 * @param string $option option to set.
 * @param string $value value of option.
 * @return string
 */
function edacp_issues_set_option( $status, $option, $value ) {
	return ( 'edacp_issues_per_page' === $option )
		? $value
		: $status;
}

add_filter( 'set-screen-option', 'edacp_ignore_set_option', 10, 3 );
/**
 * Undocumented function
 *
 * @param string $status status of issue.
 * @param string $option option to set.
 * @param string $value value of option.
 * @return string
 */
function edacp_ignore_set_option( $status, $option, $value ) {
	return ( 'edacp_ignore_per_page' === $option )
		? $value
		: $status;
}

/**
 * Render the issues options page for plugin.
 *
 * @return void
 */
function edacp_display_issues_page() {
	include_once plugin_dir_path( __DIR__ ) . 'partials/issues-page.php';
}

/**
 * Render the ignored options page for plugin.
 *
 * @return void
 */
function edacp_display_ignored_page() {
	include_once plugin_dir_path( __DIR__ ) . 'partials/ignored-page.php';
}

/**
 * Register Setting.
 *
 * @return void
 */
function edacp_register_setting() {

	if ( EDACP_KEY_VALID === false ) {
		return;
	}

	// Add sections.
	add_settings_section(
		'edacp_scan',
		__( 'Scan Settings', 'edacp' ),
		'__return_empty_string',
		'edacp_scan_settings'
	);

	add_settings_field(
		'edacp_simplified_summary_heading',
		__( 'Simplified Summary Heading', 'edacp' ),
		'edacp_simplified_summary_heading_cb',
		'edac_settings',
		'edac_simplified_summary',
		array( 'label_for' => 'edacp_simplified_summary_heading' )
	);
	register_setting( 'edac_settings', 'edacp_simplified_summary_heading', 'sanitize_text_field' );

	add_settings_field(
		'edacp_ignore_user_roles',
		__( 'Ignore Permissions', 'edacp' ),
		'edacp_ignore_user_roles_cb',
		'edac_settings',
		'edac_general',
		array( 'label_for' => 'edacp_ignore_user_roles' )
	);
	register_setting( 'edac_settings', 'edacp_ignore_user_roles', 'edacp_sanitize_ignore_user_roles', array( 'administrator' ) );

	
	add_settings_field(
		'edacp_authorization',
		__( 'Password Protection Login', 'edacp' ),
		'edacp_authorization_cb',
		'edac_settings',
		'edac_general',
		array( 'label_for' => 'edacp_authorization' )
	);
	register_setting( 'edac_settings', 'edacp_authorization_password', 'sanitize_text_field' );
	register_setting( 'edac_settings', 'edacp_authorization_username', 'sanitize_text_field' );
}

/**
 * Render the input field for simplified summary heading.
 *
 * @return void
 */
function edacp_simplified_summary_heading_cb() {
	// phpcs:ignore Universal.Operators.DisallowShortTernary.Found -- ternary is more readable here.
	$simplified_summary_heading = get_option( 'edacp_simplified_summary_heading' ) ?: __( 'Simplified Summary', 'edacp' );
	?>
	<input
		type="text"
		name="edacp_simplified_summary_heading"
		id="edacp_simplified_summary_heading"
		value="<?php echo esc_attr( $simplified_summary_heading ); ?>"
	>
	<?php
}

/**
 * Get simplified Summary.
 *
 * @return string
 */
function edacp_simplified_summary_heading() {
	// phpcs:ignore Universal.Operators.DisallowShortTernary.Found -- ternary is more readable here.
	return get_option( 'edacp_simplified_summary_heading' ) ?: esc_html__( 'Simplified Summary', 'edacp' );
}

/**
 * Render the field for ignore user roles.
 *
 * @return void
 */
function edacp_ignore_user_roles_cb() {

	global $wp_roles;
	// phpcs:ignore Universal.Operators.DisallowShortTernary.Found -- ternary is more readable here.
	$selected_roles = get_option( 'edacp_ignore_user_roles' ) ?: array();
	$roles          = $wp_roles->roles;

	?>
	<fieldset>
		<?php if ( $roles ) : ?>
			<?php foreach ( $roles as $key => $role ) : ?>
				<label>
					<input
						type="checkbox"
						name="edacp_ignore_user_roles[]"
						value="<?php echo esc_attr( $key ); ?>"
						<?php checked( in_array( $key, $selected_roles, true ), 1 ); ?>
					>
					<?php echo esc_html( $role['name'] ); ?>
				</label>
				<br>
			<?php endforeach; ?>
		<?php endif; ?>
	</fieldset>
	<p class="edac-description">
		<?php esc_html_e( 'Choose which user roles have permission to ignore issues.', 'edacp' ); ?>
	</p>
	<?php
}

/**
 * Sanitize the ignore user roles value before being saved to database
 *
 * @param array $selected_roles user roles to sanitize.
 * @return array
 */
function edacp_sanitize_ignore_user_roles( $selected_roles ) {

	global $wp_roles;
	$roles = array_keys( $wp_roles->roles );

	if ( $selected_roles ) {
		foreach ( $selected_roles as $key => $selected_role ) {
			if ( ! in_array( $selected_role, (array) $roles, true ) ) {
				unset( $selected_roles[ $key ] );
			}
		}
	}

	return $selected_roles;
}

/**
 * Render the fields for site authorization
 *
 * @return void
 */
function edacp_authorization_cb() {
	$password = get_option( 'edacp_authorization_password' );
	$username = get_option( 'edacp_authorization_username' );

	?>
		<fieldset>
			<label for="edacp_authorization_username">
				<?php esc_html_e( 'Username', 'edacp' ); ?>
			</label>
			<input
				type="text"
				name="edacp_authorization_username"
				id="edacp_authorization_username"
				value="<?php echo esc_attr( $username ); ?>"
			>
			<label for="edacp_authorization_password">
				<?php esc_html_e( 'Password', 'edacp' ); ?>
			</label>
			<input
				type="password"
				name="edacp_authorization_password"
				id="edacp_authorization_password"
				value="<?php echo esc_attr( $password ); ?>"
			>

		</fieldset>
		<p class="edac-description">
			<?php esc_html_e( 'If your website is on a password protected URL such as a staging site, Accessibility Checker may need the username and password in order to scan your website.', 'edacp' ); ?>
		</p>
	<?php
}
