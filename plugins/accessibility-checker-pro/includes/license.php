<?php
/**
 * Accessibility Checker pluign file.
 *
 * @package Accessibility_Checker
 */

// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed.
define( 'EDACP_STORE_URL', 'https://my.equalizedigital.com' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file.

// the download ID for the product in Easy Digital Downloads.
define( 'EDACP_ITEM_ID', 24 ); // you should use your own CONSTANT name, and be sure to replace it throughout this file.

// the name of the product in Easy Digital Downloads.
define( 'EDACP_ITEM_NAME', 'Accessibility Checker' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file.

// the name of the settings page for the license input to be displayed.
define( 'EDD_PLUGIN_LICENSE_PAGE', 'accessibility_checker_settings' );

if ( ! class_exists( 'EDACP_SL_Plugin_Updater' ) ) {
	// load our custom updater.
	include __DIR__ . '/classes/EDACP_SL_Plugin_Updater.php';
}

/**
 * Initialize the updater. Hooked into `init` to work with the
 * wp_version_check cron job, which allows auto-updates.
 */
function edacp_plugin_updater() {

	// retrieve our license key from the DB.
	$license_key = trim( get_option( 'edacp_license_key' ) );

	// setup the updater.
	new EDACP_SL_Plugin_Updater(
		EDACP_STORE_URL,
		EDACP_PLUGIN_FILE,
		array(
			'version' => EDACP_VERSION,      // Current version number.
			'license' => $license_key,       // License key (used get_option above to retrieve from DB).
			'item_id' => EDACP_ITEM_ID,      // ID of the product.
			'author'  => 'Equalize Digital', // Author of this plugin.
			'beta'    => false,
		)
	);
}
add_action( 'init', 'edacp_plugin_updater' );

/************************************
* Code below is for the options page.
*/

add_action(
	'edac_license_tab',
	function () {
		edacp_license_page();
	}
);

/**
 * Lisense Page
 *
 * @return void
 */
function edacp_license_page() {

	$license = get_option( 'edacp_license_key' );
	$status  = get_option( 'edacp_license_status' );
	?>
		<h2><?php esc_html_e( 'License Settings', 'edacp' ); ?></h2>
		<form method="post" action="options.php">

			<?php settings_fields( 'edacp_license' ); ?>

			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php esc_html_e( 'License Key', 'edacp' ); ?>
						</th>
						<td>
							<input id="edacp_license_key" name="edacp_license_key" type="text" class="regular-text" value="<?php echo esc_attr( $license ); ?>" />
							<label class="description" for="edacp_license_key">
								<?php if ( false !== $status && 'valid' === $status ) : ?>
									<span style="color:green;"><?php esc_html_e( 'active', 'edacp' ); ?></span>
								<?php elseif ( false !== $status && 'expired' === $status ) : ?>
									<span style="color:red;"><?php esc_html_e( 'expired', 'edacp' ); ?></span>
								<?php else : ?>
									<?php esc_html_e( 'Enter your license key', 'edacp' ); ?>
								<?php endif; ?>
							</label>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" valign="top"></th>
						<td>
							<?php if ( false !== $status && 'valid' === $status ) : ?>
								<?php wp_nonce_field( 'edd_sample_nonce', 'edd_sample_nonce' ); ?>
								<input type="submit" class="button-primary" name="edd_license_deactivate" value="<?php esc_attr_e( 'Deactivate License', 'edacp' ); ?>">
							<?php else : ?>
								<?php wp_nonce_field( 'edd_sample_nonce', 'edd_sample_nonce' ); ?>
								<input type="submit" class="button-primary" name="edd_license_activate" value="<?php esc_attr_e( 'Activate License', 'edacp' ); ?>"/>
							<?php endif; ?>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	<?php
}

/**
 * Register License Options
 *
 * @return void
 */
function edacp_register_option() {
	// creates our settings in the options table.
	register_setting( 'edacp_license', 'edacp_license_key', 'edacp_sanitize_license' );
}
add_action( 'admin_init', 'edacp_register_option' );

/**
 * Sanitize License
 *
 * @param string $new_licence_key The new license key.
 * @return string
 */
function edacp_sanitize_license( $new_licence_key ) {
	$old_licence_key = get_option( 'edacp_license_key' );
	if ( $old_licence_key && $old_licence_key !== $new_licence_key ) {
		delete_option( 'edacp_license_status' ); // new license has been entered, so must reactivate.
	}
	return $new_licence_key;
}

/**
 * Activate license
 *
 * @return void
 */
function edacp_activate_license() {

	// listen for our activate button to be clicked.
	if ( isset( $_POST['edd_license_activate'] ) ) {

		// run a quick security check.
		if ( ! check_admin_referer( 'edd_sample_nonce', 'edd_sample_nonce' ) ) {
			return; // get out if we didn't click the Activate button.
		}

		// sanitize and update license key option.
		if ( isset( $_POST['edacp_license_key'] ) ) {
			$license = edacp_sanitize_license( sanitize_text_field( $_POST['edacp_license_key'] ) );
			update_option( 'edacp_license_key', $license );
		}

		// retrieve the license from the database.
		$license = trim( get_option( 'edacp_license_key' ) );

		// data to send in our API request.
		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_name'  => rawurlencode( EDACP_ITEM_NAME ), // the name of our product in EDD.
			'url'        => home_url(),
		);

		// Call the custom API.
		$response = wp_remote_post(
			EDACP_STORE_URL,
			array(
				'timeout'   => 15, // phpcs:ignore WordPressVIPMinimum.Performance.RemoteRequestTimeout.timeout_timeout -- 15 seconds is needed for now.
				'sslverify' => false,
				'body'      => $api_params,
			)
		);

		// make sure the response came back okay.
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			$message = ( is_wp_error( $response ) )
				? $response->get_error_message()
				: esc_html__( 'An error occurred, please try again.', 'edacp' );
		} else {

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( isset( $license_data->error ) ) {
				// save license error as an option.
				update_option( 'edacp_license_error', $license_data->error );
			} else {
				delete_option( 'edacp_license_error' );
			}
		}

		// Check if anything passed on a message constituting a failure.
		if ( ! empty( $message ) ) {
			$base_url = admin_url( 'admin.php?page=' . EDD_PLUGIN_LICENSE_PAGE . '&tab=license' );
			$redirect = add_query_arg(
				array(
					'sl_activation' => 'false',
					'message'       => rawurlencode( $message ),
				),
				$base_url
			);

			// phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect -- $redirect is a safe url.
			wp_redirect( $redirect );
			exit();
		}

		update_option( 'edacp_license_status', $license_data->license );
		wp_safe_redirect( admin_url( 'admin.php?page=' . EDD_PLUGIN_LICENSE_PAGE . '&tab=license' ) );
		exit();
	}
}
add_action( 'admin_init', 'edacp_activate_license' );

