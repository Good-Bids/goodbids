<?php
/**
 * Accessibility Checker plugin file.
 *
 * @package Accessibility_Checker
 */

/**
 * Enqueue Admin Styles
 *
 * @return void
 */
function edacp_pro_admin_enqueue_styles() {
	wp_enqueue_style( 'accessibility-checker-pro', plugin_dir_url( __DIR__ ) . 'build/css/admin.css', array(), EDACP_VERSION, 'all' );
}

/**
 * Enqueue Admin Scripts
 *
 * @return void
 */
function edacp_pro_admin_enqueue_scripts() {

	// Enqueue the admin js.
	wp_enqueue_script( 'accessibility-checker-pro', plugin_dir_url( __DIR__ ) . 'build/admin.bundle.js', array( 'jquery' ), EDACP_VERSION, false );

	if ( 'accessibility-checker_page_accessibility_checker_settings' === get_current_screen()->id
		&& isset( $_GET['tab'] ) && 'scan' === $_GET['tab'] //phpcs:ignore WordPress.Security.NonceVerification.Recommended
	) {
		$mode = 'full-site-scan';

	} else {

		global $pagenow;

		if ( 'post.php' !== $pagenow ) {
			return;
		}

		$mode = 'editor-scan';

		// Don't load on customizer pages or if the user is not able to edit this page.
		global $post;
		$post_id = is_object( $post ) ? $post->ID : null;

		if ( null === $post_id ) {
			return;
		}

		if ( is_customize_preview() || ! ( $post_id && current_user_can( 'edit_post', $post_id ) ) ) {
			return;
		}
	}

	if ( WP_DEBUG || strpos( EDACP_VERSION, '-beta' ) !== false ) {
		$debug = true;
	} else {
		$debug = false;
	}

	$debug = false;

	// Enqueue the full site scan js.
	wp_enqueue_script( 'edacp-full-site-scan-app', plugin_dir_url( __DIR__ ) . 'build/fullSiteScanApp.bundle.js', null, EDACP_VERSION, false );
	wp_localize_script(
		'edacp-full-site-scan-app',
		'edacpFullSiteScanApp',
		array(
			'mode'        => $mode,
			'edacApiUrl'  => esc_url_raw( rest_url() . 'accessibility-checker/v1' ),
			'edacpApiUrl' => esc_url_raw( rest_url() . 'accessibility-checker-pro/v1' ),
			'edacBaseUrl' => plugin_dir_url( EDAC_PLUGIN_FILE ),
			'restNonce'   => wp_create_nonce( 'wp_rest' ),
			'authOk'      => false === get_option( 'edac_password_protected', false ),
			'debug'       => $debug,
		)
	);

	// This is a patch to fix misnamed variable found in Free 1.7.0. 
	// phpcs:ignore Generic.Commenting.Todo.TaskFound
	// TODO: This patch may be removed once Free 1.8 is released.
	wp_localize_script(
		'edacp-full-site-scan-app',
		'edacp_full_site_scan_app',
		array(
			'mode'        => $mode,
			'edacApiUrl'  => esc_url_raw( rest_url() . 'accessibility-checker/v1' ),
			'edacpApiUrl' => esc_url_raw( rest_url() . 'accessibility-checker-pro/v1' ),
			'edacBaseUrl' => plugin_dir_url( EDAC_PLUGIN_FILE ),
			'restNonce'   => wp_create_nonce( 'wp_rest' ),
			'authOk'      => false === get_option( 'edac_password_protected', false ),
			'debug'       => $debug,
		)
	);
}
