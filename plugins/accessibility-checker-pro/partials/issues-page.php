<?php
/**
 * Accessibility Checker pluign file.
 *
 * @package Accessibility_Checker
 */

?>
<div class="wrap edacp-issues">

	<?php
	// set up tab items.
	$ignore_tab_items = array(
		array(
			'slug'  => '',
			'label' => esc_html__( 'Individual Open Issues', 'edacp' ),
			'order' => 1,
		),
		array(
			'slug'  => 'fast_track',
			'label' => esc_html__( 'Fast Track', 'edacp' ),
			'order' => 2,
		),
	);
	// filter settings tab items.
	$ignore_tab_items = apply_filters( 'edacp_filter_ignore_log_tab_items', $ignore_tab_items );

	// sort settings tab items.
	if ( is_array( $ignore_tab_items ) ) {
		usort(
			$ignore_tab_items,
			function ( $a, $b ) {
				if ( $a['order'] < $b['order'] ) {
					return -1;
				}
				if ( $a['order'] === $b['order'] ) {
					return 0;
				}
				return 1;
			}
		);
	}

	// Get the active tab from the $_GET param.
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$current_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : null;
	$current_tab = ( array_search( $current_tab, array_column( $ignore_tab_items, 'slug' ), true ) !== false ) ? $current_tab : null;
	?>

	<p class="edacp-issues-title"><?php echo esc_html( get_admin_page_title() ); ?></p>

	<?php if ( $ignore_tab_items ) : ?>
		<nav class="nav-tab-wrapper">
			<?php foreach ( $ignore_tab_items as $settings_tab_item ) : ?>
				<?php
				$slug      = $settings_tab_item['slug'] ? $settings_tab_item['slug'] : null;
				$query_var = $slug ? '&tab=' . $slug : '';
				$label     = $settings_tab_item['label'];
				?>
				<a
					href="?page=accessibility_checker_issues<?php echo esc_html( $query_var ); ?>"
					class="nav-tab<?php echo ( $current_tab === $slug ) ? ' nav-tab-active' : ''; ?>"
				>
					<?php echo esc_html( $label ); ?>
				</a>
			<?php endforeach; ?>
		</nav>
	<?php endif; ?>

	<?php
	if ( null === $current_tab ) {
		include __DIR__ . '/log.php';
	}

	if ( 'fast_track' === $current_tab ) {
		include __DIR__ . '/fast-track.php';
	}
	?>

</div>
