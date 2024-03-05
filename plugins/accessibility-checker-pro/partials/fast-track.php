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

// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- nonce not required.
$current_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : null;
// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- nonce not required.
$type_query_var = isset( $_GET['type'] ) ? sanitize_text_field( $_GET['type'] ) : null;
$post_types     = get_option( 'edac_post_types' );
if ( ! $type_query_var && is_array( $post_types ) ) {
	$type_query_var = in_array( 'page', $post_types, true )
		? 'page'
		: $post_types[0];
}
$type_query_var_name = null;
if ( post_type_exists( $type_query_var ) ) {
	$type_query_var_name = get_post_type_object( $type_query_var )->labels->name;
}

$allowed_select_protocols = array(
	'label'  => array(
		'for'   => array(),
		'class' => array(),
	),
	'option' => array(
		'name'     => array(),
		'value'    => array(),
		'class'    => array(),
		'selected' => array(),
	),
	'select' => array(
		'name'  => array(),
		'class' => array(),
	),
);

/**
 * Undocumented function
 *
 * @param array  $post_types post types.
 * @param string $type_query_var query type.
 * @param string $current_tab current tab.
 * @return string
 */
function edacp_fast_track_type_select( $post_types, $type_query_var, $current_tab ) {
	ob_start();
	?>
	<label for="edacp-fast-track-type-select" class="screen-reader-text">Filter by post type</label>
	<select name="type" class="edacp-fast-track-type-select">
		<?php
		if ( $post_types ) {
			foreach ( $post_types as $post_type ) {
				$label = post_type_exists( $post_type )
					? get_post_type_object( $post_type )->labels->name
					: $post_type;

				// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- nonce not required.
				$current_page = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';
				$selected     = ( $type_query_var === $post_type ) ? 'selected' : '';
				?>

				<option
					<?php echo esc_attr( $selected ); ?>
					value="<?php echo esc_url( admin_url( "admin.php?page={$current_page}&tab={$current_tab}&type={$post_type}" ) ); ?>"
				>
					<?php echo esc_html( $label ); ?>
				</option>
				<?php
			}
		}
		?>
	</select>
	<?php
	return ob_get_clean();
}
?>

