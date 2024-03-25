<?php
/**
 * Provide a unconfigured client view for the plugin.
 *
 * This file is used to markup the unconfigured client view of the plugin.
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
			<h2 class="is-size-5 has-text-weight-semibold miniorange-oauth-20-server-card-title">Add New Client</h2>
			<a target="_blank" href="https://plugins.miniorange.com/oauth-api-documentation" class="button is-blue is-outlined ml-auto">
				<i class="fa-solid fa-file"></i> API Documentation
			</a>
		</div>
	</div>

	<?php if ( $client_settings ) : ?>
		<div class="columns mx-1 my-5">
			<figure class="image is-32x32 is-flex is-align-items-center is-justify-content-center">
				<img src="<?php echo esc_attr( MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_URL ) . 'assets/' . esc_attr( $client_settings['image'] ); ?>">
			</figure>
			<h3 class="has-text-weight-semibold ml-2 is-blue"><?php echo esc_attr( $client_settings['label'] ); ?></h3>

			<a target="_blank" href="<?php echo esc_url( $client_settings['setup_guide'] ); ?>" class="button is-blue is-outlined ml-auto">
				<i class="fa-solid fa-file"></i> Setup Guide
			</a>

		</div>
	<?php endif; ?>

	<form method="POST" action="">
		<?php wp_nonce_field( 'mo_oauth_server_add_new_client_form', 'mo_oauth_server_add_new_client_form_nonce' ); ?>
		<div class="columns mb-0">
			<div class="column is-one-third">
				<label class="label" for="access_token_expiry">Client Name:</label>
			</div>
			<div class="column is-two-third">
				<div class="control">
					<input class="input is-normal" placeholder="Application Name" name="client_name">
				</div>
			</div>
		</div>
		<div class="columns">
			<div class="column is-one-third">
				<label class="label" for="access_token_expiry">Callback/Redirect URI (Optional):</label>
			</div>
			<div class="column is-two-third">
				<div class="control">
					<input class="input is-normal" placeholder="Provided by your Client application" name="redirect_uri">
				</div>
			</div>
		</div>
		<div class="field is-grouped is-grouped-centered mt-4">
			<div class="control">
				<button class="button is-active is-blue" type="submit" name="mo_oauth_server_add_new_client_form" value="add_new_client">Save Client</button>
			</div>
		</div>
	</form>
</div>

<!-- This div close the parent container of main template. -->
</div>
