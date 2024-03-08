<?php
/**
 * Accessibility Checker Pluign.
 *
 * @package Accessibility_Checker_Pro
 * @link    https://my.equalizedigital.com
 * @since   1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       Accessibility Checker Pro
 * Plugin URI:        https://my.equalizedigital.com
 * Description:       This plugin adds pro features to the Accessibility Checker plugin, including full site scanning. You must have an active license key for this plugin to work.
 * Version:           1.6.0
 * Author:            Equalize Digital
 * Author URI:        https://equalizedigital.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       edacp
 * Domain Path:       /languages
 */

// phpcs:disable Generic.Commenting.Todo.TaskFound

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'EDACP_PLUGIN_FILE', __FILE__ );

/**
 * Currently plugin versions.
 */
define( 'EDACP_VERSION', '1.6.0' );
define( 'EDACP_DB_VERSION', '1.0.2' );

/**
 * Key Valid.
 */
if ( 'valid' === get_option( 'edacp_license_status' ) ) {
	define( 'EDACP_KEY_VALID', true );
} else {
	define( 'EDACP_KEY_VALID', false );
}

/**
 * Enable AC_DEBUG mode
 */
define( 'EDACP_PRO_DEBUG', false );

/**
 * Plugin Activation & Deactivation
 */
register_activation_hook( __FILE__, 'edacp_pro_activation' );
register_deactivation_hook( __FILE__, 'edacp_pro_deactivation' );
register_uninstall_hook( __FILE__, 'edacp_pro_uninstall' );

/**
 * Import Resources
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/activation.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/deactivation.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/uninstall.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/helper-functions.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/enqueue-scripts.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/license.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/options-page.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/custom-columns.php';
require_once plugin_dir_path( __FILE__ ) . '/vendor/woocommerce/action-scheduler/action-scheduler.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/restricted-access.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/classes/class-rest-api.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/classes/class-scans.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/classes/class-settings.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/classes/class-updates.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/classes/class-helpers.php';


require_once __DIR__ . '/vendor/autoload.php';
add_action(
	'plugins_loaded',
	function () {
		WP_Dependency_Installer::instance( __DIR__ )->run();
	}
);

/**
 * Filters and Actions
 */
add_action( 'init', 'edacp_init' );
add_action( 'admin_enqueue_scripts', 'edacp_pro_admin_enqueue_scripts' );
add_action( 'admin_enqueue_scripts', 'edacp_pro_admin_enqueue_styles' );
add_action(
	'admin_init',
	function () {
		$updates = new EDACP\Updates();
		$updates->run();
	}
);
add_action( 'admin_init', 'edacp_update_database', 11 );
add_action( 'admin_init', 'edacp_register_setting', 11 );
add_action( 'admin_menu', 'edacp_add_options_page', 11 );
add_filter( 'edac_filter_simplified_summary_heading', 'edacp_simplified_summary_heading' );
add_filter( 'edac_filter_post_types', 'edacp_post_types' );
add_filter( 'edac_ignore_permission', 'edacp_set_ignore_permission' );
add_action( 'admin_init', 'edacp_custom_columns', 11 );
add_filter( 'edac_get_content', 'edacp_get_content' );
add_filter( 'cron_request', 'edacp_cron_http_auth' );
add_action( 'edacp_scan_content_hook', 'edacp_scan_content', 11, 1 );
add_action( 'edacp_spawn_schedules_hook', 'edacp_spawn_schedules', 11, 1 );
add_action( 'wp_ajax_edacp_global_ignore_ajax', 'edacp_global_ignore_ajax' );
add_filter( 'edac_filter_settings_tab_items', 'edacp_filter_settings_tab_items' );
add_action( 'edac_settings_tab_content', 'edacp_scan_settings_tab', 11, 1 );
add_action( 'edac_settings_tab_content', 'edacp_license_settings_tab', 11, 1 );
add_action( 'edac_before_validate', 'edacp_before_validate', 10, 2 );
add_action( 'edac_after_validate', 'edacp_after_validate', 10, 2 );
add_action( 'edac_before_rule', 'edacp_before_rule', 10, 3 );
add_action( 'edac_after_rule', 'edacp_after_rule', 10, 3 );
add_action( 'edac_rule_errors', 'edacp_rule_errors', 10, 4 );
add_action( 'edac_after_get_content', 'edacp_after_get_content', 10, 3 );
add_filter( 'edac_filter_insert_rule_data', 'edacp_check_global_ignore_on_insert_rule' );
add_filter( 'pand_theme_loader', '__return_true' );
add_action( 'edacp_scans_stats_runner_hook', 'edacp_scans_stats_runner' );

