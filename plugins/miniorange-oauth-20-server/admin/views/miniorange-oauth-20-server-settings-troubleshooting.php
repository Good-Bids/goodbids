<?php
/**
 * Provide a troubleshooting view for the plugin.
 *
 * This file is used to markup the troubleshooting aspects of the plugin.
 *
 * @link       https://www.miniorange.com
 * @since      1.0.0
 *
 * @package    Miniorange_Oauth_20_Server
 * @subpackage Miniorange_Oauth_20_Server/admin/views
 */

?>
<div class="column has-background-white mr-5 px-5">
	<div class="mb-4">
		<h2 class="is-size-5 has-text-weight-semibold miniorange-oauth-20-server-card-title">Troubleshooting</h2>
	</div>
	<h3 class="has-text-weight-semibold mt-4 is-blue">Debug Logs</h3>
	<p class="mt-4 is-size-6">Enable the debug logs to troubleshoot the issue.</p>

	<form id="mo_oauth_server_log_button_form" method="POST">
		<?php wp_nonce_field( 'mo_oauth_server_debug_logs_form', 'mo_oauth_server_debug_logs_form_nonce' ); ?>
		<div class="field">
			<input id="mo_oauth_server_log_button_toggle" type="checkbox" name="mo_oauth_server_log_button_toggle" <?php echo esc_attr( $debug_log_button ); ?> class="switch is-rounded is-success">
			<label for="mo_oauth_server_log_button_toggle">Debug Logs</label>
		</div>
		<?php if ( 'checked' === $debug_log_button ) : ?>
		<div class="field is-grouped is-grouped-centered">
			<p class="control">
				<button type="submit" name="mo_oauth_server_download_logs" value="true" class="button is-blue">Download Logs</button>
			</p>
			<p class="control">
				<button type="submit" name="mo_oauth_server_delete_logs" value="true" class="button is-blue is-outlined">Delete Old Logs</button>
			</p>
		</div>
		<?php endif; ?>
	</form>

</div>
<!-- This div close the parent container of main template. -->
</div>

<script>
	// submit this form on toggling the mo switch button for debug log
	const debug_log_button = document.querySelector('#mo_oauth_server_log_button_toggle');
	debug_log_button.addEventListener('click', () => {
		const debug_log_form = document.getElementById('mo_oauth_server_log_button_form');
		debug_log_form.submit();
	});
</script>
