<?php
/**
 * Provide a short summary of the configured client.
 *
 * This file is used to markup the configured client.
 *
 * @link       https://www.miniorange.com
 * @since      1.0.0
 *
 * @package    Miniorange_Oauth_20_Server
 * @subpackage Miniorange_Oauth_20_Server/admin/views
 */

?>	
	<div class="column has-background-white mr-5 pt-0">
		<div class="mb-4">
			<h2 class="is-size-5 has-text-weight-semibold miniorange-oauth-20-server-card-title">Configured Applications Overview</h2>
		</div>

		<form method="post" action="">
		<?php wp_nonce_field( 'mo_oauth_server_client_update_delete_action', 'mo_oauth_server_client_update_delete_action_nonce' ); ?>
			<input class="hidden" name="client_name" value="<?php echo esc_attr( $client->client_name ); ?>">
			<input type="hidden" name="client_id" value="<?php echo esc_attr( $client_id ); ?>">
			<div class="container">

				<article class="message is-info">
					<div class="message-body">
					You can only add 1 client with free version. Upgrade to <a href="admin.php?page=mo_oauth_server_settings&tab=licensing_tab" class="is-link" >Premium</a> to add more.
					</div>
				</article>

				<div class="pt-4 px-3 mr-0">
					<div class="columns is-flex is-align-items-center is-justify-content-left">
						<figure class="image is-32x32 is-flex is-align-items-center is-justify-content-center">
							<img src="<?php echo esc_attr( MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_URL ) . 'assets/' . esc_attr( $client_settings['image'] ); ?>">
						</figure>
						<h3 class="has-text-weight-semibold mx-2 is-blue"><?php echo esc_attr( $client_settings['label'] ); ?></h3>
					</div>
					<div class="columns is-flex is-justify-content-center my-2">
						<a href="admin.php?page=mo_oauth_server_settings&tab=config" class="button is-blue">
							<strong>See Client Details</strong>
						</a>
						<button class="button ml-2 delete-client" type="submit" name="delete_client_button" value="delete_client_app">Delete Client</button>
					</div>
					<div class="columns has-text-centered my-4">
						<a target="_blank" href="<?php echo esc_url( $client_settings['setup_guide'] ); ?>" class="button is-blue is-outlined mx-auto">
							<strong><i class="fa-solid fa-file"></i> Setup Guide</strong>
						</a>
					</div>
				</div>

			</div>
		</form>
	</div>

<!-- This div close the parent container of main template. -->
</div>

