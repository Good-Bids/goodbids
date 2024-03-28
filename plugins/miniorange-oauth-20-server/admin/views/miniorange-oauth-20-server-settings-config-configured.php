<?php
/**
 * Provide a configured client view for the plugin.
 *
 * This file is used to markup the configured client view of the plugin.
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
		<div class="is-flex">
			<h2 class="is-size-5 has-text-weight-semibold miniorange-oauth-20-server-card-title">Configured Client</h2>
			<a target="_blank" href="https://plugins.miniorange.com/oauth-api-documentation" class="button is-blue is-outlined ml-auto">
				<i class="fa-solid fa-file"></i> API Documentation
			</a>
		</div>
	</div>

	<div class="columns mx-1 my-5">
		<figure class="image is-32x32 is-flex is-align-items-center is-justify-content-center">
			<img src="<?php echo esc_attr( MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_URL ) . 'assets/' . esc_attr( $client_settings['image'] ); ?>">
		</figure>
		<h3 class="has-text-weight-semibold ml-2 mt-1 is-blue"><?php echo esc_attr( $client_settings['label'] ); ?></h3>

		<a target="_blank" href="<?php echo esc_attr( $client_settings['setup_guide'] ); ?>" class="button is-blue ml-auto">
			<i class="fa-solid fa-file"></i> Setup Guide
		</a>

	</div>

	<form method="POST" action="">
		<?php wp_nonce_field( 'mo_oauth_server_client_update_delete_action', 'mo_oauth_server_client_update_delete_action_nonce' ); ?>
		<div class="columns mb-0">
			<div class="column is-one-third mt-2">
				<label class="label" for="access_token_expiry">Client Name:</label>
			</div>
			<div class="column is-two-third">
				<div class="control">
					<input class="input is-normal" name="client_name" value="<?php echo esc_attr( $client->client_name ); ?>" type="text" readonly>
					<input type="hidden" name="client_id" value="<?php echo esc_attr( $client_id ); ?>">
				</div>
			</div>
		</div>
		<div class="columns">
			<div class="column is-one-third mt-2">
				<label class="label" for="access_token_expiry">Callback/Redirect URI (Optional):</label>
			</div>
			<div class="column is-two-third">
				<div class="control">
					<input class="input is-normal" name="redirect_uri" value="<?php echo esc_attr( $client->redirect_uri ); ?>" type="url" pattern="https?://.+">
				</div>
			</div>
		</div>
		<div class="field is-grouped is-grouped-centered mt-4">
			<div class="control">
				<button class="button is-blue" type="submit" name="update_client_button" value="update_client_app">Update</button>
			</div>
			<div class="control">
				<button class="button is-outlined delete-client" type="submit" name="delete_client_button" value="delete_client_app">Delete</button>
			</div>
		</div>
	</form>

	<hr />

	<div class="mb-4">
		<h3 class="has-text-weight-semibold is-blue">Client Credentials</h3>
	</div>

	<p class="is-size-6 mb-5">You can configure below credentials in your Client.</p>

	<table class="table is-striped is-hoverable is-fullwidth is-bordered">
		<tbody>
			<?php if ( $client_settings['client_id_label'] ) : ?>
				<tr>
					<td class="has-text-weight-semibold"><?php echo esc_attr( $client_settings['client_id_label'] ); ?>:</td>
					<td class="endpoint"><?php echo esc_attr( $client_id ); ?></td>
					<td>
						<div class="mx-auto is-clickable">
							<a class="is-grey" data-tooltip="Copy Client ID"><i class="fa-solid fa-copy copy-tooltip"></i></a>
						</div>
					</td>
				</tr>
			<?php endif; ?>
			<?php if ( $client_settings['client_secret_label'] ) : ?>
				<tr>
					<td class="has-text-weight-semibold"><?php echo esc_attr( $client_settings['client_secret_label'] ); ?>:</td>
					<td class="endpoint"><input class="input is-static is-borderless" type="password" id="client-secret" value="<?php echo esc_attr( $client_secret ); ?>" readonly></td>
					<td>
						<div class="mx-auto is-clickable">
							<a class="is-grey" data-tooltip="Copy Client Secret"><i class="fa-solid fa-copy copy-tooltip"></i></a>
							<a class="is-grey" id="eye-tooltip" data-tooltip="Show Client Secret"><i class="fa-solid fa-eye" id="eye_icon"></i></a>
						</div>
					</td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>


	<hr />

	<h3 class="has-text-weight-semibold is-blue">JWT Support</h3>

	<p class="my-4 is-size-6">Enable or Disable the support for JSON Web Tokens (JWT).</p>


	<form method="POST">

		<?php wp_nonce_field( 'mo_oauth_server_jwt_settings_form', 'mo_oauth_server_jwt_settings_form_nonce' ); ?>
		<input type="hidden" name="mo_oauth_server_appname" value="<?php echo esc_attr( $client->client_name ); ?>">

		<div class="field">
			<input id="mo_oauth_server_jwt_switch" type="checkbox" name="mo_server_enable_jwt_support_for_<?php echo esc_attr( $client->client_name ); ?>" class="switch is-rounded is-success" <?php echo esc_attr( $jwt_switch ); ?>>
			<label for="mo_oauth_server_jwt_switch">Enable JWT support</label>
		</div>

		<p class="my-4 is-size-6 is-italic"><span class="has-text-weight-semibold">Note:</span> Enable only if JWT is supported by your OAuth/OpenID client.</p>


		<?php if ( 'checked' === $jwt_switch ) : ?>
			<div class="field columns">
				<label class="label column is-one-quarter">Signing Algorithm:</label>
				<div class="select column is-fullwidth" id="signing-algo">
					<select class="column" name="mo_oauth_server_jwt_signing_algo_for_<?php echo esc_attr( $client->client_name ); ?>">
						<option value="HS256" <?php echo 'HS256' === $jwt_signing_algo ? 'selected' : ''; ?>>HS256</option>
						<option value="RS256" <?php echo 'RS256' === $jwt_signing_algo ? 'selected' : ''; ?>>RS256</option>
					</select>
				</div>
			</div>
		<?php endif; ?>

		<div class="field is-grouped is-grouped-centered mt-4">
			<div class="control">
				<button class="button is-active is-blue" type="submit" name="mo-oauth-server-save-jwt-settings" value="submit">Save Settings</button>
			</div>
	</form>
	<?php if ( 'checked' === $jwt_switch && 'RS256' === $jwt_signing_algo ) : ?>
		<form action="" method="POST">
			<?php wp_nonce_field( 'mo_oauth_server_jwt_signing_cert_download_form', 'mo_oauth_server_jwt_signing_cert_download_form_nonce' ); ?>
			<input type="hidden" name="option" value="downloadsigningcertificate">
			<input type="hidden" name="client" value="<?php echo esc_attr( $client_id ); ?>">
			<div class="control">
				<button class="button is-active is-blue is-outlined" type="submit">Download Signing Certificate</button>
			</div>
		</form>
	<?php endif; ?>
</div>



<hr />
<div class="mb-4 columns mx-auto">
	<h3 class="has-text-weight-semibold is-blue">Endpoints</h3>

	<form action="" method="POST" class="ml-auto">
		<?php wp_nonce_field( 'mo_oauth_server_postman_collection_form', 'mo_oauth_server_postman_collection_form_nonce' ); ?>
		<input type="hidden" name="option" value="downloadsamplejson">
		<input type="hidden" name="client" value="<?php echo esc_attr( $client_id ); ?>">
		<div class="field is-grouped">
			<div class="control">
				<button class="button is-danger ml-auto has-text-weight-medium has-tooltip-multiline" id="download_postman_collection" type="submit" data-tooltip="This will help you to test the OAuth server with postman as Client.">
					<i class="fa-solid fa-download"></i> Download Postman Collection
				</button>
			</div>
		</div>
	</form>
</div>
<p class="is-size-6 mb-5">You can configure below endpoints in your Client application.</p>

<table class="table is-striped is-hoverable is-fullwidth is-bordered">
	<tbody>
		<?php if ( $client_settings['authorize_url_label'] ) : ?>
			<tr>
				<td class="has-text-weight-semibold"><?php echo esc_attr( $client_settings['authorize_url_label'] ); ?>:</td>
				<td class="endpoint"><?php echo esc_url( $home_url_plus_rest_prefix ) . '/moserver/authorize'; ?></td>
				<td>
					<div class="mx-auto is-clickable">
						<a class="is-grey" data-tooltip="Copy Authorization Endpoint"><i class="fa-solid fa-copy copy-tooltip"></i></a>
					</div>
				</td>
			</tr>
		<?php endif; ?>
		<?php if ( $client_settings['token_url_label'] ) : ?>
			<tr>
				<td class="has-text-weight-semibold"><?php echo esc_attr( $client_settings['token_url_label'] ); ?>:</td>
				<td class="endpoint"><?php echo esc_url( $home_url_plus_rest_prefix ) . '/moserver/token'; ?></td>
				<td>
					<div class="mx-auto is-clickable">
						<a class="is-grey" data-tooltip="Copy Token Endpoint"><i class="fa-solid fa-copy copy-tooltip"></i></a>
					</div>
				</td>
			</tr>
		<?php endif; ?>
		<?php if ( $client_settings['userinfo_url_label'] ) : ?>
			<tr>
				<td class="has-text-weight-semibold"><?php echo esc_attr( $client_settings['userinfo_url_label'] ); ?>:</td>
				<td class="endpoint"><?php echo esc_url( $home_url_plus_rest_prefix ) . '/moserver/resource'; ?></td>
				<td>
					<div class="mx-auto is-clickable">
						<a class="is-grey" data-tooltip="Copy Get USer Info Endpoint"><i class="fa-solid fa-copy copy-tooltip"></i></a>
					</div>
				</td>
			</tr>
		<?php endif; ?>
		<?php if ( $client_settings['scopes_label'] ) : ?>
			<tr>
				<td class="has-text-weight-semibold"><?php echo esc_attr( $client_settings['scopes_label'] ); ?>:</td>
				<td class="endpoint">openid profile email</td>
				<td>
					<div class="mx-auto is-clickable">
						<a class="is-grey" data-tooltip="Copy Scopes"><i class="fa-solid fa-copy copy-tooltip"></i></a>
					</div>
				</td>
			</tr>
		<?php endif; ?>
		<?php if ( $client_settings['discovery_url_label'] ) : ?>
			<tr>
				<td class="has-text-weight-semibold"><?php echo esc_attr( $client_settings['discovery_url_label'] ); ?>:</td>
				<td class="endpoint"><?php echo esc_url( $home_url_plus_rest_prefix ) . '/moserver/' . esc_attr( $client->client_id ) . '/.well-known/openid-configuration'; ?></td>
				<td>
					<div class="mx-auto is-clickable">
						<a class="is-grey" data-tooltip="Copy Discovery Endpoint"><i class="fa-solid fa-copy copy-tooltip"></i></a>
					</div>
				</td>
			</tr>
		<?php endif; ?>
		<?php if ( $client_settings['jwks_url_label'] ) : ?>
			<tr>
				<td class="has-text-weight-semibold"><?php echo esc_attr( $client_settings['jwks_url_label'] ); ?>:</td>
				<td class="endpoint"><?php echo esc_url( $home_url_plus_rest_prefix ) . '/moserver/' . esc_attr( $client->client_id ) . '/.well-known/keys'; ?></td>
				<td>
					<div class="mx-auto is-clickable">
						<a class="is-grey" data-tooltip="Copy JWKS URL"><i class="fa-solid fa-copy copy-tooltip"></i></a>
					</div>
				</td>
			</tr>
		<?php endif; ?>
		<?php if ( $client_settings['issuer_url_label'] ) : ?>
			<tr>
				<td class="has-text-weight-semibold"><?php echo esc_attr( $client_settings['issuer_url_label'] ); ?>:</td>
				<td class="endpoint"><?php echo esc_url( $home_url_plus_rest_prefix ) . '/moserver/' . esc_attr( $client->client_id ); ?></td>
				<td>
					<div class="mx-auto is-clickable">
						<a class="is-grey" data-tooltip="Copy Issuer"><i class="fa-solid fa-copy copy-tooltip"></i></a>
					</div>
				</td>
			</tr>
		<?php endif; ?>
	</tbody>
</table>

<?php if ( $additional_settings ) : ?>
	<hr />
	<div class="mb-4">
		<h3 class="has-text-weight-semibold is-blue">Additional Settings</h3>
	</div>

	<p class="is-size-6 mb-5">You can find the additional settings required by your Client application here.</p>

	<table class="table is-striped is-narrow is-hoverable is-fullwidth is-bordered">
		<tbody>
			<?php foreach ( $additional_settings as $key => $value ) : ?>
				<tr>
					<td class="has-text-weight-semibold"><?php echo esc_attr( $key ); ?>:</td>
					<td><?php echo esc_html( htmlspecialchars( "{$value}", ENT_QUOTES ) ); ?></td>

				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

<?php endif; ?>

<hr />

<p class="my-2 has-text-weight-bold is-size-6 miniorange-oauth-20-server-yellow-color">
	<i class="fa-regular fa-gem mr-2"></i>
	Premium Features
</p>
<div class="columns is-vcentered is-multiline mt-4">
	<div class="column is-one-forth premium-features">
		<div class="">
			<div class="has-text-centered">
				<img class="my-auto" src="<?php echo esc_attr( MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_URL ) . 'assets/lock-gif.gif'; ?>" alt="GIF for premium features" style="width: 200px; height: 200px;">
			</div>
		</div>
	</div>
	<div class="column is-one-forth">
		<div class="card miniorange-oauth-20-server-card-background p-0 mt-0">
			<div class="card-content has-text-white">
				<p class="title has-text-centered is-5 miniorange-oauth-20-server-yellow-color">Endpoints</p>
				<p class="content has-text-centered is-size-6">Introspection Endpoint, OpenID Single Logout Endpoint, Revoke Endpoint</p>
			</div>
		</div>
	</div>
	<div class="column is-one-forth">
		<div class="card miniorange-oauth-20-server-card-background p-0 mt-0">
			<div class="card-content has-text-white">
				<p class="title has-text-centered is-5 miniorange-oauth-20-server-yellow-color">Grant Types</p>
				<p class="content has-text-centered is-size-6">Authorization Code Grant, Implicit Grant, Password Grant, Client Credentials Grant, Refresh Token Grant, Authorization Code with PKCE</p>
			</div>
		</div>
	</div>
	<div class="column is-one-forth">
		<div class="card miniorange-oauth-20-server-card-background p-0 mt-0">
			<div class="card-content has-text-white">
				<p class="title has-text-centered is-5 miniorange-oauth-20-server-yellow-color">Supported Scopes</p>
				<p class="content has-text-centered is-size-6">email, profile, custom, openid</p>
			</div>
		</div>
	</div>
</div>


</div>



</div>