/**
 * Deactivate license
 * This will decrease the site count
 *
 * @return void
 */
function edacp_deactivate_license() {

	// listen for our activate button to be clicked.
	if ( isset( $_POST['edd_license_deactivate'] ) ) {

		// run a quick security check.
		if ( ! check_admin_referer( 'edd_sample_nonce', 'edd_sample_nonce' ) ) {
			return; // get out if we didn't click the Activate button.
		}

		edacp_deactivate_process( home_url(), true );

	}
}
add_action( 'admin_init', 'edacp_deactivate_license' );

/**
 * Deactivate a license key
 *
 * @param string $url deactivation url.
 * @param string $redirect redirect url.
 * @return void
 */
function edacp_deactivate_process( $url, $redirect ) {
	// retrieve the license from the database.
	$license = trim( get_option( 'edacp_license_key' ) );

	// data to send in our API request.
	$api_params = array(
		'edd_action' => 'deactivate_license',
		'license'    => $license,
		'item_name'  => rawurlencode( EDACP_ITEM_NAME ), // the name of our product in EDD.
		'url'        => $url,
	);

	// Call the custom API.
	$response = wp_remote_post(
		EDACP_STORE_URL,
		array(
			'timeout'   => 15, // phpcs:ignore WordPressVIPMinimum.Performance.RemoteRequestTimeout.timeout_timeout -- 15 seconds is needed for now.
			'sslverify' => false,
			'body'      => $api_params,
		)
	);

	// make sure the response came back okay.
	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

		$message = is_wp_error( $response )
			? $response->get_error_message()
			: __( 'An error occurred, please try again.', 'edacp' );

		if ( true === $redirect ) {
			$base_url = admin_url( 'admin.php?page=' . EDD_PLUGIN_LICENSE_PAGE . '&tab=license' );
			$redirect = add_query_arg(
				array(
					'sl_activation' => 'false',
					'message'       => rawurlencode( $message ),
				),
				$base_url
			);

			// phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect -- $redirect is a safe url.
			wp_redirect( $redirect );
			exit();
		}
	}

	// decode the license data.
	$license_data = json_decode( wp_remote_retrieve_body( $response ) );

	// $license_data->license will be either "deactivated" or "failed".
	if ( 'deactivated' === $license_data->license ) {
		delete_option( 'edacp_license_key' );
		delete_option( 'edacp_license_status' );
	}

	if ( true === $redirect ) {
		wp_safe_redirect( admin_url( 'admin.php?page=' . EDD_PLUGIN_LICENSE_PAGE . '&tab=license' ) );
		exit();
	}
}

/**
 * Deactivate license on domain change
 *
 * This illustrates how to check if
 * a license key is still valid
 * the updater does this for you,
 * so this is only needed if you
 * want to do something custom
 *
 * @return void
 */
