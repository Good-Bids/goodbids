<?php
/**
 * Accessibility Checker pluign file.
 *
 * @package Accessibility_Checker
 */

// log query limit.
$log_limit = 10000;
if ( has_filter( 'edacp_log_limit' ) ) {
	$log_limit = apply_filters( 'edacp_log_limit', $log_limit );
}

global $wpdb;
$table_name = $wpdb->prefix . 'accessibility_checker';
$siteid     = get_current_blog_id();
// phpcs:disable WordPress.Security.NonceVerification.Recommended
$current_page       = ( isset( $_GET['page'] ) && ! empty( $_GET['page'] ) ) ? sanitize_text_field( $_GET['page'] ) : null;
$post_query_var     = isset( $_GET['post'] ) ? sanitize_text_field( $_GET['post'] ) : false;
$type_query_var     = isset( $_GET['type'] ) ? sanitize_text_field( $_GET['type'] ) : false;
$ruletype_query_var = isset( $_GET['ruletype'] ) ? sanitize_text_field( $_GET['ruletype'] ) : false;
$rule_query_var     = isset( $_GET['rule'] ) ? sanitize_text_field( $_GET['rule'] ) : false;
$search_query_var   = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : false;
// phpcs:enable WordPress.Security.NonceVerification.Recommended
$ignore            = ( 'accessibility_checker_ignored' === $current_page ) ? 1 : 0;
$ignore_global     = ( 'accessibility_checker_ignored' === $current_page ) ? 1 : 0;
$rules             = edac_register_rules();
$current_rule_info = ( $rule_query_var ) ? edac_filter_by_value( $rules, 'slug', $rule_query_var )[0] : false;
$ignore_permission = apply_filters( 'edac_ignore_permission', true );

$current_url = '';
if ( isset( $_SERVER['HTTP_HOST'] ) && isset( $_SERVER['REQUEST_URI'] ) ) {
	$host = sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) );
	$uri  = sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) );

	$current_url = '//' . $host . $uri;
}

// get orderby.
$allowed_orderby_values = array( 'postid', 'type', 'object' );
// phpcs:disable WordPress.Security.NonceVerification.Recommended
$current_orderby = isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : 'ruletype';
if ( ! in_array( $current_orderby, $allowed_orderby_values, true ) ) {
	$current_orderby = 'ruletype';
}

// get order.
$current_order = isset( $_GET['order'] ) ? sanitize_text_field( $_GET['order'] ) : 'ASC';
if ( ! in_array( $current_orderby, $allowed_orderby_values, true ) ) {
	$current_order = 'asc';
}

/**
 * Rules Count
 *
 * @param integer $ignore local ignore.
 * @param integer $ignore_global global ignore.
 * @return array
 */
function edacp_rules_count( $ignore = 0, $ignore_global = 0 ) {

	global $wpdb;
	$table_name = $wpdb->prefix . 'accessibility_checker';
	$siteid     = get_current_blog_id();
	$rules      = edac_register_rules();
	$output     = array();

	foreach ( $rules as $rule ) {
		if ( EDAC_ANWW_ACTIVE && 'link_blank' === $rule['slug'] ) {
			$rule['count'] = 0;
		} elseif ( 1 === $ignore ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$rule['count'] = $wpdb->get_var(
				$wpdb->prepare(
					'SELECT count(*) FROM %i where rule = %s and siteid = %d and ( ignre = %d or ignre_global = %d )',
					$table_name,
					$rule['slug'],
					$siteid,
					$ignore,
					$ignore_global
				)
			);
		} else {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$rule['count'] = $wpdb->get_var(
				$wpdb->prepare(
					'SELECT count(*) FROM %i where rule = %s and siteid = %d and ( ignre = %d and ignre_global = %d )',
					$table_name,
					$rule['slug'],
					$siteid,
					$ignore,
					$ignore_global
				)
			);
		}

		$output[] = $rule;
	}

	usort(
		$output,
		function ( $a, $b ) {
			return ( $a['count'] < $b['count'] ) ? 1 : -1;
		}
	);

	return $output;
}

/**
 * Create rules select
 *
 * @param string $current_page current page.
 * @param string $rule_query_var current rule.
 * @param int    $log_limit limit.
 * @return string
 */
function edacp_rule_select( $current_page, $rule_query_var, $log_limit ) {

	ob_start();
	$ignore = ( 'accessibility_checker_issues' === $current_page ) ? 0 : 1;

	$rules = edacp_rules_count( $ignore, $ignore );

	if ( $rules ) {
		ob_start();
		foreach ( $rules as $rule ) {
			if ( 0 === $rule['count'] ) {
				continue;
			}
			?>
			<option
				value="<?php echo esc_url( admin_url( 'admin.php?page=' . $current_page . '&rule=' . $rule['slug'] ) ); ?>"
				class="hide-if-no-js"
				<?php echo ( $rule_query_var === $rule['slug'] ) ? 'selected' : ''; ?>
			>
				<?php
				printf(
					( $rule['count'] >= $log_limit )
						// translators: %1$s is the rule title, %2$s is the log limit.
						? esc_html__( '%1$s (More than %2$s)', 'edacp' )
						// translators: %1$s is the rule title, %2$s is the rule count.
						: esc_html__( '%1$s (%2$s)', 'edacp' ),
					esc_html( $rule['title'] ),
					( $rule['count'] >= $log_limit )
						? number_format( $log_limit )
						: number_format( $rule['count'] )
				);
				?>
			</option>
			<?php
		}
		$options = ob_get_clean();

		if ( $options ) {
			?>
			<select name="action" class="edacp-rule-select">
				<?php
				$allowed_protocols = array(
					'option' => array(
						'value'    => array(),
						'class'    => array(),
						'selected' => array(),
					),
				);
				echo wp_kses( $options, $allowed_protocols );
				?>
			</select>
			<?php
		}
	}

	$html = ob_get_clean();

	return $html;
}
?>