<div class="edacp-fast-track">

	<?php
	global $wpdb;
	$table_name        = $wpdb->prefix . 'accessibility_checker';
	$siteid            = get_current_blog_id();
	$ignore_permission = true;
	if ( has_filter( 'edac_ignore_permission' ) ) {
		$ignore_permission = apply_filters( 'edac_ignore_permission', $ignore_permission );
	}

	// database query.
	$where = '';
	if ( 'fast_track' === $current_tab ) {
		$parameters = array( $table_name, $siteid, 0, 0, $type_query_var );
		$where      = 'WHERE siteid = %d and ignre_global = %d and ignre = %d and type = %s';
	} elseif ( 'global' === $current_tab ) {
		$parameters = array( $table_name, $siteid, 1, 1, $type_query_var );
		$where      = 'WHERE siteid = %d and ignre_global = %s and ignre = %d and type = %s';
	}
	if ( EDAC_ANWW_ACTIVE ) {
		array_push( $parameters, 'link_blank' );
		$where .= ' and rule != %s';
	}

	array_push( $parameters, $log_limit );

	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
	$results = $wpdb->get_results(
		// phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.ReplacementsWrongNumber -- number of replacements is correct if we take $where into account.
		$wpdb->prepare(
			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			'SELECT id, postid, type, object, ruletype, rule, ignre, ignre_user, ignre_date, ignre_comment FROM %i ' . $where . ' ORDER BY ruletype ASC LIMIT %d',
			$parameters
		),
		ARRAY_A
	);

	// set up fast track array output.
	if ( $results ) {

		// build new array.
		$fast_track_results = array();

		// get objects.
		$objects = array_column( $results, 'object' );

		if ( $objects ) {

			// uniaue objects.
			$objects = array_unique( $objects );

			foreach ( $objects as $object ) {

				// find issues that match object.
				$issues = edac_filter_by_value( $results, 'object', $object );

				if ( $issues ) {

					$rules        = array_column( $issues, 'rule' );
					$rules_unique = array_unique( $rules );
					$rules_count  = array_count_values( $rules ); // count number of each rule.
					$array        = array(
						'code'        => esc_html( $object ),
						'rules_count' => count( $rules_unique ),
						'pages'       => array(),
					);

					if ( $rules_unique ) {

						$rules_array    = array();
						$register_rules = edac_register_rules();
						$all_pages      = array();

						foreach ( $rules_unique as $rule ) {

							$rule_info   = edac_filter_by_value( $register_rules, 'slug', $rule );
							$found_pages = edac_filter_by_value( $issues, 'rule', $rule );
							$rule_array  = array(
								'rule_slug'   => $rule,
								'rule_title'  => $rule_info[0]['title'],
								'info_url'    => $rule_info[0]['info_url'],
								'slug'        => $rule_info[0]['slug'],
								'pages_count' => $rules_count[ $rule ],
								'id'          => $found_pages[0]['id'],
							);

							if ( $found_pages ) {
								$pages_array = array();
								foreach ( $found_pages as $found_page ) {
									$view_link     = add_query_arg(
										array(
											'edac'       => $found_page['id'],
											'edac_nonce' => wp_create_nonce( 'edac_highlight' ),
										),
										get_the_permalink( $found_page['postid'] )
									);
									$pages_array[] = array(
										'id'        => $found_page['postid'],
										'title'     => get_the_title( $found_page['postid'] ),
										'url'       => get_the_permalink( $found_page['postid'] ),
										'view_link' => $view_link,
									);
									$all_pages[]   = $found_page['postid'];
								}
							}
							$rule_array['pages'] = $pages_array;

							$rules_array[] = $rule_array;
						}

						$array['rules'] = $rules_array;

					}

					// gets a unique count for all pages with issues.
					$array['pages_count'] = count( array_unique( $all_pages ) );

				}

				$fast_track_results[] = $array;

			}
		}

		// sort by page count first and then by rules count.
		$custom_order = array(
			'pages_count' => 'desc',
			'rules_count' => 'desc',
		);
		usort(
			$fast_track_results,
			function ( $a, $b ) use ( $custom_order ) {
				$t = array(
					true  => -1,
					false => 1,
				);
				$r = true;
				$k = 1;
				foreach ( $custom_order as $key => $value ) {
					$k = ( 'asc' === $value ) ? 1 : -1;
					$r = ( $a[ $key ] < $b[ $key ] );
					if ( $a[ $key ] !== $b[ $key ] ) {
						return $t[ $r ] * $k;
					}
				}
				return $t[ $r ] * $k;
			}
		);

	}

	// get only affected code.
	$objects = array();
	if ( $results ) {
		foreach ( $results as $result ) {
			$objects[] = $result['object'];
		}
	}

	// find duplicates.
	$dups = array();
	foreach ( array_count_values( $objects ) as $val => $c ) {
		if ( $c > 1 ) {
			$dups[] = $val;
		}
	}

	// update results.
	$results = $dups;

	// get screen options.
	$user          = get_current_user_id();
	$screen        = get_current_screen();
	$option        = $screen->get_option( 'per_page', 'option' );
	$post_per_page = get_user_meta( $user, $option, true );
	if ( empty( $post_per_page ) || $post_per_page < 1 ) {
		$post_per_page = $screen->get_option( 'per_page', 'default' );
	}

	// pagination and count.
	$total = isset( $fast_track_results ) ? count( $fast_track_results ) : 0;
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- nonce not required.
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
			/* translators: %1$d is the first item number, %2$d is the last item number, %3$d is the total number of items */
			esc_html__( '%1$d - %2$d of %3$d Issues', 'edacp' ),
			(int) $first,
			(int) $last,
			(int) $total
		);
	}

	/**
	 * Log Pagination
	 *
	 * @param int  $total total number of posts.
	 * @param int  $post_per_page number of posts per page.
	 * @param bool $current_paged current page.
	 * @return void
	 */
	function edacp_log_pagination( $total, $post_per_page, $current_paged ) {
		?>
		<div class="edacp-log-pagination pagination-links">
			<?php
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
			?>
		</div>
		<?php
	}

	// update query results.
	if ( isset( $fast_track_results ) ) {
		$fast_track_results = array_slice( $fast_track_results, $offset, $post_per_page, true );
	} else {
		$fast_track_results = null;
	}
	?>

	<?php if ( 'fast_track' === $current_tab ) : ?>
		<h2>
			<?php
			if ( $type_query_var_name ) {
				printf(
					// translators: %s is the post type name.
					esc_html__( 'Fast Track: %s', 'edacp' ),
					esc_html( $type_query_var_name )
				);
			} else {
				esc_html_e( 'Fast Track', 'edacp' );
			}
			?>
		</h2>

		<p>
			<?php esc_html_e( 'Fast Track allows you to quickly review and fix or ignore HTML snippets that are flagging errors and warnings on your website all at once. Snippets are ordered by the number of pages affected and the number of failing checks so you can see elements that may have the biggest impact on your website\'s overall accessibility.', 'edacp' ); ?>
		</p>

		<p>
			<?php esc_html_e( 'What To Do: Click "Review Issues" to see the flagged errors or warnings and which specific page(s) they were on. Either fix the issue or create a Global Ignore to never be warned about this code snippet/issue again if you have determined it is not really an accessibility problem.', 'edacp' ); ?>
		</p>
	<?php elseif ( 'global' === $current_tab ) : ?>
		<h2>
			<?php
			if ( $type_query_var_name ) {
				printf(
					// translators: %s is the post type name.
					esc_html__( 'Global Ignores: %s', 'edacp' ),
					esc_html( $type_query_var_name )
				);
			} else {
				esc_html_e( 'Global Ignores', 'edacp' );
			}
			?>
		</h2>

		<p>
			<?php esc_html_e( 'Global Ignores are errors or warnings that have been bulk ignored using the Fast Track feature. These items act as rules that will stop the specific code snippet from being flagged for the same error or warning if new pages are added in the future.', 'edacp' ); ?>
		</p>
	<?php endif; ?>

	<div class="tablenav top">

		<div class="alignleft actions">
			<?php
			echo wp_kses( edacp_fast_track_type_select( $post_types, $type_query_var, $current_tab ), $allowed_select_protocols );
			?>
		</div>

		<h2 class="screen-reader-text"><?php esc_html_e( 'Pages list navigation', 'edacp' ); ?></h2>
		<div class="tablenav-pages">
			<span class="displaying-num"><?php edacp_log_count( $offset, $post_per_page, $total ); ?></span>
			<?php edacp_log_pagination( $total, $post_per_page, $current_paged ); ?>
		</div>
		<br class="clear">

	</div>

	<table class="edacp-fast-track-table widefat" cellspacing="0">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Affected Code', 'edacp' ); ?></th>
				<th><?php esc_html_e( 'Image', 'edacp' ); ?></th>
				<th><?php esc_html_e( 'Affected Pages', 'edacp' ); ?></th>
				<th><?php esc_html_e( 'Failing checks', 'edacp' ); ?></th>
				<th><?php esc_html_e( 'Actions', 'edacp' ); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th><?php esc_html_e( 'Affected Code', 'edacp' ); ?></th>
				<th><?php esc_html_e( 'Image', 'edacp' ); ?></th>
				<th><?php esc_html_e( 'Affected Pages', 'edacp' ); ?></th>
				<th><?php esc_html_e( 'Failing checks', 'edacp' ); ?></th>
				<th><?php esc_html_e( 'Actions', 'edacp' ); ?></th>
			</tr>
		</tfoot>
		<tbody>
			<?php
			if ( $fast_track_results ) {

				$row_count = 0;
				foreach ( $fast_track_results as $key => $row ) {

					++$row_count;
					$table_class = ( 1 === $row_count % 2 ) ? ' alternate' : '';
					?>

					<tr class="<?php echo esc_attr( $table_class ); ?>" data-row-id="<?php echo esc_attr( $key ); ?>">
						<td class="edacp-fast-track-table-code">
							<code><?php echo esc_html( $row['code'] ); ?></code>
						</td>
						<td class="edacp-fast-track-table-detail">
					<?php

					// check for images and svgs in object code.
					$object_img      = null;
					$object_svg      = null;
					$object_img_html = str_get_html( htmlspecialchars_decode( $row['code'], ENT_QUOTES ) );
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
						$image_alt_txt = sprintf(
							/* translators: %s is the row name. */
							esc_html__( 'image for row %s', 'edacp' ),
							esc_attr( $key )
						);
						$image_html .= '<img src="' . $object_img . '" alt="' . $image_alt_txt . '" />';
					} elseif ( $object_svg ) {
						$image_html = $object_svg;
					}

					if ( $image_html ) {
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo '<div class="edacp-fast-track-table-image">' . $image_html . '</div>';
					} else {
						echo '<div></div>';
					}
					?>
						</td>
						<td class="edacp-fast-track-table-pages-count">
							<?php echo esc_html( $row['pages_count'] ); ?>
						</td>
						<td class="edacp-fast-track-table-rules-count">
							<?php echo esc_html( $row['rules_count'] ); ?>
						</td>
						<td class="edacp-fast-track-table-actions">
							<button class="edacp-fast-track-table-actions-edit" data-id="<?php echo esc_attr( $key ); ?>">
								<span class="dashicons dashicons-arrow-down-alt2"></span> <span><?php esc_html_e( 'Review Issues', 'edacp' ); ?></span>
							</button>
							<br>
						</td>
					</tr>

					<tr class="<?php echo esc_attr( $table_class ); ?>" data-row-id="<?php echo esc_attr( $key ); ?>">
						<td colspan="7">
							<?php if ( $row['rules'] ) : ?>
								<div id="edacp-fast-track-table-rules-<?php echo esc_attr( $key ); ?>" class="edacp-fast-track-table-rules">
									<table class="edacp-fast-track-table-rules-table" cellspacing="0">
										<thead>
											<tr>
												<th><?php esc_html_e( 'Check', 'edacp' ); ?></th>
												<th><?php esc_html_e( 'Affected Pages', 'edacp' ); ?></th>
												<th><?php esc_html_e( 'Pages', 'edacp' ); ?></th>
												<?php
												// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- nonce not required.
												if ( isset( $_GET['page'] ) && 'accessibility_checker_ignored' === $_GET['page'] ) :
													?>
													<th><?php esc_html_e( 'Ignored By', 'edacp' ); ?></th>
													<th><?php esc_html_e( 'Date', 'edacp' ); ?></th>
												<?php endif; ?>
												<th><?php esc_html_e( 'Action', 'edacp' ); ?></th>
											</tr>
										</thead>
										<?php
										foreach ( $row['rules'] as $rule_key => $rule ) {

											$table_name                  = $wpdb->prefix . 'accessibility_checker_global_ignores';
											$global_ignore_results       = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
												$wpdb->prepare(
													'SELECT rule, object, created, user FROM %i where rule = %s and object = %s and siteid = %d',
													$table_name,
													$rule['rule_slug'],
													esc_attr( $row['code'] ),
													$siteid
												),
												ARRAY_A
											);
											$global_ignore_button_text   = $global_ignore_results
												? esc_html__( 'Stop Global Ignore', 'edacp' )
												: esc_html__( 'Global Ignore', 'edacp' );
											$global_ignore_button_class  = $global_ignore_results ? ' active' : '';
											$global_ignore_button_action = $global_ignore_results ? 'disable' : 'enable';

											// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- nonce not required.
											if ( isset( $_GET['page'] ) && 'accessibility_checker_ignored' === $_GET['page'] ) {
												$ignore_user      = $global_ignore_results ? (int) $global_ignore_results[0]['user'] : null;
												$ignore_user_info = $ignore_user ? get_userdata( $ignore_user ) : null;
												$ignore_username  = is_object( $ignore_user_info ) ? $ignore_user_info->user_login : '';
												$ignore_date      = ( isset( $global_ignore_results[0]['created'] ) && $global_ignore_results[0]['created'] && '0000-00-00 00:00:00' !== $global_ignore_results[0]['created'] ) ? gmdate( 'F j, Y g:i a', strtotime( esc_html( $global_ignore_results[0]['created'] ) ) ) : '';
											}

											// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
											$ids = $wpdb->get_col(
												$wpdb->prepare(
													'SELECT id FROM %i where rule = %s and object = %s and siteid = %d',
													$wpdb->prefix . 'accessibility_checker',
													$rule['rule_slug'],
													esc_attr( $row['code'] ),
													$siteid
												)
											);

											global $wp_version;
											$days_active   = edac_days_active();
											$tool_tip_link = add_query_arg(
												array(
													'utm_source'   => 'accessibility-checker',
													'utm_medium'   => 'software',
													'utm_term'     => esc_attr( $rule['slug'] ),
													'utm_content'  => 'content-analysis',
													'utm_campaign' => 'wordpress-general',
													'php_version'  => PHP_VERSION,
													'platform'     => 'wordpress',
													'platform_version' => $wp_version,
													'software'     => 'free',
													'software_version' => EDAC_VERSION,
													'days_active'  => $days_active,
												),
												$rule['info_url']
											);
											?>

											<tr>
												<td class="edacp-fast-track-table-rules-table-rule">
													<?php echo esc_html( $rule['rule_title'] ); ?>
													<a
														href="<?php echo esc_url( $tool_tip_link ); ?>"
														class="edacp-fast-track-table-rules-table-rule-information"
														target="_blank"
													>
														<span class="dashicons dashicons-info"></span>
													</a>
												</td>
												<td class="edacp-fast-track-table-rules-table-page-count">
													<?php echo esc_html( $rule['pages_count'] ); ?>
												</td>
												<td class="edacp-fast-track-table-rules-table-pages">
													<?php if ( $rule['pages'] ) : ?>

														<div
															id="edacp-fast-track-table-rules-table-pages-list-<?php echo esc_attr( $key . $rule_key ); ?>"
															class="edacp-fast-track-table-rules-table-pages-list"
														>
															<ul>
																<?php foreach ( $rule['pages'] as $affected_page ) : ?>
																	<li>
																		<?php echo esc_html( $affected_page['title'] ); ?>
																		<p>
																			<small>
																				<span class="dashicons dashicons-admin-links"></span>
																				<span class="screen-reader-text">
																					<?php echo esc_url( $affected_page['url'] ); ?>
																				</span>
																				<?php echo esc_url( $affected_page['url'] ); ?>
																			</small>
																		</p>
																		<a href="<?php echo esc_url( get_edit_post_link( $affected_page['id'] ) ); ?>"><?php esc_html_e( 'Edit', 'edacp' ); ?></a> | <a href="<?php echo esc_url( $affected_page['view_link'] ); ?>" title="<?php esc_attr_e( 'Go to page', 'edacp' ); ?>"><?php esc_html_e( 'View', 'edacp' ); ?></a>
																	</li>
																<?php endforeach; ?>
															</ul>
														</div>

														<button
															class="edacp-fast-track-table-rules-table-pages-view"
															data-id="<?php echo esc_attr( $key . $rule_key ); ?>"
														>
															<span class="dashicons dashicons-visibility"></span> <span><?php esc_html_e( 'View Pages', 'edacp' ); ?></span>
														</button>

													<?php endif; ?>
												</td>

												<?php
												// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- nonce not required.
												if ( isset( $_GET['page'] ) && 'accessibility_checker_ignored' === $_GET['page'] ) :
													?>
													<td class="edacp-fast-track-table-rules-table-user"><?php echo esc_html( $ignore_username ); ?></td>
													<td class="edacp-fast-track-table-rules-table-date"><?php echo esc_html( $ignore_date ); ?></td>
												<?php endif; ?>
												<td class="edacp-fast-track-table-rules-table-actions">
													<button
														class="edacp-fast-track-table-rules-table-actions-ignore<?php echo esc_attr( $global_ignore_button_class ); ?>"
														data-ids="<?php echo esc_attr( wp_json_encode( $ids ) ); ?>"
														data-id="<?php echo esc_attr( $rule['id'] ); ?>"
														data-action="<?php echo esc_attr( $global_ignore_button_action ); ?>"
														data-row-id="<?php echo esc_attr( $key ); ?>"
													>
														<?php
														if ( defined( 'EDAC_DEBUG' ) ) {
															?>
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
															<?php
														}
														?>
														<span class="edacp-fast-track-table-rules-table-actions-ignore-label">
															<?php echo esc_html( $global_ignore_button_text ); ?>
														</span>
													</button>
												</td>
											</tr>
											<?php
										}
										?>
									</table>
								</div>
							<?php endif; ?>
						</td>
					</tr>
					<?php
				}
			}
			?>
		</tbody>
	</table>

	<div class="tablenav bottom">
		<div class="alignleft actions">
			<?php echo wp_kses( edacp_fast_track_type_select( $post_types, $type_query_var, $current_tab ), $allowed_select_protocols ); ?>
		</div>
		<div class="tablenav-pages">
			<span class="displaying-num"><?php edacp_log_count( $offset, $post_per_page, $total ); ?></span>
			<?php edacp_log_pagination( $total, $post_per_page, $current_paged ); ?>
		</div>
		<br class="clear">
	</div>

</div>