/**
 * Init the plugin
 */
function edacp_init() {
	// instantiate the classes that need to load hooks early.

	if ( EDACP_KEY_VALID === true ) {
		// instantiate the classes that need to load hooks early.
		new \EDACP\Rest_api();
	}
}

/**
 * Updates the database schema for the Accessibility Checker Pro plugin.
 *
 * This function is responsible for bringing the database schema up to date
 * with the current plugin version. It first checks if the database version
 * matches the current plugin version (EDACP_DB_VERSION). If they are the same,
 * it exits early as no update is needed.
 *
 * The function performs the following operations:
 * 1. Drops the 'accessibility_checker_logs' table if it exists. This is relevant
 *    as of version 1.0.5 of the plugin. The use of a direct database query is necessary
 *    here because dbDelta() does not support dropping tables.
 *    Note: WordPress CodeSniffer rules regarding direct database queries and schema changes
 *    are intentionally ignored for this operation.
 *
 * 2. Creates or updates the 'accessibility_checker_global_ignores' table using dbDelta(),
 *    which is the recommended approach for creating or altering tables in WordPress.
 *    The table structure includes fields for ID, site ID, rule, object, creation timestamp,
 *    and user, with ID as a unique key.
 *
 * 3. Updates the 'edacp_db_version' option in the WordPress options table to the current
 *    plugin version, indicating that the database schema has been updated.
 *
 * @global wpdb $wpdb Global WordPress database object used for executing SQL queries.
 */
function edacp_update_database() {
	if ( get_option( 'edacp_db_version' ) === EDACP_DB_VERSION ) {
		return;
	}

	global $wpdb;
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange -- Using direct query for table drop, caching not required for one time operation.
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}accessibility_checker_logs;" );

	dbDelta(
		"CREATE TABLE {$wpdb->prefix}accessibility_checker_global_ignores (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            siteid text NOT NULL,
            rule text NOT NULL,
            object mediumtext NOT NULL,
            created timestamp NOT NULL default CURRENT_TIMESTAMP,
            user bigint(20) NOT NULL,
            UNIQUE KEY id (id)
        ) {$wpdb->get_charset_collate()};"
	);

	update_option( 'edacp_db_version', EDACP_DB_VERSION );
}

/**
 * Fitler Post Types
 *
 * @param  array $post_types post types list.
 * @return array
 */
function edacp_post_types( $post_types ) {
	if ( EDACP_KEY_VALID === true && function_exists( 'edac_custom_post_types' ) ) {

		$custom_post_types = edac_custom_post_types();

		if ( is_array( $post_types ) && is_array( $custom_post_types ) ) {
			$post_types = array_merge( $post_types, $custom_post_types );
		}
	}

	return $post_types;
}

/**
 * Set Ignore Permission
 *
 * @param  bool $ignore_permission whether to ignore.
 * @return bool
 */
function edacp_set_ignore_permission( $ignore_permission ) {
	if ( EDACP_KEY_VALID === true ) {

		$ignore_user_roles = get_option( 'edacp_ignore_user_roles' );
		if ( ! $ignore_user_roles ) {
			return false;
		}
		$user = wp_get_current_user();
		if ( $user->roles ) {
			foreach ( $user->roles as $role ) {
				if ( ! in_array( $role, (array) $ignore_user_roles, true ) ) {
					$ignore_permission = false;
				}
			}
		}
	}

	return $ignore_permission;
}

/**
 * Filter get content
 *
 * @param  array $content The content to filter.
 * @return array
 */
function edacp_get_content( $content ) {
	if ( ! empty( $content['file_html'] ) ) {
		$content['the_content_html'] = $content['file_html'];
	}

	return $content;
}

/**
 * Automated content scan
 *
 * @param  int $post_id id of the post.
 * @return void
 */
function edacp_scan_content( $post_id ) {
	if ( EDACP_KEY_VALID === true ) {
		$post = get_post( $post_id );
		if ( $post ) {
			edac_validate( $post->ID, $post, $action = 'scan' );
			edac_summary( $post->ID );
		}
	}
}

/**
 * Load scans_stats into cache
 *
 * @return void
 */