<div class="edacp-issues-title-description<?php echo ( ! $rule_query_var && ! $post_query_var ) ? ' edacp-issues-title-description-limit' : ''; ?>">
	<?php if ( 'accessibility_checker_issues' === $current_page || 'accessibility_checker_ignored' === $current_page ) : ?>
		<?php
		$bread_crumbs_label     = ( 'accessibility_checker_issues' === $current_page )
			? esc_html__( 'Open Issues', 'edacp' )
			: esc_html__( 'Ignored Issues', 'edacp' );
		$bread_crumbs_label_url = admin_url( 'admin.php?page=' . $current_page );
		?>

		<h1>
			<?php if ( $post_query_var ) : ?>
				<a href="<?php echo esc_url( $bread_crumbs_label_url ); ?>">
					<?php echo esc_html( $bread_crumbs_label ); ?>
				</a>
				<span class="dashicons dashicons-arrow-right-alt2"></span> <?php echo esc_html( get_the_title( $post_query_var ) ); ?>
			<?php elseif ( $rule_query_var ) : ?>
				<a href="<?php echo esc_url( $bread_crumbs_label_url ); ?>">
					<?php echo esc_html( $bread_crumbs_label ); ?>
				</a>
				<span class="dashicons dashicons-arrow-right-alt2"></span> <?php echo esc_html( edac_filter_by_value( $rules, 'slug', $rule_query_var )[0]['title'] ); ?>
			<?php else : ?>
				<?php echo esc_html( $bread_crumbs_label ); ?>
			<?php endif; ?>
		</h1>

		<?php
		if ( $rule_query_var && $rules ) {

			$allowed_protocols = array(
				'option' => array(
					'value'    => array(),
					'class'    => array(),
					'selected' => array(),
				),
				'select' => array(
					'name'  => array(),
					'class' => array(),
				),
			);
			echo wp_kses( edacp_rule_select( $current_page, $rule_query_var, $log_limit ), $allowed_protocols );
		}
		?>

		<div class="clear"></div>

		<?php if ( ! $rule_query_var && ! $post_query_var ) : ?>
			<p>
				<?php if ( 'accessibility_checker_issues' === $current_page ) : ?>
					<?php esc_html_e( 'To view a list of site-wide accessibility errors and warnings, click on a check name in the table below. To quickly review and remediate issues, we recommend utilizing the Fast Track feature, which allows you to create global ignores and dismiss all instances of an issue across your entire site.', 'edacp' ); ?>
				<?php else : ?>
					<?php esc_html_e( 'To view a list of issues that have been ignored across your site, click on a check name in the table below. This will show you all individual instances of code that have been marked as ignored for that particular check, by whom, and when. To see global ignores, go to the "Global Ignores" tab, above.', 'edacp' ); ?>
				<?php endif; ?>
			</p>
		<?php endif; ?>

	<?php else : ?>
		<h2><?php esc_html_e( 'Ignored Issues', 'edacp' ); ?></h2>
		<?php if ( $post_query_var ) : ?>
			<?php echo ': ' . esc_html( get_the_title( $post_query_var ) ); ?>
		<?php endif; ?>
	<?php endif; ?>
</div>

