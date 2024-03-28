<?php
/**
 * Provide a navigation header view for the plugin.
 *
 * This file is used to markup the navigation header of the plugin.
 *
 * @link       https://www.miniorange.com
 * @since      1.0.0
 *
 * @package    Miniorange_Oauth_20_Server
 * @subpackage Miniorange_Oauth_20_Server/admin/views
 */

?>
<nav class="navbar is-spaced is-paddingless mr-5 mt-2" role="navigation" aria-label="main navigation">
	<div class="navbar-brand">
		<a class="navbar-item" href="admin.php?page=mo_oauth_server_settings">
			<img src="<?php echo esc_attr( MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_URL ) . 'assets/logo.png'; ?>" alt="miniOrange logo" width="30" height="100">
			<h1 class="ha p-4s-text-dark is-size-5 has-text-weight-bold ml-2">miniOrange OAuth/OpenID Connect Server</h1>
		</a>
		<a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
			<span aria-hidden="true"></span>
			<span aria-hidden="true"></span>
			<span aria-hidden="true"></span>
		</a>
	</div>
	<div id="navbarBasicExample" class="navbar-menu">
		<div class="navbar-end">
			<div class="navbar-item">
				<div class="buttons">
					<a href="admin.php?page=mo_oauth_server_settings&tab=troubleshooting" class="button is-blue is-outlined">
						<strong><i class="fa-solid fa-screwdriver-wrench"></i> Troubleshooting</strong>
					</a>
					<a href="admin.php?page=mo_oauth_server_settings&tab=licensing_tab" id="upgrade_now" class="button">
						<strong><i class="fa-solid fa-circle-up"></i> Upgrade Now</strong>
					</a>
					<a href="admin.php?page=mo_oauth_server_settings" class="button is-blue is-outlined">
						<span class="icon is-justify-content-center is-align-items-center mx-1">
							<i class="fa-solid fa-home fa-lg"></i>
						</span>
					</a>
				</div>
			</div>
		</div>
	</div>
</nav>

<div id="mo-oauth-server-contact-us-modal" class="modal">
	<div class="modal-background"></div>
	<div class="modal-card">
		<header class="modal-card-head">
			<p class="modal-card-title">Modal title</p>
			<button class="delete" aria-label="close"></button>
		</header>
		<section class="modal-card-body">
			<!-- Content ... -->
		</section>
		<footer class="modal-card-foot">
			<button class="button is-success">Save changes</button>
			<button class="button">Cancel</button>
		</footer>
	</div>
</div>

<!-- Email Support Section -->
<section id="email_popup">
	<div class="email_popup is-flex is-align-items-center">
		<div class="email_popup_dialog pt-3 is-clickable js-modal-trigger" data-target="email-modal">
			<p class="has-text-weight-normal is-size-6 has-text-centered has-text-white">Hi There!</p>
			<p class="has-text-weight-normal is-size-6 has-text-centered has-text-white">Need help? Contact Us</p>
		</div>
		<span class="email_popup_triangle mr-1"></span>
		<div class="email_popup_button is-align-items-center is-justify-content-center">
			<button class="js-modal-trigger" data-target="email-modal">
				<i class="fa-solid fa-envelope has-text-white is-clickable"></i>
			</button>
		</div>
		<!-- Email Modal -->
		<div class="modal" id="email-modal">
			<div class="modal-background"></div>
			<div class="modal-card">
				<header class="modal-card-head">
					<p class="modal-card-title">Contact miniOrange Support</p>
					<button class="delete" aria-label="close"></button>
				</header>
				<form method="POST">
					<?php wp_nonce_field( 'mo_oauth_server_contact_us_form_dashboard', 'mo_oauth_server_contact_us_form_dashboard_nonce' ); ?>
					<section class="modal-card-body">
						<div class="field">
							<label class="label">Email</label>
							<div class="control has-icons-left">
								<input class="input" type="email" name="mo_oauth_contact_us_email" placeholder="person@example.com" required>
								<span class="icon is-small is-left">
									<i class="fas fa-envelope"></i>
								</span>
							</div>
						</div>
						<input type="hidden" name="mo_oauth_contact_us_phone" value="">
						<div class="field">
							<label class="label">Query</label>
							<div class="control">
								<textarea class="textarea" name="mo_oauth_contact_us_query" placeholder="What can we help you with?" required></textarea>
							</div>
						</div>
					</section>
					<footer class="modal-card-foot is-justify-content-center">
						<button type="submit" class="button is-blue">Submit</button>
						<button type="button" class="button button-cancel is-blue is-outlined">Cancel</button>
					</footer>
				</form>
			</div>
		</div>
	</div>
</section>
