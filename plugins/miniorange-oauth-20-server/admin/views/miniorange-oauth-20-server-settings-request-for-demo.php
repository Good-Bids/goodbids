<?php
/**
 * Provide a request for demo view for the plugin.
 *
 * This file is used to markup the request for demo of the plugin.
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
		<h2 class="is-size-5 has-text-weight-semibold miniorange-oauth-20-server-card-title">Trials Available</h2>
	</div>

	<h3 class="has-text-weight-semibold is-blue">Request for a Demo/Trial</h3>
	<p class="mt-4 is-size-6">Want to try out the paid features before purchasing the license? Just submit the demo request and we will setup a demo for you.</p>

	<div class="columns is-3 mt-3">
		<div class="column is-two-thirds">
			<form method="POST" id="trial_demo_form">
				<?php wp_nonce_field( 'mo_oauth_server_trial_demo_form', 'mo_oauth_server_trial_demo_nonce' ); ?>
				<input type="hidden" name="mo_auto_create_demosite_demo_plan" value="miniorange-oauth-server-enterprise@31.5.0">
				<div class="field">
					<label class="label">Email</label>
					<div class="control has-icons-left">
						<input class="input" type="email" name="mo_auto_create_demosite_email" placeholder="person@example.com" required="">
						<span class="icon is-small is-left">
							<i class="fas fa-envelope"></i>
						</span>
					</div>
					<p class="help"><span class="has-text-weight-semibold">Note: </span>We will use this email to setup the demo for you.</p>
				</div>
				<div class="field">
					<label class="label">Usecase</label>
					<div class="control">
						<textarea class="textarea" name="mo_auto_create_demosite_usecase" placeholder="Example: Login into other sites using WordPress credentials." rows="2" required="" data-gramm="false" wt-ignore-input="true"></textarea>
					</div>
				</div>
				<div class="field is-grouped is-grouped-centered">
					<div class="control">
						<button type="submit" class="button is-blue">Submit</button>
					</div>
				</div>
			</form>
		</div>
		<div class="column is-one-third my-auto miniorange-oauth-20-server-account-setup-card">
			<p class="has-text-white is-size-6">You can test out all the premium plugin features as per your requirements on a demo site.
				You will receive credentials for a demo site where our premium plugin is installed via the email provided by you.</p>
		</div>
	</div>

	<hr />

	<h3 class="has-text-weight-semibold is-blue">Request for Video Demo</h3>
	<p class="mt-4 is-size-6">Want to try out the paid features before purchasing the license? Just submit the demo request and we will setup a demo for you.</p>

	<div class="columns is-3 mt-3">
		<div class="column is-two-thirds">
			<form method="POST" id="video_demo_form">
				<?php wp_nonce_field( 'mo_oauth_server_trial_video_demo', 'mo_oauth_server_trial_video_demo_nonce' ); ?>
				<input type="hidden" name="option" value="mo_oauth_video_demo">
				<div class="field">
					<label class="label">Email</label>
					<div class="control has-icons-left">
						<input class="input" type="email" name="mo_oauth_video_demo_email" placeholder="person@example.com" required>
						<span class="icon is-small is-left">
							<i class="fas fa-envelope"></i>
						</span>
					</div>
					<p class="help"><span class="has-text-weight-semibold">Note: </span>We will use this email to setup the demo for you.</p>
				</div>
				<div class="field">
					<label class="label">Date</label>
					<div class="control has-icons-left">
						<input class="input" type="date" name="mo_oauth_video_demo_request_date" placeholder="DD-MM-YYY" required>
						<span class="icon is-small is-left">
							<i class="fa-solid fa-calendar"></i>
						</span>
					</div>
				</div>
				<div class="field">
					<label class="label">Time</label>
					<div class="control has-icons-left">
						<input class="input" type="time" name="mo_oauth_video_demo_request_time" placeholder="(Eg:- 12:56, 18:30, etc.)" required>
						<input type="hidden" name="mo_oauth_video_demo_time_diff" id="mo_oauth_video_demo_time_diff">
						<span class="icon is-small is-left">
							<i class="fa-solid fa-clock"></i>
						</span>
					</div>
				</div>
				<div class="field">
					<label class="label">Usecase</label>
					<div class="control">
						<textarea class="textarea" name="mo_oauth_video_demo_request_usecase_text" placeholder="Example: Login into other sites using WordPress credentials." rows="2" required></textarea>
					</div>
				</div>
				<div class="field is-grouped is-grouped-centered">
					<div class="control">
						<button type="submit" class="button is-blue">Submit</button>
					</div>
				</div>
			</form>
		</div>
		<div class="column is-one-third my-auto">

			<div class="mb-4">
				<img src="<?php echo esc_attr( MINIORANGE_OAUTH_20_SERVER_PLUGIN_DIR_URL ) . 'assets/setup-gif.gif'; ?>" alt="">
			</div>

			<div class="column miniorange-oauth-20-server-account-setup-card">
				<p class="has-text-white is-size-6">You can set up a screen share meeting with our developers to walk you through our plugin featuers providing the overview of all Premium Plugin features.</p>
			</div>

		</div>
	</div>
</div>

<script>
	var mo_date = new Date();
	var timezone = mo_date.getTimezoneOffset();
	document.getElementById("mo_oauth_video_demo_time_diff").value = timezone;
</script>


<!-- This div close the parent container of main template. -->
</div>
