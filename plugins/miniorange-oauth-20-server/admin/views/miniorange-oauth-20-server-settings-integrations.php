<?php
/**
 * Provide a integrations and all clients view for the plugin.
 *
 * This file is used to markup the integrations and all clients view of the plugin.
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
		<h2 class="is-size-5 has-text-weight-semibold miniorange-oauth-20-server-card-title">Integrations</h2>
	</div>
	<div class="container">
	<h3 class="has-text-weight-semibold is-blue">Integrations</h3>
	<p class="is-size-6 mt-3">Send your user's memberships details in the response. Can’t find the plugin you wish to integrate with? Please <a href="admin.php?page=mo_oauth_server_settings&tab=contact_us"><span class="has-text-weight-semibold miniorange-oauth-20-server-orange-color">contact us</span></a>, we’ll help you integrate it in no time.</p>
	<div class="container" id="integrations-and-clients">
		<div class="columns is-multiline">
			<div class="column is-one-third">
				<div class="card p-0 is-flex">
					<div class="card-content">
						<div class="media">
							<div class="media-left">
								<figure class="image is-48x48">
									<img src="<?php echo esc_attr( MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_URL ) . 'assets/WooCommerce.png'; ?>" alt="Placeholder image">
								</figure>
							</div>
							<div class="media-content is-clipped">
								<p class="title is-5 is-grey">WooCommerce Membership</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="column is-one-third">
				<div class="card p-0 is-flex">
					<div class="card-content">
						<div class="media">
							<div class="media-left">
								<figure class="image is-48x48">
									<img src="<?php echo esc_attr( MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_URL ) . 'assets/PaidMembership.png'; ?>" alt="Placeholder image">
								</figure>
							</div>
							<div class="media-content is-clipped">
								<p class="title is-5 is-grey">Paid Membership Pro</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="column is-one-third">
				<div class="card p-0 is-flex">
					<div class="card-content">
						<div class="media">
							<div class="media-left">
								<figure class="image is-48x48">
									<img src="<?php echo esc_attr( MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_URL ) . 'assets/BuddyPress.png'; ?>" alt="Placeholder image">
								</figure>
							</div>
							<div class="media-content is-clipped">
								<p class="title is-5 is-grey">BuddyPress</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="column is-one-third">
				<div class="card p-0 is-flex">
					<div class="card-content">
						<div class="media">
							<div class="media-left">
								<figure class="image is-48x48">
									<img src="<?php echo esc_attr( MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_URL ) . 'assets/MemberPress.png'; ?>" alt="Placeholder image">
								</figure>
							</div>
							<div class="media-content is-clipped">
								<p class="title is-5 is-grey">MemberPress</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="column is-one-third">
				<div class="card p-0 is-flex">
					<div class="card-content">
						<div class="media">
							<div class="media-left">
								<figure class="image is-48x48">
									<img src="<?php echo esc_attr( MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_URL ) . 'assets/BuddyBoss.png'; ?>" alt="Placeholder image">
								</figure>
							</div>
							<div class="media-content is-clipped">
								<p class="title is-5 is-grey">BuddyBoss</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="column is-one-third">
				<div class="card p-0 is-flex">
					<div class="card-content">
						<div class="media">
							<div class="media-left">
								<figure class="image is-48x48">
									<img src="<?php echo esc_attr( MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_URL ) . 'assets/LearnDash.png'; ?>" alt="Placeholder image">
								</figure>
							</div>
							<div class="media-content is-clipped">
								<p class="title is-5 is-grey">LearnDash</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<hr />
		<h3 class="has-text-weight-semibold is-blue my-4">Supported Client Applications</h3>
		<p class="is-size-6 mt-3">Can’t find the client application you wish to connect with? Configure a custom OAuth/OpenID application. You can also let us know at <a href="mailto:wpidpsupport@xecurify.com">wpidpsupport@xecurify.com</a> about your application, we can add it as a pre-configured application</p>
		<div class="columns mt-4 is-multiline">
			<?php foreach ( $oauth_client_list_json_data as $client_name => $client_fields ) : ?>
				<div id="<?php echo esc_attr( $client_name ); ?>" class="column is-one-fifth-widescreen is-one-quarter-mobile ">
					<a target="_blank" href="<?php echo esc_url( $client_fields['setup_guide'] ); ?>">
						<figure class="image mx-auto is-rounded is-96x96 miniorange-oauth-20-server-logo is-flex is-clickable is-align-items-center is-justify-content-center">
							<img src="<?php echo esc_attr( MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_URL ) . 'assets/' . esc_attr( $client_fields['image'] ); ?>">
						</figure>
						<p class="has-text-centered mt-2 is-size-6 has-text-weight-normal client-name"><?php echo esc_attr( $client_fields['label'] ); ?></p>
					</a>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
<!-- This div ends the div that starts at template level -->
</div>