function edacp_scans_stats_runner() {
	if ( class_exists( 'EDAC\Scans_Stats' ) ) {
		( new EDAC\Scans_Stats() )->load_cache();
	}
}

/**
 * Get the ids of the posts that will be scanned.
 *
 * @return array
 */
function edacp_get_post_ids_to_scan() { 

	$list = array();

	if ( EDACP_KEY_VALID === true ) {

		$post_types = get_option( 'edac_post_types' );
		if ( is_array( $post_types ) && count( $post_types ) > 0 ) {

			// Page the results for a lighter load.
			$paged = 0;
			do {
				++$paged;

				$args = array(
					'fields'                 => 'ids',
					'post_type'              => $post_types,
					'post_status'            => array( 'publish', 'future', 'draft', 'pending', 'private' ),
					'posts_per_page'         => 100,
					'no_found_rows'          => false, 
					'paged'                  => $paged,
					'update_post_term_cache' => false,
					'update_post_meta_cache' => false,
					'orderby'                => 'ID',
					'order'                  => 'ASC',
				);
	
				$query = new \WP_Query( $args );

				foreach ( $query->posts as $id ) {
					$list[] = $id;
				}
			} while ( $query->max_num_pages > $paged );
		}
	}

	return $list;
}

/**
 * Spawn schedules for each post
 *
 * @param  int $edacp_scan_id Id of the current scan.
 * @return void
 */
function edacp_spawn_schedules( $edacp_scan_id ) { 
	$post_ids = edacp_get_post_ids_to_scan();

	if ( count( $post_ids ) > 0 ) {

		set_transient( 'edacp_scan_total', count( $post_ids ) );

		foreach ( $post_ids as $id ) {
			as_enqueue_async_action( 'edacp_scan_content_hook', array( 'post_id' => $id ), $edacp_scan_id );
		}   
	}
}


add_action( 'action_scheduler_failed_action', 'edacp_scan_failed' );
/**
 * Scan failed
 *
 * @return void
 */
function edacp_scan_failed() { }

/**
 * Allow cron to run with http authorization
 *
 * @param  array $cron_request cron request.
 * @return array
 */
function edacp_cron_http_auth( $cron_request ) {

	$auth_username   = false;
	$legacy_username = get_option( 'edac_authorization_username', false );
	$username        = get_option( 'edacp_authorization_username', false );

	if ( $legacy_username ) {
		$auth_username = $legacy_username;
	}
	if ( $username ) {
		$auth_username = $username;
	}

	$auth_password   = false;
	$legacy_password = get_option( 'edac_authorization_password', false );
	$password        = get_option( 'edacp_authorization_password', false );

	if ( $legacy_password ) {
		$auth_password = $legacy_password;
	}
	if ( $password ) {
		$auth_password = $password;
	}

	if ( $auth_username && $auth_password ) {

		$headers = array(
			'Authorization' => sprintf(
				'Basic %s',
				base64_encode( $auth_username . ':' . $auth_password ) 
			),
		);

		$cron_request['args']['headers'] = isset( $cron_request['args']['headers'] )
			? array_merge( $cron_request['args']['headers'], $headers ) 
			: $headers;
	}

	return $cron_request;
}

/**
 * Add scan tab to settings page
 *
 * @param  array $settings_tab_items arrray of tab items.
 * @return array
 */
function edacp_filter_settings_tab_items( $settings_tab_items ) {
	if ( EDACP_KEY_VALID === true ) {

		$scan_tab = array(
			'slug'  => 'scan',
			'label' => 'Scan',
			'order' => 2,
		);
		array_push( $settings_tab_items, $scan_tab );
	}

	$scan_tab = array(
		'slug'  => 'license',
		'label' => 'License',
		'order' => 3,
	);
	array_push( $settings_tab_items, $scan_tab );

	return $settings_tab_items;
}

/**
 * Scan Setting Tab
 *
 * @param  string $tab name of tab.
 * @return void
 */
