<?php
/**
 * Provide a premium features view for the plugin.
 *
 * This file is used to markup the premium features of the plugin.
 *
 * @link       https://www.miniorange.com
 * @since      1.0.0
 *
 * @package    Miniorange_Oauth_20_Server
 * @subpackage Miniorange_Oauth_20_Server/admin/views
 */

?>
	<div class="column has-background-white mr-5 px-5" id="premium-features">
		<div class="mb-4">
			<h2 class="is-size-5 has-text-weight-semibold miniorange-oauth-20-server-card-title">Premium Features</h2>
		</div>
		<p class="is-size-6">You can <a href="admin.php?page=mo_oauth_server_settings&tab=licensing_tab"><span class="has-text-weight-bold miniorange-oauth-20-server-orange-color">Upgrade Now</span></a> to unlock these features or <a href="admin.php?page=mo_oauth_server_settings&tab=contact_us"><span class="has-text-weight-bold miniorange-oauth-20-server-orange-color">Contact Us</span></a> for more information.</p>
		<div class="columns is-multiline">
			<div class="column is-one-third">
				<div class="card miniorange-oauth-20-server-card-background p-0">
					<div class="card-content has-text-white has-text-centered">
						<p class="title is-5 miniorange-oauth-20-server-card-title">Endpoints</p>
						<p class="content is-size-6">Introspection Endpoint, OpenID Single Logout Endpoint, Revoke Endpoint</p>
					</div>
				</div>
			</div>
			<div class="column is-one-third">
				<div class="card miniorange-oauth-20-server-card-background p-0">
					<div class="card-content has-text-white has-text-centered">
						<p class="title is-5 miniorange-oauth-20-server-card-title">Grant Types</p>
						<p class="content is-size-6">Authorization Code Grant, Implicit Grant, Password Grant, Client Credentials Grant, Refresh Token Grant, Authorization Code with PKCE</p>
					</div>
				</div>
			</div>
			<div class="column is-one-third">
				<div class="card miniorange-oauth-20-server-card-background p-0">
					<div class="card-content has-text-white has-text-centered">
						<p class="title is-5 miniorange-oauth-20-server-card-title">Scopes</p>
						<p class="content is-size-6">email, profile, openid and custom</p>
					</div>
				</div>
			</div>
			<div class="column is-one-third pt-0">
				<div class="card miniorange-oauth-20-server-card-background p-0">
					<div class="card-content has-text-white has-text-centered">
						<p class="title is-5 miniorange-oauth-20-server-card-title">Basic Attribute Mapping</p>
						<p class="content is-size-6">Send the user's profile info in the attribute names of your choice.</p>
					</div>
				</div>
			</div>
			<div class="column is-one-third pt-0">
				<div class="card miniorange-oauth-20-server-card-background p-0">
					<div class="card-content has-text-white has-text-centered">
						<p class="title is-5 miniorange-oauth-20-server-card-title">Custom Attribute Mapping</p>
						<p class="content is-size-6">Allows you to map custom attributes such as phone number, bio, etc present in usermeta table from WP to your Client application.</p>
					</div>
				</div>
			</div>
			<div class="column is-one-third pt-0">
				<div class="card miniorange-oauth-20-server-card-background p-0">
					<div class="card-content has-text-white has-text-centered">
						<p class="title is-5 miniorange-oauth-20-server-card-title">Role Mapping</p>
						<p class="content is-size-6">Allows you to send roles from your WP website to your Client app for role based access to Resources.</p>
					</div>
				</div>
			</div>
			<div class="column is-one-third pt-0">
				<div class="card miniorange-oauth-20-server-card-background p-0">
					<div class="card-content has-text-white has-text-centered">
						<p class="title is-5 miniorange-oauth-20-server-card-title">Membership Sync</p>
						<p class="content is-size-6">Send your user's memberships details in the response.</p>
					</div>
				</div>
			</div>
			<div class="column is-one-third pt-0">
				<div class="card miniorange-oauth-20-server-card-background p-0">
					<div class="card-content has-text-white has-text-centered">
						<p class="title is-5 miniorange-oauth-20-server-card-title">Access Token Expiry Time</p>
						<p class="content is-size-6">Customizable</p>
					</div>
				</div>
			</div>
			<div class="column is-one-third pt-0">
				<div class="card miniorange-oauth-20-server-card-background p-0">
					<div class="card-content has-text-white has-text-centered">
						<p class="title is-5 miniorange-oauth-20-server-card-title">Refresh Token Expiry Time</p>
						<p class="content is-size-6">Customizable</p>
					</div>
				</div>
			</div>
			<div class="column is-one-third pt-0">
				<div class="card miniorange-oauth-20-server-card-background p-0">
					<div class="card-content has-text-white has-text-centered">
						<p class="title is-5 miniorange-oauth-20-server-card-title">Access Token Length</p>
						<p class="content is-size-6">Customizable</p>
					</div>
				</div>
			</div>
		</div>

	</div>


<!-- This div close the parent container of main template. -->
</div>
