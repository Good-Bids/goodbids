<?php
/**
 * Summary of authorize-dialog
 *
 * @package Authorize Dialog
 */

/**
 * Summary of mo_oauth_server_emit_css
 *
 * The CSS for consent screen.
 *
 * @return void
 */
function mo_oauth_server_emit_css() {   ?>
	<style>
		body {
			background-color: #f8f8f8;
			height: 742px;
		}

		.box img {
			height: 85px;
			position: relative;
			left: 25px;
		}

		.box img.client-image {
			left: -47px;
		}

		.box .subtitle {
			font-size: 15px;
		}

		.box p.is-italic {
			font-size: 13px;
		}
		.box .powered-by img {
			height: 20px;
			left: 0px;
			top: 6px;
		}
	</style>
	<?php
}

/**
 * Summary of mo_oauth_server_emit_html
 *
 * The HTML for consent screen.
 *
 * @param mixed $client_credentials the client credentials.
 * @param mixed $scope_message the scope message.
 * @return void
 */
function mo_oauth_server_emit_html( $client_credentials, $scope_message ) {
	mo_oauth_server_emit_css();

	$oauth_client_list_json      = file_get_contents( dirname( dirname( dirname( __FILE__ ) ) ) . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'oauth-client-list.json' ); //phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Using file_get_contents to fetch a local file, not a remote file.
	$oauth_client_list_json_data = json_decode( $oauth_client_list_json, true );

	$choosen_client  = get_option( 'mo_oauth_server_client' );
	$client_settings = $oauth_client_list_json_data[ $choosen_client ];
	?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>miniOrange OAuth Authorization Screen</title>
	<link rel="stylesheet" href="<?php echo esc_attr( MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_URL ) . 'admin/css/bulma.min.css'; ?>">
</head>

<body>
	<div class="container">
		<div class="columns is-centered">
			<div class="column is-5 mt-6">
				<div class="box mt-6 p-6">
					<div class="has-text-centered">
						<div class="is-flex is-justify-content-center is-align-items-center">
							<img src="<?php echo esc_attr( MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_URL ) . 'assets/Authorize.png'; ?>" alt="Application logo">
							<figure class="image is-64x64 is-flex is-justify-content-center is-align-items-center">
								<img src="<?php echo esc_attr( MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_URL ) . 'assets/' . esc_attr( $client_settings['image'] ); ?>" class="client-image">
							</figure>
						</div>
						<h2 class="title is-4 pt-5">Authorize 3rd Party Application</h2>
					</div>
					<hr class="mx-4">
					<p class="subtitle has-text-centered">The application <b>'<?php echo esc_attr( $client_credentials['client_name'] ); ?>'</b> wants to access the following information:
					<br>Please review these and consent if it is OK.</p>
					<ul class="consent-list mx-4">
						<?php foreach ( $scope_message as $msg ) { ?>
							<li>
								<label class="checkbox">
									<input type="checkbox" checked="checked" name="consent[]" value="<?php echo esc_attr( $msg ); ?>" disabled>
									<span><?php echo esc_attr( $msg ); ?></span>
								</label>
							</li>
						<?php } ?>
					</ul>
					<p class="has-text-centered py-4 is-italic">This application cannot continue if you do not allow this application.</p>
					<div class="consent-form mx-4">
						<form action="" method="post">
							<?php wp_nonce_field( 'mo_oauth_server_authorize_dialog_allow_form', 'mo_oauth_server_authorize_dialog_allow_form_field' ); ?>
							<input type="hidden" name="mo_oauth_server_authorize_dialog" value="1" />
							<input type="hidden" name="mo_oauth_server_authorize" value="allow" />
							<button class="button is-success is-fullwidth">Submit Consent</button>
						</form>
						<form action="" method="post">
							<?php wp_nonce_field( 'mo_oauth_server_authorize_dialog_deny_form', 'mo_oauth_server_authorize_dialog_deny_form_field' ); ?>
							<input type="hidden" name="mo_oauth_server_authorize_dialog" value="1" />
							<input type="hidden" name="mo_oauth_server_authorize" value="deny" />
							<button class="button is-outlined is-danger is-fullwidth">Cancel</button>
						</form>
					</div>
					<div class="powered-by has-text-centered">
						<p>Powered by <img src="<?php echo esc_attr( MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_URL ) . 'assets/miniorange-logo.png'; ?>"></img></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>

</html>

	<?php
}
