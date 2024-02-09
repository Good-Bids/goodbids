<?php
/**
 * GoodBids Network Logs
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Network;

use GoodBids\Utilities\Log;

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
	 * @since 1.0.0
	 */
	public function __construct() {}

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

		foreach ( $files as $file ) {
			// Skip hidden files.
			if ( str_starts_with( $file, '.' ) ) {
				continue;
			}

			$logs[] = Log::get_logs_dir() . $file;
		}

		return collect( $logs )
			->sort(
				function ( $file_path ) {
					return filemtime( $file_path );
				}
			)
			->all();
	}
}
