<?php
/**
 * Provide a account setup login view for the plugin.
 *
 * This file is used to markup the login form for the plugin.
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
		<h2 class="is-size-5 has-text-weight-semibold miniorange-oauth-20-server-card-title">Account Setup</h2>
	</div>
	<h3 class="has-text-weight-semibold is-blue">Login with miniOrange</h3>

	<form method="POST" id="login_with_miniorange">
		<?php
		wp_nonce_field( 'mo_oauth_server_account_verification', 'mo_oauth_server_account_verification_nonce' );
		?>
		<input type="hidden" name="option" value="mo_oauth_login_customer">
		<div class="field mt-4">
			<label class="label">Email</label>
			<div class="control has-icons-left">
				<input class="input" type="email" name="email" placeholder="person@example.com" required>
				<span class="icon is-small is-left">
					<i class="fas fa-envelope"></i>
				</span>
			</div>
		</div>
		<div class="field">
			<label class="label">Password</label>
			<div class="control">
				<input class="input" type="password" name="password" placeholder="Enter your password" required>
			</div>
			<p class="my-2 has-text-weight-semibold"><a href="https://login.xecurify.com/moas/idp/userforgotpassword">Forgot Password?</a></p>
		</div>
		<div class="field is-grouped is-grouped-centered">
			<div class="control">
				<button type="submit" class="button is-blue">Login</button>
			</div>
			<div class="control">
				<button class="button is-blue is-outlined" onclick="location.href='admin.php?page=mo_oauth_server_settings&tab=account_setup';">Back</button>
			</div>
		</div>
	</form>
</div>

<!-- This div close the parent container of main template. -->
</div>
