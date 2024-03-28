<?php
/**
 * Provide a admin area view for the plugin.
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://miniorange.com
 * @since      1.0.0
 *
 * @package    Miniorange_Oauth_20_Server
 * @subpackage Miniorange_Oauth_20_Server/admin/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="column has-background-white mr-5 px-5">
	<div class="mb-4">
		<h2 class="is-size-5 has-text-weight-semibold miniorange-oauth-20-server-card-title">Account Setup</h2>
	</div>
	<h3 class="has-text-weight-semibold is-blue">miniOrange Account Details</h3>

	<div class="table-container mt-4">
		<table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
			<thead>
				<tr>
					<th>Account Email</th>
					<td><?php echo esc_attr( get_option( 'mo_oauth_admin_email' ) ); ?></td>
				</tr>
				<tr>
					<th>Customer ID</th>
					<td><?php echo esc_attr( get_option( 'mo_oauth_server_admin_customer_key' ) ); ?></td>
				</tr>
			</thead>
		</table>
		<form method="POST" id="change_miniorange_account">
			<?php wp_nonce_field( 'mo_oauth_server_change_account', 'mo_oauth_server_change_account_nonce' ); ?>
			<input type="hidden" name="option" value="mo_oauth_change_account">
			<div class="field is-grouped is-grouped-centered">
				<div class="control">
					<button type="submit" class="button is-blue">Change Account</button>
				</div>
			</div>
		</form>
	</div>
</div>

<!-- This div close the parent container of main template. -->
</div>
