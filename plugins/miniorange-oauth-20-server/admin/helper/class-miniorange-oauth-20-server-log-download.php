<?php
/**
 * Class Miniorange_Oauth_20_Server_Log_Download
 *
 * @package Miniorange_Oauth_20_Server
 */

/**
 * Class Miniorange_Oauth_20_Server_Log_Download
 *
 * This class handles the download of log file.
 */
class Miniorange_Oauth_20_Server_Log_Download {

	/**
	 * Function to download the log file.
	 */
	public function handle_log_download() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}
			$file_name          = MOSERVER_DIR . DIRECTORY_SEPARATOR . 'errorlogs' . DIRECTORY_SEPARATOR . 'wp_oauth_server_errors.log';
			$download_file_name = 'mo-server-log-' . gmdate( 'd-m-y-H-i-s' ) . '.log';
			$file_content       = $wp_filesystem->get_contents( $file_name );
			header( 'Content-Type: application/octet-stream' );
			header( 'Content-Transfer-Encoding: Binary' );
			header( 'Content-disposition: attachment; filename="' . $download_file_name . '"' );
			echo $file_content; //phpcs:ignore -- This is the debug log content.
			exit();
	}
}
