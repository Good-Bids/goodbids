<?php
/**
 * Security Warning Message View
 *
 * Provides the view for the security warning message.
 *
 * @link       https://www.miniorange.com
 * @since      1.0.0
 *
 * @package    Miniorange_Oauth_20_Server
 * @subpackage Miniorange_Oauth_20_Server/admin/views
 */

/**
 * Security Warning Message View
 *
 * @return void
 */
function mo_oauth_server_security_warning_message_view() {
	// If on plugin page don't show global notice,
	// Also don't show when mo_oauth_server_hide_security_warning_admin is set to 1 in wp_options.
	$jwks_uri_hit_count             = get_option( 'mo_oauth_server_jwks_uri_hit_count' );
	$hide_security_notice_permanent = get_option( 'mo_oauth_server_hide_security_warning_admin' );
	$hide_security_notice_temporary = get_option( 'mo_oauth_server_security_warning_remind_date' ) > time();

	if ( ! isset( $_GET['page'] ) && $jwks_uri_hit_count >= 10 && ! boolval( $hide_security_notice_permanent ) && ! $hide_security_notice_temporary ) { // phpcs:ignore WordPress.Security.NonceVerification -- This is only to get the page to display the security warning
		?>
			<div class="notice notice-info mo_security_banner mo_security_banner-admin">
				<div style="display: block;" id="mo_main-security-warning-banner" class="mo_security_banner-content">
					<div class="mo_security_banner-header">miniOrange OAuth Server</div>
					<br>
				<?php
				$login_url   = get_option( 'host_name' ) . '/moas/login';
				$username    = get_option( 'mo_oauth_admin_email' );
				$payment_url = get_option( 'host_name' ) . '/moas/initializepayment';
				// Not adding nonce parameter as form is posted to external URL.
				echo '
                <form style="display:none;" id="admin_loginform" action="' . esc_url_raw( $login_url ) . '" target="_blank" method="post"> ';
				echo '
                <input type="email" name="username" value="' . esc_attr( $username ) . '" />
                <input type="text" name="redirectUrl" value="' . esc_url_raw( $payment_url ) . '" />
                <input type="text" name="requestOrigin" value="wp_oauth_server_enterprise_plan"  />
                </form>';
				?>
					<script>
						function upgrade_plugin_dashboard_form() {
							jQuery("#admin_loginform").submit()
						}
					</script>
					<div class="mo_button-tab">
						<a class="mo_security-warning-contact button button-primary button-large" onclick="upgrade_plugin_dashboard_form()">Upgrade Now</a>
						<button class="button button-primary button-large mo_security_banner-close-admin">X</button>
					</div>
					<div>
						<span class="mo_warning-icon dashicons dashicons-warning"></span>
						<b class="mo_security_warning">SECURITY RISK!</b>
						<br><br>
						<span class="mo_notice-important">You are at a Security Risk for the WordPress OAuth Server Plugin. It is because you are using the free version of the plugin for JWT Signing, where new keys are not generated for each configuration and are common for all users.</span>
					</div>
				</div>
				<form action="#" method="POST">
					<?php wp_nonce_field( 'mo_oauth_server_security_warning_form', 'mo_oauth_server_security_warning_form_field' ); ?>
					<div style="display: none;" class="mo_security_banner-content" id="mo_security-warning-confirmation-admin">
						<p>Are you sure want to dismiss this warning?</p>
						<p>The free plugin will stay functional but remain subject to this risk</p>
						<input type="submit" name="mo_admin_security_dismiss" id="mo_admin_security_dismiss" class="button button-red button-large" value="Yes, Dismiss this warning"></input>
						<input type="submit" name="mo_admin_sw_remind_later" id="mo_admin_sw_remind_later" class="button button-primary button-large" value="Remind me later"></input>
					</div>
				</form>
			</div>

			<script>
				const security_warning_banner_close_admin = document.querySelector('.mo_security_banner-close-admin');
				security_warning_banner_close_admin.addEventListener('click', function() {
					const security_warning_confirm_admin = document.querySelector("#mo_security-warning-confirmation-admin");
					const main_security_warning_banner = document.querySelector("#mo_main-security-warning-banner");
					security_warning_confirm_admin.style.display = "block";
					main_security_warning_banner.style.display = "none";
				});
				const admin_sw_dismiss = document.querySelector("#mo_admin_security_dismiss");
				<?php
				if ( isset( $_REQUEST['mo_oauth_server_security_warning_form_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['mo_oauth_server_security_warning_form_field'] ) ), 'mo_oauth_server_security_warning_form' ) ) {
					if ( array_key_exists( 'mo_admin_security_dismiss', $_POST ) ) {
						update_option( 'mo_oauth_server_hide_security_warning_admin', 1 );
						?>
						const security_warning_banner_admin = document.querySelector(".mo_security_banner-admin");
						security_warning_banner_admin.style.display = "none";
						<?php
					}
					if ( array_key_exists( 'mo_admin_sw_remind_later', $_POST ) ) {
						$current_timestamp = time();
						$remind_timestamp  = strtotime( '+1 days', $current_timestamp );
						update_option( 'mo_oauth_server_security_warning_remind_date', $remind_timestamp );
						?>
						const expiry_banner_admin = document.querySelector(".mo_security_banner-admin");
						expiry_banner_admin.style.display = "none";
						<?php
					}
				}
				?>
			</script>
			<?php
	}
}