<?php if ( ! $rule_query_var && ! $post_query_var ) : ?>
	<?php
	if ( 'accessibility_checker_issues' === $current_page ) {
		$ignore        = 0;
		$ignore_global = 0;
		$rules         = edacp_rules_count( $ignore, $ignore_global );
	} elseif ( 'accessibility_checker_ignored' === $current_page ) {
		$ignore        = 1;
		$ignore_global = 1;
		$rules         = edacp_rules_count( $ignore, $ignore_global );
	}
	?>

	<?php if ( $rules ) : ?>
		<table class="edacp-rules-results-table edacp-results-table widefat" cellspacing="0">
			<thead>
				<tr>
					<th scope="column">
						<?php esc_html_e( 'Check', 'edacp' ); ?>
					</th>
					<th class="edacp-rules-results-table-rule" scope="column">
						<?php esc_html_e( 'Type', 'edacp' ); ?>
					</th>
					<th class="edacp-rules-results-table-count" scope="column">
						<?php esc_html_e( 'Count', 'edacp' ); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th scope="column">
						<?php esc_html_e( 'Check', 'edacp' ); ?>
					</th>
					<th class="edacp-rules-results-table-rule" scope="column">
						<?php esc_html_e( 'Type', 'edacp' ); ?>
					</th>
					<th class="edacp-rules-results-table-count" scope="column">
						<?php esc_html_e( 'Count', 'edacp' ); ?>
					</th>
				</tr>
			</tfoot>
			<tbody>
				<?php $row_count = 0; ?>
				<?php foreach ( $rules as $rule ) : ?>
					<?php
					++$row_count;
					$table_class = ( 1 === $row_count % 2 ) ? ' alternate' : '';
					?>
					<tr scope="row" class="<?php echo esc_attr( $table_class ); ?>">
						<td>
							<a href="<?php echo esc_url( admin_url() ); ?>admin.php?page=<?php echo esc_attr( $current_page ); ?>&rule=<?php echo esc_attr( $rule['slug'] ); ?>">
								<?php echo esc_html( $rule['title'] ); ?>
							</a>
						</td>
						<td class="edacp-rules-results-table-rule">
							<?php $rule_type = ( 0 === (int) $rule['count'] ) ? 'passed' : $rule['rule_type']; ?>
							<span class="edacp-rules-results-table-rule-type-<?php echo esc_html( $rule_type ); ?>">
								<?php
								$rule_type = ( 'passed' === $rule_type && 'accessibility_checker_ignored' === $current_page )
									? __( 'No Ignores', 'edacp' )
									: $rule_type;
								echo esc_html( $rule_type );
								?>
							</span>
						</td>
						<td class="edacp-rules-results-table-count"><?php echo number_format( $rule['count'] ); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
