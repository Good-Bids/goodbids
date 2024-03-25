<?php
/**
 * Fired during plugin activation
 *
 * @link       https://www.miniorange.com
 * @since      1.0.0
 *
 * @package    Miniorange_Oauth_20_Server
 * @subpackage Miniorange_Oauth_20_Server/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Miniorange_Oauth_20_Server
 * @subpackage Miniorange_Oauth_20_Server/includes
 * @author     miniOrange <info@xecurify.com>
 */
class Miniorange_Oauth_20_Server_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function activate() {

		global $wpdb;

		update_option( 'host_name', 'https://login.xecurify.com' );

		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/helper/class-miniorange-oauth-20-server-db.php';
		$mo_oauth_server_db = new Mo_Oauth_Server_Db();
		$mo_oauth_server_db->mo_plugin_activate();

		// create a new cronjob to delete old debug logs.
		if ( ! wp_next_scheduled( 'mo_oauth_server_debug_delete_cron_job' ) ) {
			wp_schedule_event( time(), 'weekly', 'mo_oauth_server_debug_delete_cron_job' );
		}
	}

}