function edacp_deactivate_license_on_domain_change() {
	$edacp_license_url = base64_decode( get_option( 'edacp_license_url' ) );

	if ( ! $edacp_license_url ) {
		update_option( 'edacp_license_url', base64_encode( home_url() ) );
	}

	$edacp_license_url = base64_decode( get_option( 'edacp_license_url' ) );

	if ( home_url() !== $edacp_license_url ) {
		edacp_deactivate_process( $edacp_license_url, false );
		delete_option( 'edacp_license_url' );
	}
}

/**
 * License check
 *
 * @return void
 */
function edacp_check_license() {

	if ( ! get_option( 'edacp_license_key' ) ) {
		return;
	}

	$license = trim( get_option( 'edacp_license_key' ) );

	$api_params = array(
		'edd_action'  => 'check_license',
		'license'     => $license,
		'item_id'     => EDACP_ITEM_ID,
		'item_name'   => rawurlencode( EDACP_ITEM_NAME ),
		'url'         => home_url(),
		'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production',
	);

	// Call the custom API.
	$response = wp_remote_post(
		EDACP_STORE_URL,
		array(
			'timeout'   => 15, // phpcs:ignore WordPressVIPMinimum.Performance.RemoteRequestTimeout.timeout_timeout -- 15 seconds is needed for now.
			'sslverify' => false,
			'body'      => $api_params,
		)
	);
	if ( is_wp_error( $response ) ) {
		return false;
	}

	$license_data = json_decode( wp_remote_retrieve_body( $response ) );

	if ( 'valid' !== $license_data->license ) {
		update_option( 'edacp_license_status', $license_data->license );
	}
}
add_action( 'edacp_check_license_hook', 'edacp_check_license' );

/**
 * License check cron schedule
 *
 * @return void
 */
function edacp_check_license_cron() {
	if ( ! wp_next_scheduled( 'edacp_check_license_hook' ) ) {
		wp_schedule_event( time(), 'daily', 'edacp_check_license_hook' );
	}
}
add_action( 'admin_init', 'edacp_check_license_cron' );

/**
 * This is a means of catching errors from the activation method above and displaying it to the customer
 */
function edacp_admin_notices() {

	$error       = get_option( 'edacp_license_error' );
	$license_url = get_bloginfo( 'url' ) . '/wp-admin/admin.php?page=accessibility_checker_settings&tab=license';

	if ( $error ) {

		switch ( $error ) {

			case 'expired':
				$message = sprintf(
					/* translators: %1$s: item name, %2$s: item name */
					__( 'Your %1$s license has expired. This plugin requires an active license key to function. <a href="https://my.equalizedigital.com/?utm_source=wpadmin" target="_blank">Please renew your license</a> to continue using %2$s.', 'edacp' ),
					EDACP_ITEM_NAME,
					EDACP_ITEM_NAME
				);
				break;

			case 'disabled':
			case 'revoked':
				$message = sprintf(
					/* translators: %s: item name */
					__( 'Your %s license key has been disabled.', 'edacp' ),
					EDACP_ITEM_NAME
				);
				break;

			case 'missing':
				$message = sprintf(
					/* translators: %1$s: item name, %2$s: license url, %3$s: item name */
					__( 'Your %1$s license is invalid. This plugin requires an active license key to function. Please <a href="%2$s">reenter your license key</a> or <a href="https://my.equalizedigital.com/?utm_source=wpadmin" target="_blank">purchase a new key</a> to use %3$s.', 'edacp' ),
					EDACP_ITEM_NAME,
					$license_url,
					EDACP_ITEM_NAME
				);
				break;

			case 'invalid':
			case 'site_inactive':
				$message = sprintf(
					/* translators: %s: item name */
					__( 'Your %s license is not active for this URL.', 'edacp' ),
					EDACP_ITEM_NAME
				);
				break;

			case 'item_name_mismatch':
				/* translators: %s: item name */
				$message = sprintf( __( 'This appears to be an invalid license key for %s.', 'edacp' ), EDACP_ITEM_NAME );
				break;

			case 'no_activations_left':
				/* translators: %s: item name */
				$message = sprintf( __( 'Your %s license key has reached its activation limit.', 'edacp' ), EDACP_ITEM_NAME );
				break;

			default:
				/* translators: %s: item name */
				$message = sprintf( __( 'An error occurred trying to activate %s., please try again.', 'edacp' ), EDACP_ITEM_NAME );
				break;
		}
	} elseif ( ! EDACP_KEY_VALID ) {
		echo '<div class="notice notice-error"><p>';
		printf(
			// translators: %1$s: item name, %2$s: license url.
			__( 'Thank you for activating %1$s. This plugin requires an active license key to function. Please <a href="%2$s">enter your license key</a>.', 'edacp' ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			esc_html( EDACP_ITEM_NAME ),
			esc_url( $license_url )
		);
		echo '</p></div>';
		return;
	}

	if ( isset( $message ) ) {
		printf( '<div class="notice notice-error"><p>%s</p></div>', wp_kses_post( $message ) );
	}
}
add_action( 'admin_notices', 'edacp_admin_notices' );
