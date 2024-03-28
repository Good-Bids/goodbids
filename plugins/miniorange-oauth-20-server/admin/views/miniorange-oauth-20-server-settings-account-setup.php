<?php
/**
 * Provide a account setup view for the plugin.
 *
 * This file is used to markup the account setup form for the plugin.
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
	<h3 class="has-text-weight-semibold is-blue">Register with miniOrange</h3>

	<div class="card p-3 has-text-white miniorange-oauth-20-server-account-setup-card">
		<div class="card-title">
			<p class="is-size-6 has-text-weight-bold">Why should I register?</p>
		</div>
		<div class="card-content p-0 my-2">
		<p class="is-size-6">You should register so that in case you need help, we can help you with step by step instructions. 
			<span class="has-text-weight-semibold">You will also need a miniOrange account to upgrade to the premium version of the plugins.</span> 
			We do not store any information except the email that you will use to register with us.</p>
		</div>
	</div>

	<form method="POST" id="register_with_miniorange">
		<?php
		wp_nonce_field( 'mo_oauth_server_register_customer', 'mo_oauth_server_register_customer_nonce' );
		?>
		<input type="hidden" name="option" value="mo_oauth_register_customer">
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
				<input class="input" type="password" name="password" placeholder="Choose your password (Min. length 8)" required>
			</div>
		</div>
		<div class="field">
			<label class="label">Confirm Password</label>
			<div class="control">
				<input class="input" type="password" name="confirm_password" placeholder="Re-enter your password" required>
			</div>
		</div>
		<div class="field is-grouped is-grouped-centered">
			<div class="control">
				<button type="submit" class="button is-blue">Register</button>
			</div>
			<div class="control">
				<button class="button is-blue is-outlined" onclick="location.href='admin.php?page=mo_oauth_server_settings&tab=login_existing_user';">Already have an account?</button>
			</div>
		</div>
	</form>
</div>

<!-- This div close the parent container of main template. -->
</div>
