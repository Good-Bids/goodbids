<?php
/**
 * Network Admin Logs
 *
 * @since 1.0.0
 * @package GoodBids
 */

$log_files = goodbids()->network->logs->get_log_files();
?>
<div class="wrap">
	<h2><?php esc_html_e( 'Logs', 'goodbids' ); ?></h2>

	<ul>
		<?php foreach ( $log_files as $log_file ) : ?>
			<li>
				<?php echo esc_html( basename( $log_file ) ); ?>

				<a href="<?php echo esc_url( $log_file ); ?>" download><?php esc_html_e( 'Download', 'goodbids' ); ?></a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
