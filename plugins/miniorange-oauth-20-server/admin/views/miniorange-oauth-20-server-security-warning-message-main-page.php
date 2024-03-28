<?php

/**
 * The file that defines the security warning message view for the plugin
 *
 * This file is used to display the security warning message on the main page of the plugin.
 *
 * @link       https://www.miniorange.com
 * @since      1.0.0
 *
 * @package    Miniorange_Oauth_20_Server
 * @subpackage Miniorange_Oauth_20_Server/admin/views
 */

/**
 * The security warning message view for the plugin.
 *
 * This file is used to display the security warning message on the main page of the plugin.
 *
 * @return void
 */
function mo_oauth_server_security_warning_message_view_main_page()
{
	// Security warning notice on plugin page.
	$jwks_uri_hit_count = get_option('mo_oauth_server_jwks_uri_hit_count');
	if ($jwks_uri_hit_count >= 10) {
		$is_security_warning_mail_sent = get_option('mo_oauth_server_is_security_warning_mail_sent');
		if (10 == $jwks_uri_hit_count && false == $is_security_warning_mail_sent) {
			$email       = get_option('admin_email');
			$login_url   = get_option('host_name') . '/moas/login';
			$username    = get_option('mo_oauth_admin_email');
			$payment_url = get_option('host_name') . '/moas/initializepayment';

			// Not adding nonce parameter as form is posted to external URL.
			$message  = 'Dear Customer, <br><br>

			You are at a Security Risk for the WordPress OAuth Server Plugin. It is because you are using the free version of the plugin for JWT Signing, where new keys are not generated for each configuration and are common for all users.<br><br>
			You can			
			<form style="display:inline;" id="email_loginform" action="' . esc_url_raw($login_url) . '" target="_blank" method="post">
				<input style="display:none;" type="email" name="username" value="' . esc_attr($username) . '" />
				<input style="display:none;" type="text" name="redirectUrl" value="' . esc_url_raw($payment_url) . '" />
				<input style="display:none;" type="text" name="requestOrigin" value="wp_oauth_server_enterprise_plan"  />
				<button style="border: none; background: transparent; color: blue; text-decoration: underline;" type="submit">Click here to Upgrade to Premium</button>
			</form>
			for RSA support with dynamic keys to avoid this risk.<br><br>
			<i><b>Note:</b> The free plugin will stay functional but remain subject to this risk.</i>
			<br><br>
			For more information, you can contact us at wpidpsupport@xecurify.com. <br><br>

	
			Thank you,<br>
			miniOrange Team';
			$customer = new Mo_Oauth_Server_Customer();
			$customer->mo_oauth_send_jwks_alert($email, $message, 'WP OAuth Server Alert | You are at a Security Risk - ' . $email);
			
			$is_security_warning_mail_sent = 1;
			update_option('mo_oauth_server_is_security_warning_mail_sent', $is_security_warning_mail_sent, false);
		}
?>
		<article class="message is-warning ml-0 mr-5 my-3">
			<div class="message-header">
				<p class="is-size-5"><i class="fas fa-warning"></i> Warning!</p>
			</div>
			<?php
			$login_url   = get_option('host_name') . '/moas/login';
			$username    = get_option('mo_oauth_admin_email');
			$payment_url = get_option('host_name') . '/moas/initializepayment';
			// Not adding nonce parameter as form is posted to external URL.
			?>
			<div class="message-body pt-0">
				<form style="display:none;" id="loginform" action="<?php echo esc_url_raw($login_url) ?>" target="_blank" method="post">
					<input type="email" name="username" value="<?php echo esc_attr($username) ?>" />
					<input type="text" name="redirectUrl" value="<?php echo esc_url_raw($payment_url) ?>" />
					<input type="text" name="requestOrigin" value="wp_oauth_server_enterprise_plan" />
				</form>
				<br>
				<p>You are at a Security Risk for the WordPress OAuth Server Plugin. It is because you are using the free version of the plugin for JWT Signing, where new keys are not generated for each configuration and are common for all users. You can <a onclick="upgrade_plugin_form()"><strong>Upgrade</strong></a> to Premium for RSA support with dynamic keys to avoid this risk.</p>
				<br>
				<p>Contact us at <a href="mailto:wpidpsupport@xecurify.com" style="text-decoration: none"><strong>wpidpsupport@xecurify.com</strong></a> for upgrading to premium or any other query.</p>
			</div>
		</article>
		<script>
			function upgrade_plugin_form() {
				jQuery("#loginform").submit()
			}
		</script>
<?php
	}
}
