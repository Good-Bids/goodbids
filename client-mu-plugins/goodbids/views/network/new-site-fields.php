<?php
/**
 * New Site Form Fields
 *
 * @package GoodBids
 */

?>
<h3><?php esc_html_e( 'GoodBids Nonprofit Settings', 'goodbids' ); ?></h3>

<table class="form-table" role="presentation">
	<tr class="form-field form-required">
		<th scope="row">
			<label for="gbnp-legal-name">
				<?php
				_e( 'Nonprofit Legal Name' );
				echo ' ' . wp_required_field_indicator(); // phpcs:ignore
				?>
			</label>
		</th>
		<td><input name="blog[gbnp-legal-name]" type="text" class="regular-text" id="gbnp-legal-name" required /></td>
	</tr>
	<tr class="form-field form-required">
		<th scope="row">
			<label for="gbnp-ein">
				<?php
				_e( 'Nonprofit EIN' );
				echo ' ' . wp_required_field_indicator(); // phpcs:ignore
				?>
			</label>
		</th>
		<td><input name="blog[gbnp-ein]" type="text" class="regular-text" id="gbnp-ein" required placeholder="XX-XXXXXXX"/></td>
	</tr>
	<tr class="form-field form-required">
		<th scope="row">
			<label for="gbnp-website">
				<?php
				_e( 'Nonprofit Website' );
				echo ' ' . wp_required_field_indicator(); // phpcs:ignore
				?>
			</label>
		</th>
		<td><input name="blog[gbnp-website]" type="text" class="regular-text" id="gbnp-website" required placeholder="https://" /></td>
	</tr>
</table>

<div class="warning-message">
	<p><?php esc_html_e( 'Double check all fields before submitting this form. Data will not be restored in the event of an error and will be required to be filled in again.', 'goodbids' ); ?></p>
</div>
