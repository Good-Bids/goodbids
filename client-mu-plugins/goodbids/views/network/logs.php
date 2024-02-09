<?php
/**
 * Network Admin Logs
 *
 * @since 1.0.0
 * @package GoodBids
 */

use GoodBids\Network\Logs;

$log_files = goodbids()->network->logs->get_log_files();
?>
<div class="wrap">
	<h2><?php esc_html_e( 'Logs', 'goodbids' ); ?></h2>

	<ul>
		<?php foreach ( $log_files as $key => $log_file ) : ?>
			<li>
				<?php echo esc_html( basename( $log_file ) ); ?>

				<a href="<?php echo esc_url( add_query_arg( Logs::DOWNLOAD_SLUG, $key ) ); ?>" download><?php esc_html_e( 'Download', 'goodbids' ); ?></a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
