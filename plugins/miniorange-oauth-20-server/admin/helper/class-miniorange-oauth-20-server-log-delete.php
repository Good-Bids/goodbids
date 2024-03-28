<?php
/**
 * Class Miniorange_Oauth_20_Server_Log_Delete
 *
 * @package Miniorange_Oauth_20_Server
 */

/**
 * Class Miniorange_Oauth_20_Server_Log_Delete
 *
 * This class handles the deletion of log files.
 */
class Miniorange_Oauth_20_Server_Log_Delete {

	/**
	 * Utils contains some commonly used functions
	 *
	 * @var [object]
	 */
	private $utils;

	/**
	 * Constructor for Miniorange_Oauth_20_Server_Log_Delete.
	 */
	public function __construct() {
		require_once MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/helper/class-miniorange-oauth-20-server-utils.php';

		$this->utils = new Miniorange_Oauth_20_Server_Utils();
	}

	/**
	 * This function handles the deletion of log files.
	 */
	public function handle_log_delete() {

		// delete old error log files.
		$this->mo_oauth_delete_debug_log_file();

		// show success message.
		update_option( 'message', 'Previous log cleared successfully', false );
		$this->utils->mo_oauth_show_success_message();
	}

	/**
	 * Summary of mo_oauth_delete_debug_log_file
	 *
	 * Deletes or empties the debug log file.
	 *
	 * @return void
	 */
	public function mo_oauth_delete_debug_log_file() {
		$file_name = MOSERVER_DIR . '/errorlogs/wp_oauth_server_errors.log';

		// Use the WP_Filesystem method to open the file.
		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}

		// Get the contents of the file.
		$file_contents = $wp_filesystem->get_contents( $file_name );

		// Overwrite the file with the fixed message.
		$wp_filesystem->put_contents( $file_name, 'This is miniOrange Oauth server plugin debug log' . PHP_EOL . '------------------------------------------------' . PHP_EOL );
	}
}