<?php else : ?>
	<?php

	// database query.
	$parameters = array( $siteid, $ignore, $ignore_global );
	$where      = ( 1 === $ignore )
		? 'WHERE siteid = %d and (ignre = %d or ignre_global = %d)'
		: 'WHERE siteid = %d and (ignre = %d and ignre_global = %d)';

	if ( $post_query_var ) {
		array_push( $parameters, $post_query_var );
		$where .= ' and postid = %d';
	}
	if ( $type_query_var ) {
		array_push( $parameters, $type_query_var );
		$where .= ' and type = %s';
	}
	if ( $ruletype_query_var ) {
		array_push( $parameters, $ruletype_query_var );
		$where .= ' and ruletype = %s';
	}
	if ( $rule_query_var ) {
		array_push( $parameters, $rule_query_var );
		$where .= ' and rule = %s';
	}
	if ( $search_query_var ) {
		$wild = '%';
		$find = $search_query_var;
		$like = $wild . $wpdb->esc_like( $find ) . $wild;
		array_push( $parameters, $like );
		$where .= ' and object LIKE %s';
	}
	if ( EDAC_ANWW_ACTIVE ) {
		array_push( $parameters, 'link_blank' );
		$where .= ' and rule != %s';
	}

	array_push( $parameters, $log_limit );

	// Query.
	$prepared = $wpdb->prepare(
		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- safe variable used for table name, $where is prepared above, $current_orderby and $current_order are validated above.
		'SELECT id, postid, type, object, ruletype, rule, ignre, ignre_user, ignre_date, ignre_comment FROM ' . $table_name . ' ' . $where . ' ORDER BY ' . $current_orderby . ' ' . $current_order . ' LIMIT %d',
		$parameters
	);

	// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- The query is prepared above.
	$results = $wpdb->get_results( $prepared, ARRAY_A );

	// get screen options.
	$user          = get_current_user_id();
	$screen        = get_current_screen();
	$option        = $screen->get_option( 'per_page', 'option' );
	$post_per_page = get_user_meta( $user, $option, true );
	if ( empty( $post_per_page ) || $post_per_page < 1 ) {
		$post_per_page = $screen->get_option( 'per_page', 'default' );
	}

	// pagination and count.
	$total         = count( $results );
	$current_paged = isset( $_GET['paged'] ) ? abs( (int) $_GET['paged'] ) : 1;
	$offset        = ( $current_paged * $post_per_page ) - $post_per_page;

	/**
	 * Log Count
	 *
	 * @param int $offset offset number.
	 * @param int $post_per_page number of posts per page.
	 * @param int $total total number of posts.
	 * @return void
	 */
	function edacp_log_count( $offset, $post_per_page, $total ) {
		$first = ( 0 === $offset ) ? 1 : $offset;
		$last  = ( ( $offset + $post_per_page ) < $total ) ? $offset + $post_per_page : $total;
		printf(
			// translators: %1$d is the first item number, %2$d is the last item number, %3$d is the total number of items.
			esc_html__( '%1$d - %2$d of %3$d Issues', 'edacp' ),
			(int) $first,
			(int) $last,
			(int) $total
		);
	}

	/**
	 * Log Pagination
	 *
	 * @param int  $total total number of post.
	 * @param int  $post_per_page number of posts per page.
	 * @param bool $current_paged if is paged.
	 * @return void
	 */
	function edacp_log_pagination( $total, $post_per_page, $current_paged ) {
		?>
		<div class="edacp-log-pagination pagination-links">
			<?php
			if ( $total ) {
				echo wp_kses_post(
					paginate_links(
						array(
							'base'      => add_query_arg( 'paged', '%#%' ),
							'format'    => '',
							'prev_text' => __( '&laquo;', 'edacp' ),
							'next_text' => __( '&raquo;', 'edacp' ),
							'total'     => ceil( $total / $post_per_page ),
							'current'   => $current_paged,
							'type'      => 'list',
						)
					)
				);
			}
			?>
		</div>
		<?php
	}

	$filter_types     = array_unique( array_column( $results, 'type' ) );
	$filter_ruletypes = array_unique( array_column( $results, 'ruletype' ) );
	$filter_rules     = array_unique( array_column( $results, 'rule' ) );

	// update query results.
	$results = array_slice( $results, $offset, $post_per_page, true );
	?>

	<?php if ( $current_rule_info ) : ?>
		<div class="edacp-results-rule-header">
			<?php
			$rule_type_class = ( 'accessibility_checker_ignored' === $current_page )
				? 'ignore'
				: $current_rule_info['rule_type'];

			if ( 0 === $total ) {
				$rule_type_class .= ' edacp-results-rule-header-count-passed';
			}
			?>
			<div class="edacp-results-rule-header-count edacp-results-rule-header-count-<?php echo esc_attr( $rule_type_class ); ?>">
				<h2>
					<span>
						<?php
						$count = ( $results ) ? $total : 0;
						if ( $count >= $log_limit ) {
							printf(
								// translators: %s The log limit number.
								esc_html__( 'More than %s', 'edacp' ),
								number_format( $log_limit )
							);
						} else {
							echo number_format( $count );
						}
						?>
					</span>
					<?php if ( 'accessibility_checker_ignored' === $current_page ) : ?>
						<?php esc_html_e( 'Ignored', 'edacp' ); ?>
					<?php endif; ?>
					<?php echo esc_html( $current_rule_info['title'] ); ?>
					<?php
					// phpcs:ignore Generic.Commenting.Todo.TaskFound
					// TODO: Find a way to properly pluralize this.
					echo esc_html( $current_rule_info['rule_type'] );
					if ( 1 !== $count ) {
						echo 's';
					}
					?>
				</h2>
			</div>
			<div class="edacp-results-rule-header-description">
				<h3>
					<?php
					printf(
						// translators: %1$s is the rule title, %2$s is the rule type.
						esc_html__( 'About %1$s %2$s', 'edacp' ),
						esc_html( $current_rule_info['title'] ),
						esc_html( $current_rule_info['rule_type'] )
					);
					?>
				</h3>
				<?php if ( isset( $current_rule_info['summary'] ) ) : ?>
					<p>
						<?php echo wp_kses_post( $current_rule_info['summary'] ); ?>
					</p>
				<?php endif; ?>

				<?php
				global $wp_version;
				$days_active   = edac_days_active();
				$tool_tip_link = add_query_arg(
					array(
						'utm_source'       => 'accessibility-checker',
						'utm_medium'       => 'software',
						'utm_term'         => esc_attr( $current_rule_info['slug'] ),
						'utm_content'      => 'content-analysis',
						'utm_campaign'     => 'wordpress-general',
						'php_version'      => PHP_VERSION,
						'platform'         => 'wordpress',
						'platform_version' => $wp_version,
						'software'         => 'pro',
						'software_version' => EDACP_VERSION,
						'days_active'      => $days_active,
					),
					$current_rule_info['info_url']
				);
				?>
				<a href="<?php echo esc_url( $tool_tip_link ); ?>" class="edac-details-rule-information" target="_blank">
					<?php esc_html_e( 'More Detailed Documentation', 'edacp' ); ?>
				</a>

			</div>
			<?php if ( $count >= $log_limit ) : ?>
				<p class="edacp-results-rule-header-limit">
					<?php
					printf(
						// translators: %1$s The log limit number.
						esc_html__( 'Note: for performance reasons, a maximum of %1$s issues are shown here. As you resolve problems, more will show until there are fewer than %1$s left to resolve.', 'edacp' ),
						number_format( $log_limit )
					);
					?>
				</p>
			<?php endif; ?>
		</div>

	<?php endif; ?>

	<div class="tablenav top">

		<div class="alignleft actions bulkactions">
			<label for="bulk-action-selector-top" class="screen-reader-text">
				<?php esc_html_e( 'Select bulk action', 'edacp' ); ?>
			</label>
			<select name="action" class="edacp-bulk-ignore-select" id="bulk-action-selector-top">
				<option value="-1">
					<?php esc_html_e( 'Bulk actions', 'edacp' ); ?>
				</option>
				<?php if ( 'accessibility_checker_issues' === $current_page && $ignore_permission ) { ?>
					<option value="enable" class="hide-if-no-js">
						<?php esc_html_e( 'Ignore', 'edacp' ); ?>
					</option>
				<?php } ?>
				<?php if ( 'accessibility_checker_ignored' === $current_page && $ignore_permission ) { ?>
					<option value="disable" class="hide-if-no-js">
						<?php esc_html_e( 'Stop Ignoring', 'edacp' ); ?>
					</option>
				<?php } ?>
			</select>
			<input type="submit" class="edacp-bulk-ignore-submit button action" value="<?php esc_attr_e( 'Apply', 'edacp' ); ?>">
		</div>

		<div class="alignleft actions">
			<form action="<?php admin_url( 'admin.php' ); ?>">
				<input type="hidden" name="page" value="<?php echo esc_attr( $current_page ); ?>">
				<input type="hidden" name="post" value="<?php echo esc_attr( $post_query_var ); ?>">

				<?php if ( $filter_types ) : ?>
					<label for="filter-by-type" class="screen-reader-text">
						<?php esc_html_e( 'Filter by post type', 'edacp' ); ?>
					</label>
					<select name="type" id="filter-by-type">
						<option selected="selected" value="0">
							<?php esc_html_e( 'All Post Types', 'edacp' ); ?>
						</option>
						<?php foreach ( $filter_types as $filter_type ) : ?>
							<option
								<?php echo ( $type_query_var === $filter_type ) ? 'selected' : ''; ?>
								value="<?php echo esc_attr( $filter_type ); ?>"
							>
								<?php
								echo ( post_type_exists( $filter_type ) )
									? esc_html( get_post_type_object( $filter_type )->labels->name )
									: esc_html( $filter_type );
								?>
							</option>
						<?php endforeach; ?>
					</select>
				<?php endif; ?>

				<?php if ( $filter_ruletypes ) : ?>
					<label for="filter-by-ruletype" class="screen-reader-text">
						<?php esc_html_e( 'Filter by notice type', 'edacp' ); ?>
					</label>
					<select name="ruletype" id="filter-by-ruletype"<?php echo ( ! $post_query_var ) ? ' hidden' : ''; ?>>
						<option selected="selected" value="0">
							<?php esc_html_e( 'All Notice Types', 'edacp' ); ?>
						</option>
						<?php foreach ( $filter_ruletypes as $filter_ruletype ) : ?>
							<option
								<?php echo ( $ruletype_query_var === $filter_ruletype ) ? 'selected' : ''; ?>
								value="<?php echo esc_attr( $filter_ruletype ); ?>"
							>
								<?php echo esc_html( ucwords( $filter_ruletype ) ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				<?php endif; ?>

				<?php if ( $filter_rules ) : ?>
					<label for="filter-by-rule" class="screen-reader-text">
						<?php esc_html_e( 'Filter by issue', 'edacp' ); ?>
					</label>
					<select name="rule" id="filter-by-rule"<?php echo ( ! $post_query_var ) ? ' hidden' : ''; ?>>
						<option selected="selected" value="0">
							<?php esc_html_e( 'All Issues', 'edacp' ); ?>
						</option>
						<?php foreach ( $filter_rules as $filter_rule ) : ?>
							<option
								<?php echo ( $rule_query_var === $filter_rule ) ? 'selected' : ''; ?>
								value="<?php echo esc_attr( $filter_rule ); ?>"
							>
								<?php
								echo wp_kses(
									edac_filter_by_value( $rules, 'slug', $filter_rule )[0]['title'],
									array(
										'option' => array(
											'value'    => array(),
											'class'    => array(),
											'selected' => array(),
										),
									)
								);
								?>
							</option>
						<?php endforeach; ?>
					</select>
				<?php endif; ?>

				<label class="screen-reader-text" for="edacp-log-search-input">
					<?php esc_html_e( 'Enter a keyword to search by', 'edacp' ); ?>
				</label>
				<input type="search" id="edacp-log-search-input" name="s" value="<?php echo esc_attr( $search_query_var ); ?>" placeholder="<?php esc_attr_e( 'Search', 'edacp' ); ?>">

				<input type="submit" id="post-query-submit" class="button" value="<?php esc_attr_e( 'Filter', 'edacp' ); ?>">

				<?php
				$url_parameter      = $post_query_var ? 'post' : 'rule';
				$show_clear_filters = ( $post_query_var )
					? ( $type_query_var || $ruletype_query_var || $rule_query_var || $search_query_var )
					: ( $type_query_var || $search_query_var );
				?>
				<?php if ( $show_clear_filters ) : ?>
					<a
						class="button"
						href="<?php echo esc_url( admin_url() ); ?>admin.php?page=<?php echo esc_attr( $current_page ); ?>&<?php echo esc_attr( $url_parameter ); ?>=<?php echo esc_attr( $post_query_var ? $post_query_var : $rule_query_var ); ?>"
					>
						<?php esc_html_e( 'Clear Filters', 'edacp' ); ?>
					</a>
				<?php endif; ?>
			</form>
		</div>

		<h2 class="screen-reader-text">
			<?php esc_html_e( 'Pages list navigation', 'edacp' ); ?>
		</h2>
		<div class="tablenav-pages">
			<span class="displaying-num"><?php edacp_log_count( $offset, $post_per_page, $total ); ?></span>
			<?php edacp_log_pagination( $total, $post_per_page, $current_paged ); ?>
		</div>
		<br class="clear">

	</div>

	<table class="edacp-results-table widefat" cellspacing="0">
		<thead>
			<tr>
				<td id="cb" class="manage-column column-cb check-column">
					<label class="screen-reader-text" for="cb-select-all-1">
						<?php esc_html_e( 'Select All', 'edacp' ); ?>
					</label>
					<input id="cb-select-all-1" type="checkbox">
				</td>
				<th>
					<a href="<?php echo esc_url( $current_url ); ?>&orderby=postid&order=<?php echo ( 'asc' === $current_order ) ? 'desc' : 'asc'; ?>">
						<?php esc_html_e( 'Post Title', 'edacp' ); ?>
					</a>
				</th>
				<th>
					<a href="<?php echo esc_url( $current_url ); ?>&orderby=type&order=<?php echo ( 'asc' === $current_order ) ? 'desc' : 'asc'; ?>">
						<?php esc_html_e( 'Post Type', 'edacp' ); ?>
					</a>
				</th>
				<th><?php esc_html_e( 'Type', 'edacp' ); ?></th>
				<th><?php esc_html_e( 'Issue', 'edacp' ); ?></th>
				<th>
					<a href="<?php echo esc_url( $current_url ); ?>&orderby=object&order=<?php echo ( 'asc' === $current_order ) ? 'desc' : 'asc'; ?>">
						<?php esc_html_e( 'Affected Code', 'edacp' ); ?>
					</a>
				</th>
				<th><?php esc_html_e( 'Image', 'edacp' ); ?></th>
				<th><?php esc_html_e( 'Actions', 'edacp' ); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td id="cb" class="manage-column column-cb check-column">
					<label class="screen-reader-text" for="cb-select-all-1">
						<?php esc_html_e( 'Select All', 'edacp' ); ?>
					</label>
					<input id="cb-select-all-1" type="checkbox">
				</td>
				<th><?php esc_html_e( 'Post Title', 'edacp' ); ?></th>
				<th><?php esc_html_e( 'Post Type', 'edacp' ); ?></th>
				<th><?php esc_html_e( 'Type', 'edacp' ); ?></th>
				<th><?php esc_html_e( 'Issue', 'edacp' ); ?></th>
				<th><?php esc_html_e( 'Affected Code', 'edacp' ); ?></th>
				<th><?php esc_html_e( 'Image', 'edacp' ); ?></th>
				<th><?php esc_html_e( 'Actions', 'edacp' ); ?></th>
			</tr>
		</tfoot>
		<tbody>
			<?php if ( $results ) : ?>
				<?php $row_count = 0; ?>
				<?php foreach ( $results as $row ) : ?>
					<?php

					$row_id      = (int) $row['id'];
					$row_post_id = (int) $row['postid'];

					++$row_count;
					$table_class = ( 1 === $row_count % 2 ) ? ' alternate' : '';

					$post_link      = add_query_arg(
						array(
							'edac'       => $row_id,
							'edac_nonce' => wp_create_nonce( 'edac_highlight' ),
						),
						get_the_permalink( $row_post_id )
					);
					$edit_post_link = get_edit_post_link( $row_post_id );
					$post_title     = get_the_title( $row_post_id )
						? get_the_title( $row_post_id )
						: sprintf(
							// translators: %d is the post id.
							esc_html__( '(No Title: %d)', 'edacp' ),
							esc_html( $row_post_id )
						);
					$current_type     = esc_html( $row['type'] );
					$rule_type        = esc_html( $row['ruletype'] );
					$rule             = esc_html( $row['rule'] );
					$rule_name        = edac_filter_by_value( $rules, 'slug', $rule )[0]['title'];
					$affected_code    = esc_html( $row['object'] ) ? $row['object'] : '';
					$ignore           = (int) $row['ignre'];
					$ignore_class     = $ignore ? ' active' : '';
					$ignore_label     = $ignore ? esc_html__( 'Ignored', 'edacp' ) : esc_html__( 'Ignore', 'edacp' );
					$ignore_user      = (int) $row['ignre_user'];
					$ignore_user_info = get_userdata( $ignore_user );
					$ignore_username  = is_object( $ignore_user_info )
						// translators: %s is the username.
						? sprintf( __( '<strong>Ignored by:</strong> %s', 'edacp' ), $ignore_user_info->user_login )
						: '';
					$ignore_date = ( $row['ignre_date'] && '0000-00-00 00:00:00' !== $row['ignre_date'] )
						// translators: %s is the date.
						? sprintf( __( '<strong>Date:</strong> %s', 'edacp' ), gmdate( 'F j, Y g:i a', strtotime( esc_html( $row['ignre_date'] ) ) ) )
						: '';
					$ignore_comment      = esc_html( $row['ignre_comment'] );
					$ignore_action       = $ignore ? 'disable' : 'enable';
					$ignore_type         = $rule_type;
					$ignore_submit_label = $ignore
						? __( 'Stop Ignoring', 'edacp' )
						// translators: %s is the rule type.
						: sprintf( __( 'Ignore This %s', 'edacp' ), $rule_type );
					$ignore_comment_disabled = $ignore ? 'disabled' : '';

					// check for images and svgs in object code.
					$object_img      = null;
					$object_svg      = null;
					$object_img_html = str_get_html( htmlspecialchars_decode( $row['object'], ENT_QUOTES ) );
					if ( $object_img_html ) {
						$object_img_elements = $object_img_html->find( 'img' );
						$object_svg_elements = $object_img_html->find( 'svg' );
						if ( $object_img_elements ) {
							foreach ( $object_img_elements as $element ) {
								$object_img = $element->getAttribute( 'src' );
								if ( $object_img ) {
									break;
								}
							}
						} elseif ( $object_svg_elements ) {
							foreach ( $object_svg_elements as $element ) {
								$object_svg = $element;
								break;
							}
						}
					}

					$image_html = false;
					if ( $object_img ) {
						$image_html .= '<img src="' . $object_img . '" alt="image for row ' . esc_attr( $row_id ) . '" />';
					} elseif ( $object_svg ) {
						$image_html = $object_svg;
					}

					?>
					<div>
						<tr class="<?php echo esc_attr( $table_class ); ?>">

							<th scope="row" class="check-column">
								<label class="screen-reader-text" for="cb-select-<?php echo esc_attr( $row_id ); ?>">
									<?php
									printf(
										// translators: %s is the post title.
										esc_html__( 'Select %s', 'edacp' ),
										esc_html( $post_title )
									);
									?>
								</label>
								<input
									class="edacp-bulk-ignore-checkbox"
									id="cb-select-<?php echo esc_attr( $row_id ); ?>"
									type="checkbox"
									name="post[]"
									value="<?php echo esc_attr( $row_id ); ?>"
								>
								<div class="locked-indicator">
									<span class="locked-indicator-icon" aria-hidden="true"></span>
									<span class="screen-reader-text">
										<?php
										printf(
											// translators: %s is the post title.
											esc_html__( '%s is locked', 'edacp' ),
											esc_html( $post_title )
										);
										?>
									</span>
								</div>
							</th>

							<td class="edacp-results-table-post-title">
								<a href="<?php echo esc_url( admin_url() ); ?>/admin.php?page=<?php echo esc_attr( $current_page ); ?>&post=<?php echo esc_attr( $row_post_id ); ?>">
									<?php echo esc_html( $post_title ); ?>
								</a>
							</td>
							<td class="edacp-results-table-post-type">
								<?php echo esc_html( $current_type ); ?>
							</td>
							<td class="edacp-results-table-rule-type">
								<?php echo esc_html( $rule_type ); ?>
							</td>
							<td class="edacp-results-table-rule">
								<?php echo esc_html( $rule_name ); ?>
							</td>
							<td class="edacp-results-table-code">
								<?php echo '<code>' . esc_html( $affected_code ) . '</code>'; ?>
							</td>

							<td class="edacp-fast-track-table-detail">
								<?php if ( $image_html ) : ?>
									<div class="edacp-fast-track-table-image">
										<?php echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</div>
								<?php else : ?>
									<div></div>
								<?php endif; ?>
							</td>

							<td class="edacp-results-table-actions">
								<a class="edac-details-rule-records-record-actions-view" href="<?php echo esc_url( $post_link ); ?>" target="_blank">
									<?php
									printf(
										// translators: %s is the icon.
										esc_html__( '%s View', 'edacp' ),
										'<span class="dashicons dashicons-visibility"></span>'
									);
									?>
								</a>
								<br>
								<a class="edac-details-rule-records-record-actions-view" href="<?php echo esc_url( $edit_post_link ); ?>">
									<?php
									printf(
										// translators: %s is the icon.
										esc_html__( '%s Edit', 'edacp' ),
										'<span class="dashicons dashicons-edit-large"></span>'
									);
									?>
								</a>
								<br>
								<button class="edac-details-rule-records-record-actions-ignore<?php echo esc_attr( $ignore_class ); ?>" data-id="<?php echo esc_attr( $row_id ); ?>">
									<svg version="1.0" xmlns="http://www.w3.org/2000/svg"
									width="568.000000pt" height="568.000000pt" viewBox="0 0 568.000000 568.000000"
									preserveAspectRatio="xMidYMid meet">

									<g transform="translate(0.000000,568.000000) scale(0.100000,-0.100000)"
									fill="#000000" stroke="none">
									<path d="M2558 5585 c-289 -49 -525 -173 -735 -387 -166 -168 -277 -338 -363
									-557 -89 -224 -118 -380 -130 -696 -17 -428 -40 -640 -106 -964 -86 -426 -235
									-825 -399 -1072 -37 -55 -104 -136 -155 -187 -103 -102 -135 -160 -125 -222
									12 -73 68 -126 200 -191 l79 -39 27 27 c15 16 691 842 1504 1837 l1477 1809
									-38 54 c-292 424 -793 662 -1236 588z"/>
									<path d="M4508 5323 c-36 -43 -930 -1138 -1988 -2433 -1057 -1295 -1931 -2364
									-1942 -2376 -18 -21 -18 -21 112 -127 71 -59 134 -107 140 -107 5 0 883 1070
									1952 2377 1068 1308 1970 2412 2005 2454 l63 76 -130 107 c-72 58 -134 106
									-139 106 -5 0 -38 -35 -73 -77z"/>
									<path d="M3013 2494 l-1102 -1349 912 -3 c585 -2 971 1 1077 8 590 39 965 171
									995 350 10 62 -22 120 -125 222 -164 163 -300 406 -406 726 -132 397 -207 783
									-237 1221 l-12 173 -1102 -1348z"/>
									<path d="M2220 939 c0 -32 73 -142 135 -204 112 -112 241 -165 400 -165 159 0
									288 53 400 165 63 62 135 171 135 204 0 8 -150 11 -535 11 -385 0 -535 -3
									-535 -11z"/>
									</g>
									</svg>
									<span class="edac-details-rule-records-record-actions-ignore-label">
										<?php echo esc_html( $ignore_label ); ?>
									</span>
								</button>
							</td>
						</tr>
						<tr class="<?php echo esc_attr( $table_class ); ?>">
							<td colspan="7">
								<div id="edac-details-rule-records-record-<?php echo esc_attr( $row_id ); ?>" class="edac-details-rule-records-record">
									<div class="edac-details-rule-records-record-ignore">
										<div class="edac-details-rule-records-record-ignore-info">
											<span class="edac-details-rule-records-record-ignore-info-user">
												<?php echo esc_html( $ignore_username ); ?>
											</span>
											<span class="edac-details-rule-records-record-ignore-info-date">
												<?php echo esc_html( $ignore_date ); ?>
											</span>
										</div>

										<?php if ( true === $ignore_permission || ! empty( $ignore_comment ) ) : ?>
											<label for="edac-details-rule-records-record-ignore-comment-<?php echo esc_attr( $row_id ); ?>">
												<?php esc_html_e( 'Comment', 'edacp' ); ?>
											</label>
											<br>
										<?php endif; ?>

										<?php if ( true === $ignore_permission || ! empty( $ignore_comment ) ) : ?>
											<textarea
												rows="4"
												class="edac-details-rule-records-record-ignore-comment"
												id="edac-details-rule-records-record-ignore-comment-<?php echo esc_attr( $row_id ); ?>"
												<?php echo esc_attr( $ignore_comment_disabled ); ?>
											>
												<?php echo esc_html( $ignore_comment ); ?>
											</textarea>
										<?php endif; ?>

										<?php if ( true === $ignore_permission ) : ?>
											<button
												class="edac-details-rule-records-record-ignore-submit"
												data-id="<?php echo esc_attr( $row_id ); ?>"
												data-action="<?php echo esc_attr( $ignore_action ); ?>"
												data-type="<?php echo esc_attr( $ignore_type ); ?>"
											>
												<span class="dashicons">
													<svg version="1.0" xmlns="http://www.w3.org/2000/svg"
													width="568.000000pt" height="568.000000pt" viewBox="0 0 568.000000 568.000000"
													preserveAspectRatio="xMidYMid meet">

													<g transform="translate(0.000000,568.000000) scale(0.100000,-0.100000)"
													fill="#000000" stroke="none">
													<path d="M2558 5585 c-289 -49 -525 -173 -735 -387 -166 -168 -277 -338 -363
													-557 -89 -224 -118 -380 -130 -696 -17 -428 -40 -640 -106 -964 -86 -426 -235
													-825 -399 -1072 -37 -55 -104 -136 -155 -187 -103 -102 -135 -160 -125 -222
													12 -73 68 -126 200 -191 l79 -39 27 27 c15 16 691 842 1504 1837 l1477 1809
													-38 54 c-292 424 -793 662 -1236 588z"/>
													<path d="M4508 5323 c-36 -43 -930 -1138 -1988 -2433 -1057 -1295 -1931 -2364
													-1942 -2376 -18 -21 -18 -21 112 -127 71 -59 134 -107 140 -107 5 0 883 1070
													1952 2377 1068 1308 1970 2412 2005 2454 l63 76 -130 107 c-72 58 -134 106
													-139 106 -5 0 -38 -35 -73 -77z"/>
													<path d="M3013 2494 l-1102 -1349 912 -3 c585 -2 971 1 1077 8 590 39 965 171
													995 350 10 62 -22 120 -125 222 -164 163 -300 406 -406 726 -132 397 -207 783
													-237 1221 l-12 173 -1102 -1348z"/>
													<path d="M2220 939 c0 -32 73 -142 135 -204 112 -112 241 -165 400 -165 159 0
													288 53 400 165 63 62 135 171 135 204 0 8 -150 11 -535 11 -385 0 -535 -3
													-535 -11z"/>
													</g>
													</svg>
												</span>
												<span class="edac-details-rule-records-record-ignore-submit-label">
													<?php echo esc_html( $ignore_submit_label ); ?>
												</span>
											</button>
										<?php endif; ?>

										<?php if ( false === $ignore_permission && false === $ignore ) : ?>
											<?php esc_html_e( 'Your user account doesn\'t have permission to ignore this issue.', 'edacp' ); ?>
										<?php endif; ?>
									</div>
								</div>
							</td>
						</tr>
					</div>
				<?php endforeach; ?>
			<?php else : ?>
				<tr>
					<td colspan="7"><?php esc_html_e( 'No issues found', 'edacp' ); ?></td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>

	<div class="tablenav bottom">
		<div class="tablenav-pages">
			<span class="displaying-num">
				<?php edacp_log_count( $offset, $post_per_page, $total ); ?>
			</span>
			<?php edacp_log_pagination( $total, $post_per_page, $current_paged ); ?>
		</div>
		<br class="clear">
	</div>
<?php endif; ?>