<?php
/**
 * Network Admin Logs
 *
 * @since 1.0.0
 * @package GoodBids
 */

use GoodBids\Network\Logs;
?>
<div class="wrap <?php echo esc_attr( Logs::PAGE_SLUG ); ?>">
	<h2><?php esc_html_e( 'Logs', 'goodbids' ); ?></h2>

	<?php goodbids()->sites->loop(
		function () {
			$log_files = goodbids()->network->logs->get_log_files();

			if ( empty( $log_files ) ) {
				return;
			}

			$total_pages = goodbids()->network->logs->get_total_pages();

			printf(
				'<h3>%s</h3>',
				esc_html( get_bloginfo( 'name' ) )
			);

			?>
			<ul class="gb-log-files">
				<?php foreach ( $log_files as $key => $log_file ) : ?>
					<li>
						<a href="<?php echo esc_url( add_query_arg( Logs::DOWNLOAD_SLUG, $key ) ); ?>" download><?php esc_html_e( 'Download', 'goodbids' ); ?></a>

						<span class="file-name"><?php echo esc_html( basename( $log_file ) ); ?></span>

						<span class="file-size">(<?php echo esc_html( size_format( filesize( $log_file ) ) ); ?>)</span>
					</li>
				<?php endforeach; ?>
			</ul>

			<?php
			if ( $total_pages > 1 ) :
				$page_url     = remove_query_arg( Logs::PAGINATION_PARAM );
				$current_page = goodbids()->network->logs->get_current_page();

				echo wp_kses_post(
					paginate_links(
						[
							'base'      => esc_url_raw( $page_url . '%_%' ),
							'format'    => '&' . Logs::PAGINATION_PARAM . '=%#%',
							'add_args'  => false,
							'current'   => $current_page,
							'total'     => $total_pages,
							'prev_text' => '&larr;',
							'next_text' => '&rarr;',
							'type'      => 'list',
							'end_size'  => 3,
							'mid_size'  => 3,
						]
					)
				);
			endif;
		}
	);
	?>
</div>
