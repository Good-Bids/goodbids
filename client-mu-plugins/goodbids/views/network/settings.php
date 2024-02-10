<?php
/**
 * Network Admin Settings
 *
 * @since 1.0.0
 * @package GoodBids
 */

use GoodBids\Network\Settings;

$form_url = network_admin_url( 'admin.php?page=' . Settings::PAGE_SLUG );
?>
<div class="wrap">
	<h2><?php esc_html_e( 'GoodBids Settings', 'goodbids' ); ?></h2>

	<form method="post" action="<?php echo esc_url( $form_url ); ?>">
		<?php
		settings_errors( Settings::PAGE_SLUG );
		settings_fields( Settings::SETTINGS_META_KEY );
		do_settings_sections( Settings::PAGE_SLUG );
		wp_nonce_field( Settings::PAGE_SLUG, Settings::SETTINGS_NONCE );
		submit_button();
		?>
	</form>
</div>
