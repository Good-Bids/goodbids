<?php
/**
 * Provide a general settings such as enforce state parameter, OpenID support etc. view for the plugin.
 *
 * This file is used to markup the general settings such as enforce state parameter, OpenID support etc. view of the plugin.
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
		<h2 class="is-size-5 has-text-weight-semibold miniorange-oauth-20-server-card-title">Advance Settings</h2>
	</div>

	<form method="post" action="">
		<?php wp_nonce_field( 'mo_oauth_server_settings_form', 'mo_oauth_server_settings_form_nonce' ); ?>
		<h3 class="has-text-weight-semibold is-blue">Master Switch</h3>
		<p class="mt-4 is-size-6">Disabling master switch will stop sending/receiveing API calls from/to your Client application.</p>
		<div class="field">
			<input id="mo_oauth_server_master_switch" type="checkbox" name="mo_oauth_server_master_switch" class="switch is-rounded is-success" <?php echo esc_attr( $master_switch ); ?>>
			<label for="mo_oauth_server_master_switch">Master Switch</label>
		</div>
		<hr />
		<h3 class="has-text-weight-semibold is-blue">Custom Login URL</h3>
		<p class="my-4 is-size-6">Add your custom login page URL.</p>
		<div class="columns">
			<div class="column is-one-third">
				<label class="label" for="mo_oauth_server_custom_login_url" class="mt-4">Custom Login URL:</label>
				<p class="is-italic">(If your login page is different from default login page.)</p>
			</div>
			<div class="column is-two-third">
				<div class="control">
					<input class="input is-normal" placeholder="Enter your custom login page URL" id="mo_oauth_server_custom_login_url" name="mo_oauth_server_custom_login_url" <?php echo 'value=' . esc_attr( $custom_url ); ?>>	
				</div>
			</div>
		</div>
		<hr />
		<h3 class="has-text-weight-semibold is-blue">OpenID Connect</h3>
		<p class="mt-4 is-size-6">Enable or Disable the support for OpenID Connect Protocol.</p>
		<div class="field">
			<input id="mo_oauth_server_openid_connect" type="checkbox" name="mo_oauth_server_openid_connect" class="switch is-rounded is-success" <?php echo esc_attr( $open_id_switch ); ?>>
			<label for="mo_oauth_server_openid_connect">OpenID Connect</label>
		</div>
		<hr />
		<h3 class="has-text-weight-semibold is-blue">State Parameter</h3>
		<p class="mt-4 is-size-6">When enabled, the authorization request will fail if state parameter is not provided or is incorrect.</p>
		<div class="field">
			<input id="mo_oauth_server_state_parameter" type="checkbox" name="mo_oauth_server_state_parameter" class="switch is-rounded is-success" <?php echo esc_attr( $state_parameter_switch ); ?>>
			<label for="mo_oauth_server_state_parameter">Enforce State Parameter</label>
		</div>
		<hr />
		<div class="field is-grouped is-grouped-centered mt-4">
			<div class="control">
				<button class="button is-active is-blue" type="submit">Save Settings</button>
			</div>
		</div>
	</form>

	<hr />
	<p class="mt-3 mb-5 has-text-weight-bold is-size-6 miniorange-oauth-20-server-yellow-color">
		<i class="fa-solid fa-gem mr-2"></i>
		Premium Features
	</p>
	<h3 class="has-text-weight-semibold is-blue">Authorize/Consent Prompt</h3>
	<p class="mt-4 is-size-6">If enabled, the server will show a consent screen where the user can allow/deny the applications.</p>
	<button class="button miniorange-oauth-20-server-tooltip-button" data-tooltip="Premium Feature">
		<div class="field">
			<input id="mo_oauth_server_consent_screen" type="checkbox" name="mo_oauth_server_consent_screen" class="switch is-rounded is-success" checked="checked" disabled>
			<label class="has-text-weight-bold has-text-black" for="mo_oauth_server_consent_screen">Enable Authorize/Consent Prompt</label>
		</div>
	</button>

	<hr />
	<h3 class="has-text-weight-semibold is-blue">Redirect/Callback URI Validation</h3>
	<p class="mt-4 is-size-6">Note: Use in case of Dynamic or Conditional Callback/Redirect URIs.</p>
	<button class="button miniorange-oauth-20-server-tooltip-button" data-tooltip="Premium Feature">
		<div class="field">
			<input id="mo_oauth_server_redirect" type="checkbox" name="" class="switch is-rounded is-success" disabled>
			<label class="has-text-weight-bold has-text-black" for="mo_oauth_server_redirect">Validate Redirect/Callback URIs</label>
		</div>
	</button>

	<hr />
	<h3 class="has-text-weight-semibold is-blue mb-4">Token Security</h3>
	<div class="columns">
		<div class="column is-one-third">
			<label class="label" for="access_token_expiry">Access Token Expiry Time:</label>
			<p class="is-italic">(In Seconds)</p>
		</div>
		<div class="column is-two-third">
			<div class="control">
				<input class="input is-normal" type="number" placeholder="3600" value="3600" disabled>
			</div>
		</div>
	</div>
	<div class="columns">
		<div class="column is-one-third">
			<label class="label" for="refersh_token_expiry">Refresh Token Expiry Time:</label>
			<p class="is-italic">(In Seconds)</p>
		</div>
		<div class="column is-two-third">
			<div class="control">
				<input class="input is-normal" type="number" placeholder="86400" value="86400" disabled>
			</div>
		</div>
	</div>
	<div class="columns">
		<div class="column is-one-third">
			<label class="label" for="token_length" class="mt-4">Token Length:</label>
		</div>
		<div class="column is-two-third">
			<div class="control">
				<input class="input is-normal" type="number" placeholder="32" value="32" disabled>
			</div>
		</div>
	</div>
	<br />
	<br />
	<div class="columns is-multiline is-vcentered">
		<div class="column is-one-forth premium-features">
			<div class="">
				<div class="has-text-centered">
					<img src="<?php echo esc_attr( MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_URL ) . 'assets/lock-gif.gif'; ?>" alt="GIF for premium features" style="width: 200px; height: 200px;">
				</div>
			</div>
		</div>
		<div class="column is-one-forth">
			<div class="card miniorange-oauth-20-server-card-background p-0 mt-0">
				<div class="card-content has-text-white">
					<p class="title has-text-centered is-5 miniorange-oauth-20-server-yellow-color">Access Token Expiry Time</p>
					<p class="content has-text-centered is-size-6">Customizable</p>
				</div>
			</div>
		</div>
		<div class="column is-one-forth">
			<div class="card miniorange-oauth-20-server-card-background p-0 mt-0">
				<div class="card-content has-text-white">
					<p class="title has-text-centered is-5 miniorange-oauth-20-server-yellow-color">Refresh Token Expiry Time</p>
					<p class="content has-text-centered is-size-6">Customizable</p>
				</div>
			</div>
		</div>
		<div class="column is-one-forth">
			<div class="card miniorange-oauth-20-server-card-background p-0 mt-0">
				<div class="card-content has-text-white">
					<p class="title has-text-centered is-5 miniorange-oauth-20-server-yellow-color">Token Length</p>
					<p class="content has-text-centered is-size-6">Customizable</p>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
