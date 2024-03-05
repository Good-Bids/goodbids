<?php
/**
 * Class file for scans
 *
 * @package Accessibility_Checker_Pro
 */

namespace EDACP;

// phpcs:disable Generic.Commenting.Todo.TaskFound, Generic.Commenting.Todo.CommentFound

/**
 * Class that handles scans
 */
class Scans {

	const SCAN_STATE_NEW               = 'new';
	const SCAN_STATE_ALL_CANCELED      = 'canceled';
	const SCAN_STATE_QUEUED            = 'queued';
	const SCAN_STATE_PHP_SCAN_RUNNING  = 'php-scan-running';
	const SCAN_STATE_PHP_SCAN_COMPLETE = 'php-scan-complete';
	const SCAN_STATE_JS_SCAN_RUNNING   = 'js-scan-running';
	const SCAN_STATE_JS_SCAN_COMPLETE  = 'js-scan-complete';
	const SCAN_STATE_PRE_COMPLETED     = 'pre-completed';
	const SCAN_STATE_COMPLETED         = 'completed';

	const SCAN_STATE_INVALID = 'invalid';

	/**
	 * The id used for the group in the db.
	 *
	 * @var integer
	 */
	public $group_id;

	/**
	 * The name of the group and the slug field in the db.
	 *
	 * @var [type]
	 */
	public $group_name;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->refresh_group();
	}

	/**
	 * Refresh the group. Should be called anytime the edacp_scan_id is changed.
	 *
	 * @return void
	 */
	public function refresh_group() {
		global $wpdb;

		$group_name = get_option( 'edacp_scan_id' );

		if ( $group_name ) {

			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$group_id       = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT group_id FROM {$wpdb->actionscheduler_groups} WHERE slug = %s",
					$group_name,
				)
			);
			$this->group_id = $group_id;
		}
		$this->group_name = $group_name;
	}

	/**
	 * Gets status of a scheduled or manually run fullscan.
	 *
	 * @return string
	 */
	public function scan_state() {

		
		if ( EDACP_KEY_VALID === true ) {
		
			if ( false === $this->group_name && self::SCAN_STATE_NEW !== get_option( 'edacp_scan_state', false ) ) {   
			
				update_option( 'edacp_scan_state', self::SCAN_STATE_NEW );

				
				return self::SCAN_STATE_NEW;
			}

			if ( self::SCAN_STATE_ALL_CANCELED === get_option( 'edacp_scan_state' ) ) {
				return self::SCAN_STATE_ALL_CANCELED;
			}

			if ( self::SCAN_STATE_PRE_COMPLETED === get_option( 'edacp_scan_state' ) ) {
				$this->delete_expired_scheduled_php_scans();
				update_option( 'edacp_scan_state', self::SCAN_STATE_COMPLETED );
				update_option( 'edacp_fullscan_completed_at', time() );
				return self::SCAN_STATE_COMPLETED;
			}

			if ( self::SCAN_STATE_COMPLETED === get_option( 'edacp_scan_state' ) ) {
				return self::SCAN_STATE_COMPLETED;
			}

			if ( self::SCAN_STATE_JS_SCAN_COMPLETE === get_option( 'edacp_scan_state' ) ) {
				update_option( 'edacp_scan_state', self::SCAN_STATE_PRE_COMPLETED );
				return self::SCAN_STATE_PRE_COMPLETED;
			}

			// edacp_spawn_schedules_hook aren't associated with the scan group, so don't filter with.
			if ( as_has_scheduled_action( 'edacp_spawn_schedules_hook' ) ) {
				update_option( 'edacp_scan_state', self::SCAN_STATE_QUEUED );
				return self::SCAN_STATE_QUEUED;
			}

			if ( as_has_scheduled_action( 'edacp_scan_content_hook', null, $this->group_name ) ) {
				update_option( 'edacp_scan_state', self::SCAN_STATE_PHP_SCAN_RUNNING );
				return self::SCAN_STATE_PHP_SCAN_RUNNING;
			}

			if ( self::SCAN_STATE_QUEUED === get_option( 'edacp_scan_state' ) ) {
				if ( $this->has_pending( 'php' ) ) {
					update_option( 'edacp_scan_state', self::SCAN_STATE_PHP_SCAN_RUNNING );
					return self::SCAN_STATE_PHP_SCAN_RUNNING;
				}
				if ( $this->recently_completed( ( 'php' ) ) ) {
					update_option( 'edacp_scan_state', self::SCAN_STATE_PHP_SCAN_RUNNING );
					return self::SCAN_STATE_PHP_SCAN_RUNNING;
				}
				update_option( 'edacp_scan_state', self::SCAN_STATE_PHP_SCAN_COMPLETE );
				return self::SCAN_STATE_PHP_SCAN_COMPLETE;
			}

			if ( self::SCAN_STATE_PHP_SCAN_RUNNING === get_option( 'edacp_scan_state' ) ) {
				if ( $this->has_pending( 'php' ) ) {
					return self::SCAN_STATE_PHP_SCAN_RUNNING;
				}
				if ( $this->recently_completed( ( 'php' ) ) ) {
					return self::SCAN_STATE_PHP_SCAN_RUNNING;
				}
				update_option( 'edacp_scan_state', self::SCAN_STATE_PHP_SCAN_COMPLETE );
				return self::SCAN_STATE_PHP_SCAN_COMPLETE;
			}

			if ( self::SCAN_STATE_PHP_SCAN_COMPLETE === get_option( 'edacp_scan_state' ) ) {

				// wait for php scan to complete before changing to js scan
				// otherwise the scan progress can show pass 2 before pass 1 has completed.
				update_option( 'edacp_scan_state', self::SCAN_STATE_JS_SCAN_RUNNING );
				return self::SCAN_STATE_JS_SCAN_RUNNING;
			}

			if ( self::SCAN_STATE_JS_SCAN_RUNNING === get_option( 'edacp_scan_state' ) ) {

				if ( $this->has_pending( 'js' ) ) {
					return self::SCAN_STATE_JS_SCAN_RUNNING;
				}
				if ( $this->recently_completed( 'js' ) ) {
					return self::SCAN_STATE_JS_SCAN_RUNNING;
				}
				update_option( 'edacp_scan_state', self::SCAN_STATE_JS_SCAN_COMPLETE );
				return self::SCAN_STATE_JS_SCAN_COMPLETE;
			}

			if ( $this->has_pending( 'php' ) ) {
				update_option( 'edacp_scan_state', self::SCAN_STATE_PHP_SCAN_RUNNING );
				return self::SCAN_STATE_PHP_SCAN_RUNNING;
			}

			if ( self::SCAN_STATE_NEW !== get_option( 'edacp_scan_state' ) ) {
				update_option( 'edacp_scan_state', self::SCAN_STATE_NEW );
			}
			return self::SCAN_STATE_NEW;
		}

		if ( self::SCAN_STATE_INVALID !== get_option( 'edacp_scan_state' ) ) {
			update_option( 'edacp_scan_state', self::SCAN_STATE_INVALID );
		}
		return self::SCAN_STATE_INVALID;
	}

	/**
	 * Gets the date of the latest post scan.
	 *
	 * @param string $scan_type php|js - defaults to php.
	 * @return integer - unix timestamp.
	 */
	public function scan_date( $scan_type = 'php' ) {
		global $wpdb;

		if ( 'js' === $scan_type ) {

			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			return $wpdb->get_var(
				$wpdb->prepare(
					"SELECT MAX(CAST({$wpdb->postmeta}.meta_value AS SIGNED))
					FROM {$wpdb->posts}
					INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id )
					WHERE 1=%d
					AND {$wpdb->postmeta}.meta_key = '_edac_post_checked_js'
					AND {$wpdb->posts}.post_type IN ('post', 'page')
					AND {$wpdb->posts}.post_status IN ('publish','future','draft','pending','private')",
					array( 1 )
				)
			);
		}
		return edacp_gmt_to_unix(
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->get_var(
				$wpdb->prepare(
					"SELECT last_attempt_gmt FROM {$wpdb->actionscheduler_actions}
					JOIN {$wpdb->actionscheduler_groups} ON {$wpdb->actionscheduler_groups}.group_id = {$wpdb->actionscheduler_actions}.group_id
					WHERE hook=%s and status=%s and slug=%s
					LIMIT 1",
					array(
						'edacp_scan_content_hook',
						\ActionScheduler_Store::STATUS_COMPLETE,
						$this->group_name,
					)
				)
			)
		);
	}

	/**
	 * Delete expired scheduled php scans.
	 *
	 * @return void
	 */
	public function delete_expired_scheduled_php_scans() {

		if ( EDACP_KEY_VALID === true ) {
			global $wpdb;

			// Delete all pending edacp_spawn_schedules_hooks.
			// edacp_spawn_schedules_hook aren't associated with the scan group, so don't filter with.
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->query(
				$wpdb->prepare(
					"DELETE FROM {$wpdb->actionscheduler_actions} WHERE hook = %s and status = %s",
					array(
						'edacp_spawn_schedules_hook',
						\ActionScheduler_Store::STATUS_PENDING,
					)
				)
			);

			// Delete all edacp_scan_content_hooks that are not complete or canceled.
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->query(
				$wpdb->prepare(
					"DELETE FROM {$wpdb->actionscheduler_actions} WHERE hook = %s and (status != %s and status != %s)",
					array(
						'edacp_scan_content_hook',
						\ActionScheduler_Store::STATUS_COMPLETE,
						\ActionScheduler_Store::STATUS_CANCELED,
					)
				)
			);
		}
	}

	/**
	 * Sets the php scan to start on the next action scheduler hook cycle.
	 *
	 * @return boolean if scheduled ok or not.
	 */
	public function start_php_scan() {
		if ( EDACP_KEY_VALID === true ) {

			$spawn_hook = as_next_scheduled_action( 'edacp_spawn_schedules_hook' );
			$scan_hook  = as_next_scheduled_action( 'edacp_scan_content_hook' );
	
			// if current scan prevent mulitple spawn schedules.
			if ( 1 === $spawn_hook || 1 === $scan_hook ) {
				return false;
			}
	
			$scan_id = uniqid();
			update_option( 'edacp_scan_id', $scan_id );
	
			global $wpdb;
	
			// delete checks from the table for pagetypes that we should not scan.
			$table_name = $wpdb->prefix . 'accessibility_checker';
	
			// TODO: On refactor, add a valid_table_names[] property that is set early to reduce DB load.
			// make sure table name is valid.
			if ( ! edacp_is_valid_table_name( $table_name ) ) {
				wp_die( esc_html__( 'Accessibility Checker Pro: A required table has an incorrect name.', 'edacp' ) );
			}
	
			// get a sanitized list of post_types.
			$post_types = Settings::get_scannable_post_types();
	
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->query(
				$wpdb->prepare(
					sprintf(
						'DELETE FROM %%i WHERE `type` NOT IN (%s)',
						implode( ',', array_fill( 0, count( $post_types ), '%s' ) )
					),
					array_merge(
						array( $table_name ),
						$post_types
					)
				)
			);
	
			// Clean all scheduled, expired and queued php scans.
			$this->delete_expired_scheduled_php_scans();
			
			// Reset the recorded scanned time for all previously scanned (js) posts to the current time.
			$wpdb->query( //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
				$wpdb->prepare(
					"UPDATE {$wpdb->postmeta} SET meta_value = %d WHERE meta_key = %s",
					array(
						time(), 
						'_edac_post_checked_js',
					)
				)
			);
	
	
			as_enqueue_async_action( 'edacp_spawn_schedules_hook', array( 'edacp_scan_id' => $scan_id ) );
	
			return true;
		}
	}

	/**
	 * Cancels a running scan
	 *
	 * @param  boolean $all - false means only php scan is canceled.
	 * @return array [error, message]
	 */
	public function cancel_current_scan( $all = true ) {
		if ( EDACP_KEY_VALID === true ) {
			global $wpdb;
			if ( true !== $all && self::SCAN_STATE_QUEUED === $this->scan_state() ) {
				$all = true;
			}

			$this->delete_expired_scheduled_php_scans();
			
			// Cancel all pending scans.
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->update(
				$wpdb->actionscheduler_actions,
				array(
					'status' => \ActionScheduler_Store::STATUS_CANCELED,
				),
				array(
					'hook' => 'edacp_scan_content_hook',
				)
			);

			

			if ( $all ) {
				// Reset the recorded scanned time for all previously scanned (js) posts to the current time.
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
				$wpdb->query(
					$wpdb->prepare(
						"UPDATE {$wpdb->postmeta} SET meta_value = %d WHERE meta_key = %s",
						array(
							time(),
							'_edac_post_checked_js',
						)
					)
				);

				update_option( 'edacp_scan_state', self::SCAN_STATE_ALL_CANCELED );
	
				delete_option( 'edacp_scan_id' );
	
			} else {
				// Only canceling the php scan.
				update_option( 'edacp_scan_state', self::SCAN_STATE_PHP_SCAN_COMPLETE );

			}
	
		
			return array(
				'error'   => false,
				'message' => __( 'The running scan has been canceled.', 'edacp' ),
			);
		}

		return array(
			'error'   => true,
			'message' => '',
		);
	}

	/**
	 * Gets the timestamp that the last scan_content_hook was completed or canceled.
	 *
	 * @return integer - timestamp in unix format
	 */
	public function php_scan_completed_or_canceled_at() {

		if ( EDACP_KEY_VALID === true ) {
			global $wpdb;

			if ( ! empty( $this->group_id ) ) {

				// Find the last scan completed from the edac_scan_id group.
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
				$last_completed_date_gmt = $wpdb->get_var(
					$wpdb->prepare(
						"SELECT last_attempt_gmt FROM {$wpdb->actionscheduler_actions}
						WHERE hook=%s and status=%s and group_id=%d
						ORDER BY  last_attempt_gmt DESC LIMIT 1",
						array(
							'edacp_scan_content_hook',
							\ActionScheduler_Store::STATUS_COMPLETE,
							$this->group_id,
						)
					)
				);

				// Find the last scan canceled from the edac_scan_id group.
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
				$last_canceled_date_gmt = $wpdb->get_var(
					$wpdb->prepare(
						"SELECT scheduled_date_gmt FROM {$wpdb->actionscheduler_actions}
						WHERE hook=%s and status=%s and group_id=%d
						ORDER BY scheduled_date_gmt DESC LIMIT 1",
						array(
							'edacp_scan_content_hook',
							\ActionScheduler_Store::STATUS_CANCELED,
							$this->group_id,
						)
					)
				);

				if ( $last_canceled_date_gmt >= $last_completed_date_gmt ) {
					return edacp_gmt_to_unix( $last_canceled_date_gmt );
				}
				return edacp_gmt_to_unix( $last_completed_date_gmt );
			}

			// Find the last scan completed from all edac_scan_id groups.
			return edacp_gmt_to_unix(
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
				$wpdb->get_var(
					$wpdb->prepare(
						"SELECT last_attempt_gmt FROM {$wpdb->actionscheduler_actions}
						WHERE hook=%s and status=%s
						ORDER BY  last_attempt_gmt DESC LIMIT 1",
						array(
							'edacp_scan_content_hook',
							\ActionScheduler_Store::STATUS_COMPLETE,
						)
					)
				)
			);
		}
		return 0;
	}

	/**
	 * Returns if there are posts that need to be scanned.
	 *
	 * @param  string $scan_type - js | php.
	 * @return boolean
	 */
	public function has_pending( $scan_type = 'js' ) {

		if ( EDACP_KEY_VALID === true ) {

			if ( 'js' === $scan_type ) {

				$php_scan_completed_or_canceled_at = $this->php_scan_completed_or_canceled_at();

				$args = array(
					'fields'         => 'ids',
					'post_type'      => get_option( 'edac_post_types' ),
					'post_status'    => array( 'publish', 'future', 'draft', 'pending', 'private' ),
					'posts_per_page' => 1,
					'no_found_rows'  => false, 
					// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					'meta_query'     => array( 
						'relation' => 'OR',
						array(
							'key'     => '_edac_post_checked_js',
							'value'   => $php_scan_completed_or_canceled_at,
							'compare' => '<=',
							'type'    => 'NUMERIC',
						),
						array(
							'key'     => '_edac_post_checked_js',
							'compare' => 'NOT EXISTS',
						),
					),

				);

				$query = new \WP_Query( $args );
				return 1 === count( $query->posts );
			}

			if ( 'php' === $scan_type ) {
				global $wpdb;

				if ( ! empty( $this->group_id ) ) {

					// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
					$row = $wpdb->get_row(
						$wpdb->prepare(
							"SELECT action_id FROM {$wpdb->actionscheduler_actions}
								JOIN {$wpdb->actionscheduler_groups} ON {$wpdb->actionscheduler_groups}.group_id = {$wpdb->actionscheduler_actions}.group_id
								WHERE hook=%s and status=%s and slug=%s
								LIMIT 1",
							array(
								'edacp_scan_content_hook',
								\ActionScheduler_Store::STATUS_PENDING,
								$this->group_name,
							)
						)
					);

					return null !== $row;
				}
				return false;
			}
		}
		return false;
	}

	/**
	 * Returns if the scan was recently completed.
	 *
	 * @param  string  $scan_type - js | php.
	 * @param  integer $window    - length of window in seconds.
	 * @return boolean
	 */
	public function recently_completed( $scan_type = 'js', $window = 5 ) {

		if ( EDACP_KEY_VALID === true ) {

			if ( 'js' === $scan_type ) {
				return ( time() - $this->scan_date( 'js' ) <= $window );
			}

			if ( 'php' === $scan_type ) {
				if ( ! empty( $this->group_id ) ) {
					return ( ( time() - $this->scan_date( 'php' ) ) <= $window );
				}
				return false;
			}
		}
		return false;
	}

	/**
	 * Gets stats on the most recent scan
	 *
	 * @param  string $scan_type - js | php.
	 * @return array
	 */
	public function get_stats( $scan_type = 'js' ) {

		if ( EDACP_KEY_VALID === true ) {

			$total_scannable_posts = 0;

			// TODO: use get_scannable_post_types.
			$edac_post_types = get_option( 'edac_post_types' );
			if ( ! is_array( $edac_post_types ) ) {
				$tmp[]           = $edac_post_types;
				$edac_post_types = $tmp;
			}

			foreach ( $edac_post_types as $post_type ) {

				// TODO: use get_scannable_post_statuses.

				if ( isset( wp_count_posts( $post_type )->publish ) ) {
					$total_scannable_posts += wp_count_posts( $post_type )->publish;
				}

				if ( isset( wp_count_posts( $post_type )->future ) ) {
					$total_scannable_posts += wp_count_posts( $post_type )->future;
				}

				if ( isset( wp_count_posts( $post_type )->draft ) ) {
					$total_scannable_posts += wp_count_posts( $post_type )->draft;
				}

				if ( isset( wp_count_posts( $post_type )->pending ) ) {
					$total_scannable_posts += wp_count_posts( $post_type )->pending;
				}

				if ( isset( wp_count_posts( $post_type )->private ) ) {
					$total_scannable_posts += wp_count_posts( $post_type )->private;
				}
			}

			if ( 'js' === $scan_type ) {

				$never_scanned = count( $this->get_never_scanned() );
				$pending       = count( $this->get_pending() );

				$all_pending = $never_scanned + $pending;
				$completed   = $total_scannable_posts - $all_pending;

				$progress = 0;
				if ( $total_scannable_posts > 0 ) {
					$progress = round( $completed / $total_scannable_posts ) * 100;
				}

				return array(
					'total'    => $total_scannable_posts,
					'complete' => $completed,
					'failed'   => 0, // TODO.
					'pending'  => $all_pending,
					'progress' => $progress,
					'state'    => $this->scan_state(),
					'date'     => $this->scan_date( 'js' ),
				);

			}

			if ( 'php' === $scan_type ) {
				// TODO: on refactor, optimize these db calls.

				$data          = array();
				$data['total'] = $total_scannable_posts;
				$data['state'] = $this->scan_state();

				$data['date'] = $this->scan_date( 'php' );

				// TODO: replace with \EDAC\Helpers::format_date().
				$date_format = get_option( 'date_format' );
				if ( ! $date_format ) {
					$date_format = 'j M Y';
				}
				$datetime = new \DateTime();
				$datetime->setTimestamp( $data['date'] );
				$datetime->setTimezone( wp_timezone() );
				$data['date_formatted'] = $datetime->format( $date_format );

				$args             = array(
					'hook'     => 'edacp_scan_content_hook',
					'group'    => $this->group_name,
					'status'   => \ActionScheduler_Store::STATUS_COMPLETE,
					'per_page' => -1,
				);
				$data['complete'] = count( as_get_scheduled_actions( $args ) );

				$args           = array(
					'hook'     => 'edacp_scan_content_hook',
					'group'    => $this->group_name,
					'status'   => \ActionScheduler_Store::STATUS_FAILED,
					'per_page' => -1,
				);
				$data['failed'] = count( as_get_scheduled_actions( $args ) );

				$args            = array(
					'hook'     => 'edacp_scan_content_hook',
					'group'    => $this->group_name,
					'status'   => \ActionScheduler_Store::STATUS_PENDING,
					'per_page' => -1,
				);
				$data['pending'] = count( as_get_scheduled_actions( $args ) );

				return $data;
			}
		}
		return array();
	}

	/**
	 * Returns a list of info about posts that have never been js scanned.
	 *
	 * @return array - post_id, last_scanned (unix timestamp), preview_url
	 */
	public function get_never_scanned() {

		if ( EDACP_KEY_VALID === true ) {
			$list  = array();
			$paged = 0;

			do {
				++$paged;

				$args = array(
					'fields'                 => 'ids',
					'post_type'              => get_option( 'edac_post_types' ),
					'post_status'            => array( 'publish', 'future', 'draft', 'pending', 'private' ),
					'posts_per_page'         => 100,
					'no_found_rows'          => false,
					'paged'                  => $paged,
					'update_post_term_cache' => false,
					'update_post_meta_cache' => false,
					// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					'meta_query'             => array(
						array(
							'key'     => '_edac_post_checked_js',
							'compare' => 'NOT EXISTS',
						),
					),
				);

				$query = new \WP_Query( $args );

				foreach ( $query->posts as $id ) {
					$list[] = array(
						'id'           => $id,
						'last_scanned' => null,
					);
				}
			} while ( $query->max_num_pages > $paged );

			return $list;
		}
		return array();
	}

	/**
	 * Returns a list of info about posts that need to be js scanned.
	 * These are posts that have not been scanned (js) since before the last
	 * scheduled (php) or manually run fullscan (php) finished running.
	 *
	 * @return array - post_id, last_scanned (unix timestamp), preview_url
	 */
	public function get_pending() {

		if ( EDACP_KEY_VALID === true ) {

			$list = array();

			if (
				self::SCAN_STATE_QUEUED === $this->scan_state() ||
				self::SCAN_STATE_PHP_SCAN_RUNNING === $this->scan_state()
			) {
				return $list;
			}

			$php_scan_completed_or_canceled_at = $this->php_scan_completed_or_canceled_at();

			// Page results for a lighter sql load.
			$paged = 0;

			do {

				++$paged;

				$args = array(
					'fields'                 => 'ids',
					'post_type'              => get_option( 'edac_post_types' ),
					'post_status'            => array( 'publish', 'future', 'draft', 'pending', 'private' ),
					'posts_per_page'         => 100,
					'no_found_rows'          => false,
					'paged'                  => $paged,
					'update_post_term_cache' => false,
					'update_post_meta_cache' => false,
					// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					'meta_query'             => array(
						'relation' => 'and',
						array(
							'key'     => '_edac_post_checked_js',
							'value'   => $php_scan_completed_or_canceled_at,
							'compare' => '<=',
							'type'    => 'NUMERIC',
						),
					),
				);

				$query = new \WP_Query( $args );

				foreach ( $query->posts as $id ) {
					$list[] = array(
						'id'           => $id,
						'last_scanned' => (int) get_post_meta( $id, '_edac_post_checked_js', true ),
					);
				}
			} while ( $query->max_num_pages > $paged );

			return $list;
		}
		return array();
	}
}
