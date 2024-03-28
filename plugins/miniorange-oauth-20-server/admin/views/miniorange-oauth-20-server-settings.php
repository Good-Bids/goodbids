<?php

/**
 * Provide a settings page view for the plugin.
 *
 * This file is used to markup the settings page of the plugin.
 *
 * @link       https://www.miniorange.com
 * @since      1.0.0
 *
 * @package    Miniorange_Oauth_20_Server
 * @subpackage Miniorange_Oauth_20_Server/admin/views
 */

?>

<?php
require MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_PATH . 'admin/views/miniorange-oauth-20-server-nav-header.php';
$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
?>

<div class="columns my-2 p-0 mx-0">
	<aside class="column has-background-white is-one-quarter mr-3">
		<nav class="menu px-2 pt-0">
			<h2 class="is-size-5 has-text-weight-semibold miniorange-oauth-20-server-card-title">Dashboard</h2>
			<ul class="menu-list">
				<li><a class="nav-link box my-3 p-4 has-text-weight-bold <?php echo (('config' === $current_tab) || ('mo_oauth_server_settings' === $current_tab && empty($current_tab))) ? 'miniorange-oauth-20-server-nav-active' : ''; ?>" href="admin.php?page=mo_oauth_server_settings&tab=config" data-content="configure-oauth-clients">
						<i class="fa-solid fa-wrench mr-2"></i>
						Configure your Application</a></li>
				<li><a class="nav-link box my-3 p-4 has-text-weight-bold <?php echo ('advance_settings' === $current_tab) ? 'miniorange-oauth-20-server-nav-active' : ''; ?>" href="admin.php?page=mo_oauth_server_settings&tab=advance_settings" data-content="settings">
						<i class="fa-solid fa-gear mr-2"></i>
						Advance Settings</a></li>
			</ul>
			<h2 class="is-size-5 has-text-weight-semibold miniorange-oauth-20-server-card-title">Premium</h2>
			<ul class="menu-list">
				<li><a class="nav-link box my-3 p-4 has-text-weight-bold <?php echo ('server_response' === $current_tab) ? 'miniorange-oauth-20-server-nav-active' : ''; ?>" href="admin.php?page=mo_oauth_server_settings&tab=server_response" data-content="server-response">
						<i class="fa-solid fa-server mr-2"></i>
						Server Response</a></li>
				<li><a class="nav-link box my-3 p-4 has-text-weight-bold <?php echo ('premium_features' === $current_tab) ? 'miniorange-oauth-20-server-nav-active' : ''; ?>" href="admin.php?page=mo_oauth_server_settings&tab=premium_features" data-content="premium-features" id="premium_features_link">
						<i class="fa-regular fa-gem mr-2"></i>
						Premium Features</a></li>
			</ul>
			<h2 class="is-size-5 has-text-weight-semibold miniorange-oauth-20-server-card-title">Support</h2>
			<ul class="menu-list">
				<li><a class="nav-link box my-3 p-4 has-text-weight-bold <?php echo ('contact_us' === $current_tab) ? 'miniorange-oauth-20-server-nav-active' : ''; ?>" href="admin.php?page=mo_oauth_server_settings&tab=contact_us" data-content="contact-us">
						<i class="fa-solid fa-headset mr-2"></i>
						Contact Us</a></li>
				<li><a class="nav-link box my-3 p-4 has-text-weight-bold <?php echo ('trials_available' === $current_tab) ? 'miniorange-oauth-20-server-nav-active' : ''; ?>" href="admin.php?page=mo_oauth_server_settings&tab=trials_available" data-content="trials-available">
						<i class="fa-solid fa-person-chalkboard mr-2"></i>
						Trials Available</a></li>
				<li><a class="nav-link box my-3 p-4 has-text-weight-bold <?php echo ('troubleshooting' === $current_tab) ? 'miniorange-oauth-20-server-nav-active' : ''; ?>" href="admin.php?page=mo_oauth_server_settings&tab=troubleshooting" data-content="troubleshooting">
						<i class="fa-solid fa-screwdriver-wrench mr-2"></i>
						Troubleshooting</a></li>
				<li><a class="nav-link box my-3 p-4 has-text-weight-bold <?php echo ('account_setup' === $current_tab) ? 'miniorange-oauth-20-server-nav-active' : ''; ?>" href="admin.php?page=mo_oauth_server_settings&tab=account_setup" data-content="account-setup">
						<i class="fa-solid fa-circle-user mr-2"></i>
						Account Setup</a></li>
				<li><a class="nav-link box my-3 p-4 has-text-weight-bold <?php echo ('licensing_tab' === $current_tab) ? 'miniorange-oauth-20-server-nav-active' : ''; ?>" href="admin.php?page=mo_oauth_server_settings&tab=licensing_tab" data-content="licensing-plans">
						<i class="fa-solid fa-id-badge mr-2"></i>
						Licensing Plans</a></li>
				<li><a class="nav-link box my-3 p-4 has-text-weight-bold <?php echo ('integrations' === $current_tab) ? 'miniorange-oauth-20-server-nav-active' : ''; ?>" href="admin.php?page=mo_oauth_server_settings&tab=integrations" data-content="integrations">
						<i class="fa-solid fa-circle-plus mr-2"></i>
						Integrations</a></li>
			</ul>
		</nav>
	</aside>

	<!-- All the pages that inherit this template will have to close
the parent div of aside. -->