<?php
/**
 * GoodBids Network Logs
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Network;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use GoodBids\Utilities\Log;
use Illuminate\Support\Collection;

/**
 * Network Admin Logs Class
 *
 * @since 1.0.0
 */
class Logs {

	/**
	 * Logs Page Slug
	 *
	 * @since 1.0.0
	 * @var string
	 */
	const PAGE_SLUG = 'gb-logs';

	/**
	 * Download Log Query Var
	 *
	 * @since 1.0.0
	 * @var string
	 */
	const DOWNLOAD_SLUG = 'gb-log';

	/**
	 * Query string parameter for pagination
	 *
	 * @since 1.0.0
	 * @var string
	 */
	const PAGINATION_PARAM = 'gb-page';

	/**
	 * @var int
	 */
	private int $total_files = 0;

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		// Download log files.
		$this->maybe_download_log_file();
	}

	/**
	 * Get an array of log files, sorted by date.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_log_files(): array {
		$logs = [];
		$dir  = Log::get_logs_dir();

		if ( ! is_dir( $dir ) ) {
			return $logs;
		}

		$files = scandir( $dir );

		foreach ( $files as $file_name ) {
			// Skip hidden files.
			if ( str_starts_with( $file_name, '.' ) ) {
				continue;
			}

			$hash = $this->encrypt_log_file( $file_name );

			$logs[ $hash ] = Log::get_logs_dir() . $file_name;
		}

		$this->total_files = count( $logs );

		// Collect and Sort.
		$collection = collect( $logs )
			->sort(
				function ( $file_path ) {
					return filemtime( $file_path );
				}
			);

		return $this->apply_pagination( $collection );
	}

	/**
	 * Get Current Page
	 *
	 * @since 1.0.0
	 * @return int
	 */
	public function get_current_page(): int {
		return ! empty( $_GET[ self::PAGINATION_PARAM ] ) ? intval( sanitize_text_field( $_GET[ self::PAGINATION_PARAM ] ) ) : 1; // phpcs:ignore
	}

	/**
	 * Apply pagination to logs collection.
	 *
	 * @since 1.0.0
	 *
	 * @param Collection $collection
	 *
	 * @return array
	 */
	private function apply_pagination( Collection $collection ): array {
		$per_page = goodbids()->get_config( 'network.logs.per-page' );
		$current  = $this->get_current_page();
		$offset   = ( $current - 1 ) * $per_page;

		return $collection
			->slice( $offset, $per_page )
			->all();
	}

	/**
	 * Get total pages of logs.
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_total_pages(): int {
		$per_page = goodbids()->get_config( 'network.logs.per-page' );
		return ceil( $this->total_files / $per_page );
	}

	/**
	 * Get encryption key for log file names.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	private function get_encryption_key(): string {
		$env_var = goodbids()->get_config( 'vip-constants.auctioneer.api-key' );

		if ( ! $env_var ) {
			Log::error( 'Missing Auctioneer API Key constants config.' );
			return SECURE_AUTH_SALT;
		}

		return vip_get_env_var( $env_var, null );
	}

	/**
	 * Encrypt the log file name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file_name
	 *
	 * @return string
	 */
	private function encrypt_log_file( string $file_name ): string {
		$key = $this->get_encryption_key();

		if ( ! $key ) {
			Log::error( 'Missing Encryption Key.' );
			return $file_name;
		}

		return JWT::encode( compact( 'file_name' ), $key, 'HS256' );
	}

	/**
	 * Decrypt the log file name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $encrypted
	 *
	 * @return string
	 */
	private function decrypt_log_file( string $encrypted ): string {
		$key = $this->get_encryption_key();

		if ( ! $key ) {
			Log::error( 'Missing Encryption Key.' );
			return $encrypted;
		}

		$decrypted = JWT::decode( $encrypted, new Key( $key, 'HS256' ) );

		return $decrypted->file_name;
	}

	/**
	 * Stream the log file when download is requested.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function maybe_download_log_file(): void {
		add_action(
			'admin_init',
			function() {
				global $pagenow;

				if ( ! is_network_admin() || 'admin.php' !== $pagenow ) {
					return;
				}

				if ( empty( $_GET['page'] ) || sanitize_text_field( $_GET['page'] ) !== self::PAGE_SLUG || empty( $_GET[ self::DOWNLOAD_SLUG ] ) ) { // phpcs:ignore
					return;
				}

				$encrypted = sanitize_text_field( $_GET[ self::DOWNLOAD_SLUG ] ); // phpcs:ignore
				$file_name = $this->decrypt_log_file( $encrypted );
				$file_path = Log::get_logs_dir() . $file_name;

				if ( ! file_exists( $file_path ) ) {
					goodbids()->utilities->die( 'Error: File not found.' );
				}

				header( 'Content-Description: File Transfer' );
				header( 'Content-Type: application/octet-stream' );
				header( 'Content-Disposition: attachment; filename="' . basename( $file_path ) . '"' );
				header( 'Expires: 0' );
				header( 'Cache-Control: must-revalidate' );
				header( 'Pragma: public' );
				header( 'Content-Length: ' . filesize( $file_path ) );
				readfile( $file_path );
				exit;
			}
		);
	}
}
