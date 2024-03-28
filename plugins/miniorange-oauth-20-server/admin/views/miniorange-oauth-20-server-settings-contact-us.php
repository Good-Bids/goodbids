<?php
/**
 * Provide a contact us view for the plugin.
 *
 * This file is used to markup the contact us form.
 *
 * @link       https://www.miniorange.com
 * @since      1.0.0
 *
 * @package    Miniorange_Oauth_20_Server
 * @subpackage Miniorange_Oauth_20_Server/admin/views
 */

?>

<?php
$is_reffered_by_license_page = ( get_query_var( 'ref_page' ) == 'licensing' ) ? true : false;
if ( ! $is_reffered_by_license_page ) {
	$current_tab = isset( $_GET['ref_page'] ) ? sanitize_text_field( wp_unslash( $_GET['ref_page'] ) ) : '';
	if ( 'licensing' === $current_tab ) {
		$is_reffered_by_license_page = true;
	}
}
?>

<div class="column has-background-white mr-5 px-5">
	<div class="mb-4">
		<h2 class="is-size-5 has-text-weight-semibold miniorange-oauth-20-server-card-title">Contact Us</h2>
	</div>
	<p class="is-size-6">Need any help? Just send us a query so we can help you.</p>

	<form method="POST" id="contact_us_form">
		<?php wp_nonce_field( 'mo_oauth_server_contact_us_form', 'mo_oauth_server_contact_us_nonce' ); ?>
		<input type="hidden" name="option" value="mo_oauth_contact_us_query_option" />
		<div class="field mt-2">
			<label class="label">Email</label>
			<div class="control has-icons-left">
				<input class="input" type="email" name="mo_oauth_contact_us_email" placeholder="person@example.com" required>
				<span class="icon is-small is-left">
				<i class="fas fa-envelope"></i>
				</span>
			</div>
		</div>
		<div class="field">
			<label class="label">Phone Number</label>
			<div class="control has-icons-left">
				<input class="input" type="tel" id="contact_us_phone" name="mo_oauth_contact_us_phone" placeholder="e.g. +1 702 123 4567" pattern="[\+]?[0-9]{1,4}[\s]?([0-9]{4,12})*">
			</div>
		</div>
		<?php if ( $is_reffered_by_license_page ) : ?>
		<div class="field">
			<label for="mo_plan_no_of_users" class="label">Number of Users</label>
			<div class="control has-icons-left">
				<input class="input" type="number" name="mo_oauth_no_of_users" id="mo_plan_no_of_users" placeholder="Enter the number of active users in your WordPress site">
				<span class="icon is-small is-left">
					<i class="fas fa-users"></i>
				</span>
			</div>
		</div>
		<?php endif; ?>

		<div class="field">
		<label class="label">Query</label>
		<div class="control">
			<textarea class="textarea" name="mo_oauth_contact_us_query" placeholder="What can we help you with?" required></textarea>
		</div>
		</div>
		<p class="is-size-6">You can reach out to us directly at <a href="mailto:wpidpsupport@xecurify.com">wpidpsupport@xecurify.com.</a></p>
		<div class="field is-grouped is-grouped-centered mt-4">
		<div class="control">
			<button class="button is-active is-blue" type="submit" name="submit" value="Submit">Submit</button>
		</div>
		</div>
	</form>
</div>

<!-- This div close the parent container of main template. -->
</div>