function edacp_scan_settings_tab( $tab ) {
	if ( EDACP_KEY_VALID === true ) {

		if ( 'scan' === $tab ) {
			$scans      = new \EDACP\Scans();
			$scan_state = $scans->scan_state();

			$post_types = get_option( 'edac_post_types' );
			?>
		
			<br />
			<div id="edacp-scan" class="edacp-scan">
				<?php if ( 0 === count( edacp_get_post_ids_to_scan() ) ) : ?>
					<div class="edacp-scan-section edacp-scan-start">
						<h2>
							<?php esc_html_e( 'Full Site Accessibility Check', 'edacp' ); ?>
						</h2>
						<p>
							<?php
							printf(
								// translators: %s: link to settings tab, with text "general settings".
								esc_html__( 'There are no pages set to be checked. Update the post types to be checked under the %s tab.', 'edacp' ),
								'<a href="' . esc_url( admin_url( 'admin.php?page=accessibility_checker_settings' ) ) . '">' .
									esc_html__( 'general settings', 'edacp' ) .
								'</a>'
							);
							?>
						</p>
					</div>
				<?php else : ?>
					<?php if ( ! get_option( 'edacp_scan_id' ) ) : ?>
						<div class="edacp-scan-section edacp-scan-start">
							<h2>
								<?php esc_html_e( 'Full Site Accessibility Check', 'edacp' ); ?>
							</h2>
							<p>
								<?php esc_html_e( 'Check the summary below, then click the green button to run a full site accessibility check.', 'edacp' ); ?>
							</p>
							<button class="edacp-scan-start-btn">
								<?php esc_html_e( 'Run Accessibility Check', 'edacp' ); ?>
							</button>
						</div>
					<?php endif; ?>

					<?php if ( get_option( 'edacp_scan_id' ) ) : ?>
						<div class="edacp-scan-section edacp-scan-progress">
							<h2 class="edacp-scan-progress-title"></h2>
							<p class="edacp-scan-progress-lead"></p>
							<div class="edacp-scan-progress-bar">
								<div class="edacp-scan-progress-bar-complete"></div>
								<div class="edacp-scan-progress-bar-percentage"></div>
							</div>
							<p class="edacp-scan-progress-count">
								<?php
								printf(
									// translators: %1$s: checked pages, %2$s: total pages, %3$s: pass percentage.
									esc_html__( 'Checked %1$s of %2$s pages %3$s', 'edacp' ),
									'<span class="edacp-scan-progress-complete"></span>',
									'<span class="edacp-scan-progress-total"></span>',
									'<span class="edacp-scan-progress-pass"></span>'
								);
								?>
							</p>
							<button class="edacp-scan-reset-btn">
								<?php esc_html_e( 'New Accessibility Check', 'edacp' ); ?>
							</button>
							<button class="edacp-scan-stop-pass1-btn">
								<?php esc_html_e( 'Stop Check', 'edacp' ); ?>
							</button>
							<button class="edacp-scan-stop-pass2-btn">
								<?php esc_html_e( 'Stop Check', 'edacp' ); ?>
							</button>
						</div>
					<?php endif; ?>
				<?php endif; ?>

				<?php if ( \EDACP\Scans::SCAN_STATE_NEW === $scan_state ) : ?>
					<div class="edacp-scan-section edacp-scan-summary">
						<h2><?php esc_html_e( 'Check Summary', 'edacp' ); ?></h2>
						<ul>
							<?php if ( $post_types ) : ?>
								<?php foreach ( $post_types as $post_type ) : ?>
									<?php
									if ( post_type_exists( $post_type ) ) {
										$post_type_object = get_post_type_object( $post_type );
										$count_pages      = wp_count_posts( $post_type );
										$count_pages      = $count_pages->publish + $count_pages->future + $count_pages->draft + $count_pages->pending + $count_pages->private;
										$label            = ( $post_type_object && 1 === $count_pages ) ? $post_type_object->labels->singular_name : $post_type_object->labels->name;
									}
									?>
									<li>
										<?php
										printf(
											// translators: %1$s: number of pages, %2$s: post type label.
											esc_html__( '%1$s %2$s will be checked.', 'edacp' ),
											esc_html( $count_pages ),
											esc_html( $label )
										);
										?>
									</li>
								<?php endforeach; ?>
							<?php endif; ?>
						</ul>
						<p>
							<?php
							printf(
								// translators: %s: link to settings tab, with text "general settings".
								esc_html__( 'Update the post types to be checked under the %s tab.', 'edacp' ),
								'<a href="' . esc_url( admin_url( 'admin.php?page=accessibility_checker_settings' ) ) . '">' .
									esc_html__( 'general settings', 'edacp' ) .
								'</a>'
							);
							?>
						</p>
					</div>

				<?php endif; ?>

			</div>
			<?php
		}
	}
}


