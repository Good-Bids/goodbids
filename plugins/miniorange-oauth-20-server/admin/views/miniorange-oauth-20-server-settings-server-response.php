<?php
/**
 * Provide a server response view for the plugin.
 *
 * This file is used to markup the server response of the plugin.
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
		<h2 class="is-size-5 has-text-weight-semibold miniorange-oauth-20-server-card-title">Server Response</h2>
	</div>

	<h3 class="has-text-weight-semibold mt-4 is-blue">Basic Attribute Mapping</h3>
	<p class="mt-4 is-size-6">You can customize and send below attriutes in response to your Client's Get User Information request.</p>
	<div class="columns mt-4">
		<div class="column">
			<div class="field">
				<label class="label has-text-weight-semibold has-text-centered">Attribute Name</label>
				<div class="control">
					<input class="input m-1" type="text" placeholder="username" disabled>
					<input class="input m-1" type="text" placeholder="email" disabled>
					<input class="input m-1" type="text" placeholder="first_name" disabled>
					<input class="input m-1" type="text" placeholder="last_name" disabled>
					<input class="input m-1" type="text" placeholder="display_name" disabled>
					<input class="input m-1" type="text" placeholder="nickname" disabled>
				</div>
			</div>
		</div>
		<div class="column">
			<div class="field">
				<label class="label has-text-weight-semibold has-text-centered">Attribute Value</label>
				<div class="control">
					<div class="select m-1 is-fullwidth">
						<select disabled>
							<option>user_login</option>
							<option>email</option>
							<option>first_name</option>
							<option>last_name</option>
							<option>display_name</option>
							<option>nickname</option>
						</select>
					</div>
				</div>
				<div class="control">
					<div class="select m-1 is-fullwidth">
						<select disabled>
							<option>email</option>
							<option>user_login</option>
							<option>first_name</option>
							<option>last_name</option>
							<option>display_name</option>
							<option>nickname</option>
						</select>
					</div>
				</div>
				<div class="control">
					<div class="select m-1 is-fullwidth">
						<select disabled>
							<option>first_name</option>
							<option>user_login</option>
							<option>email</option>
							<option>last_name</option>
							<option>display_name</option>
							<option>nickname</option>
						</select>
					</div>
				</div>
				<div class="control">
					<div class="select m-1 is-fullwidth">
						<select disabled>
							<option>last_name</option>
							<option>user_login</option>
							<option>email</option>
							<option>first_name</option>
							<option>display_name</option>
							<option>nickname</option>
						</select>
					</div>
				</div>
				<div class="control">
					<div class="select m-1 is-fullwidth">
						<select disabled>
							<option>display_name</option>
							<option>user_login</option>
							<option>email</option>
							<option>first_name</option>
							<option>last_name</option>
							<option>nickname</option>
						</select>
					</div>
				</div>
				<div class="control">
					<div class="select m-1 is-fullwidth">
						<select disabled>
							<option>nickname</option>
							<option>user_login</option>
							<option>email</option>
							<option>first_name</option>
							<option>last_name</option>
							<option>display_name</option>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>

	<hr />

	<div class="column has-background-white">
		<div class="is-flex">
			<h3 class="has-text-weight-semibold is-blue">Custom Attribute Mapping</h3>
			<div class="ml-auto">
				<i class="fa-solid fa-square-plus fa-xl mr-1"></i>
				<i class="fa-solid fa-square-minus fa-xl"></i>
			</div>
		</div>
		<p class="mt-4 is-size-6">Map extra User attributes which you wish to be included in the OAuth response.</p>
		<p><span class="is-italic has-text-weight-semibold">Note:</span> Enter the name you want to send as attribute name under Attribute Name text field and meta field name under the Attribute Value text field.</p>

		<div class="columns mt-4">
			<div class="column">
				<div class="field">
					<label class="label has-text-weight-semibold has-text-centered">Attribute Name</label>
					<div class="control">
						<input class="input m-1" type="text" placeholder="Given Name" disabled>
					</div>
				</div>
			</div>
			<div class="column">
				<div class="field">
					<label class="label has-text-weight-semibold has-text-centered">Attribute Value</label>
					<div class="control">
						<input class="input m-1" type="text" placeholder="first_name" disabled>
					</div>
				</div>
			</div>
		</div>
		<div class="field is-grouped is-grouped-centered mt-4">
			<div class="control">
				<button class="button is-blue" type="submit" disabled>Save Settings</button>
			</div>
		</div>

		<hr />
		<p class="my-2 has-text-weight-bold is-size-6 miniorange-oauth-20-server-yellow-color">
			<i class="fa-regular fa-gem mr-2"></i>
			Premium Features
		</p>
		<div class="columns is-multiline is-vcentered mt-4">
			<div class="column is-one-third">
				<div class="">
					<div class="has-text-centered">
						<img src="<?php echo esc_attr( MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_URL ) . 'assets/lock-gif.gif'; ?>" alt="GIF for premium features" style="width: 200px; height: 200px;">
					</div>
				</div>
			</div>
			<div class="column is-one-third">
				<div class="card miniorange-oauth-20-server-card-background p-0 mt-0">
					<div class="card-content has-text-white">
						<p class="title has-text-centered is-5 miniorange-oauth-20-server-yellow-color">Basic Attribute Mapping</p>
						<p class="content has-text-centered is-size-6">Send the user's profile info in the attribute names of your choice.</p>
					</div>
				</div>
			</div>
			<div class="column is-one-third">
				<div class="card miniorange-oauth-20-server-card-background p-0 mt-0">
					<div class="card-content has-text-white">
						<p class="title has-text-centered is-5 miniorange-oauth-20-server-yellow-color">Custom Attribute Mapping</p>
						<p class="content has-text-centered is-size-6">Allows you to map custom attributes such as phone number, bio, etc present in usermeta table from WP to your Client application.</p>
					</div>
				</div>
			</div>
		</div>
		<!-- This div close the parent container of main template. -->
	</div>