/**
 * Scan Setting Tab
 *
 * @param  string $tab name of tab.
 * @return void
 */
function edacp_license_settings_tab( $tab ) {
	if ( 'license' === $tab ) {
		do_action( 'edac_license_tab' );
	}
}


/**
 * Before Validate.
 *
 * @param int    $post_ID The post id.
 * @param  string $action  action being taken.
 * @return void
 */
function edacp_before_validate( $post_ID, $action ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
}

/**
 * After Validate.
 *
 * @param  int    $post_ID post id.
 * @param  string $action  action being taken.
 * @return void
 */
function edacp_after_validate( $post_ID, $action ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
}

/**
 * Before Rule.
 *
 * @param  int    $post_ID post id.
 * @param  array  $rule    rule item.
 * @param  string $action  action being taken.
 * @return void
 */
function edacp_before_rule( $post_ID, $rule, $action ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
}

/**
 * After Rule.
 *
 * @param  int    $post_ID post id.
 * @param  array  $rule    rule item.
 * @param  string $action  action being taken.
 * @return void
 */
function edacp_after_rule( $post_ID, $rule, $action ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
}

/**
 * Rule Errors.
 *
 * @param  int    $post_ID post id.
 * @param  array  $rule    rule item.
 * @param  array  $errors  error message.
 * @param  string $action  action being taken.
 * @return void
 */
function edacp_rule_errors( $post_ID, $rule, $errors, $action ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
}

/**
 * After content.
 *
 * @param  int    $post_ID post id.
 * @param  string $content post content.
 * @param  string $action  action being taken.
 * @return void
 */
function edacp_after_get_content( $post_ID, $content, $action ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
}

/**
 * Process global ignore data
 *
 * @return void
 */
function edacp_global_ignore_ajax() {
	$ignore_results = null;

	global $wpdb;
	$siteid = get_current_blog_id();

	$table_name = $wpdb->prefix . 'accessibility_checker';

	// make sure table name is valid.
	if ( ! edacp_get_valid_table_name( $table_name ) ) {
		wp_die( esc_html__( 'Accessibility Checker Pro: A required table is missing.', 'edacp' ) );
	}

	// make sure the id was passed.
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( ! isset( $_REQUEST['id'] ) ) {
		wp_die( esc_html__( 'Accessibility Checker Pro: A required parameter is missing.', 'edacp' ) );
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$id = (int) $_REQUEST['id'];

	// make sure the ignore_action was passed.
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( ! isset( $_REQUEST['ignore_action'] ) ) {
		wp_die( esc_html__( 'Accessibility Checker Pro: A required parameter is missing.', 'edacp' ) );
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$ignore_action = sanitize_text_field( $_REQUEST['ignore_action'] );

	// TODO: replace table_name with %i - see: https://make.wordpress.org/core/2022/10/08/escaping-table-and-field-names-with-wpdbprepare-in-wordpress-6-1/ .
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
	$results = $wpdb->get_results(
		$wpdb->prepare(
			'SELECT object, rule FROM %i where id = %d and siteid = %d',
			$table_name,
			$id,
			$siteid
		),
		ARRAY_A
	);

	if ( $results[0]['rule'] && $results[0]['object'] && $ignore_action ) {
		$ignore_results = edacp_insert_global_ignore_data( $results[0]['rule'], $results[0]['object'], $ignore_action );
	}

	print wp_json_encode(
		array(
			'results'       => $ignore_results,
			'ignore_action' => $ignore_action,
		)
	);
	die();
}

/**
 * Insert global ignore data into database
 *
 * @param  string $rule   rule to insert.
 * @param  string $object_name object to insert.
 * @param  string $action action to take.
 * @return int
 */
function edacp_insert_global_ignore_data( $rule, $object_name, $action ) {
	global $wpdb;

	$table_name = $wpdb->prefix . 'accessibility_checker_global_ignores';

	// make sure table name is valid.
	if ( ! edacp_get_valid_table_name( $table_name ) ) {
		wp_die( esc_html__( 'Accessibility Checker Pro: A required table is missing.', 'edacp' ) );
	}

	$siteid      = get_current_blog_id();
	$user        = get_current_user_id();
	$object_name = esc_attr( $object_name );

	// Check if exists.
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
	$results = $wpdb->get_results(
		$wpdb->prepare(
			'SELECT id, object FROM %i where rule = %s and object = %s and siteid = %d',
			$table_name,
			$rule,
			$object_name,
			$siteid
		),
		ARRAY_A
	);

	// Delete records.
	if ( $results && 'disable' === $action ) {
		foreach ( $results as $row ) {

			// Return insert id or error.
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			return $wpdb->query(
				$wpdb->prepare(
					'DELETE FROM %i WHERE id = %d',
					$table_name,
					$row['id']
				)
			);
		}
	}

	// Insert new records.
	if ( ! $results ) {
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
		$wpdb->insert(
			$table_name,
			array(
				'siteid' => $siteid,
				'rule'   => $rule,
				'object' => $object_name,
				'user'   => $user,
			)
		);

		// Return insert id or error.
		return $wpdb->insert_id;
	}
}

/**
 * Check for global Ignore when adding new issues to the database
 *
 * @param  array $rule_data rule item.
 * @return array
 */
function edacp_check_global_ignore_on_insert_rule( $rule_data ) {
	global $wpdb;

	$table_name = $wpdb->prefix . 'accessibility_checker_global_ignores';

	// make sure table name is valid.
	if ( ! edacp_get_valid_table_name( $table_name ) ) {
		wp_die( esc_html__( 'Accessibility Checker Pro: A required table is missing.', 'edacp' ) );
	}

	// Check if exists.
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
	$results = $wpdb->get_results(
		$wpdb->prepare(
			'SELECT id FROM %i where rule = %s and object = %s and siteid = %d',
			$table_name,
			$rule_data['rule'],
			$rule_data['object'],
			$rule_data['siteid']
		),
		ARRAY_A
	);

	// if current rule issue matches a global rule set ignore database fields.
	if ( $results ) {
		$rule_data['ignre']         = 1;
		$rule_data['ignre_user']    = $rule_data['user'];
		$rule_data['ignre_date']    = gmdate( 'Y-m-d H:i:s' );
		$rule_data['ignre_comment'] = 'Global Ignore';
		$rule_data['ignre_global']  = 1;
	}

	return $rule_data;
}

add_action( 'pre_get_posts', 'edacp_pre_get_posts' );
/**
 * Pre get posts
 *
 * @param  obj $query the query object.
 * @return void
 */
function edacp_pre_get_posts( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	// passed.
	if ( 'edacp_passed_column' === $query->get( 'orderby' ) ) {
		$query->set( 'orderby', 'meta_value' );
		$query->set( 'meta_key', '_edac_summary_passed_tests' );
		$query->set( 'meta_type', 'numeric' );
	}

	// errors.
	if ( 'edacp_errors_column' === $query->get( 'orderby' ) ) {
		$query->set( 'orderby', 'meta_value' );
		$query->set( 'meta_key', '_edac_summary_errors' );
		$query->set( 'meta_type', 'numeric' );
	}

	// contrast erros.
	if ( 'edacp_contrast_column' === $query->get( 'orderby' ) ) {
		$query->set( 'orderby', 'meta_value' );
		$query->set( 'meta_key', '_edac_summary_contrast_errors' );
		$query->set( 'meta_type', 'numeric' );
	}

	// contrast warnings.
	if ( 'edacp_warnings_column' === $query->get( 'orderby' ) ) {
		$query->set( 'orderby', 'meta_value' );
		$query->set( 'meta_key', '_edac_summary_warnings' );
		$query->set( 'meta_type', 'numeric' );
	}

	// ignored.
	if ( 'edacp_ignored_column' === $query->get( 'orderby' ) ) {
		$query->set( 'orderby', 'meta_value' );
		$query->set( 'meta_key', '_edac_summary_ignored' );
		$query->set( 'meta_type', 'numeric' );
	}
}

/**
 * Filter Password Protected Notice Text
 */
add_filter(
	'edac_filter_password_protected_notice_text',
	function () {
		return sprintf(
			// translators: %s: link with text "Accessibility Checker settings page".
			esc_html__( 'Whoops! It looks like your website is currently password protected. Please ensure that you have entered login credentials on the %s so scanning will work. Scan results may be stored from a previous scan.', 'edacp' ),
			'<a href="' . admin_url( 'admin.php?page=accessibility_checker_settings' ) . '">' .
				esc_html__( 'Accessibility Checker settings page', 'edacp' ) .
			'</a>'
		);
	}
);
