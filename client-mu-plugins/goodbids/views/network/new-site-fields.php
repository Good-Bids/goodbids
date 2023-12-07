<?php
/**
 * New Site Form Fields
 *
 * @global $fields
 *
 * @package GoodBids
 */

?>
<h3><?php esc_html_e( 'GoodBids Nonprofit Settings', 'goodbids' ); ?></h3>

<table class="form-table" role="presentation">
	<?php
	foreach ( $fields as $key => $field ) :
		$required    = ! empty( $field['required'] ) && true === $field['required'];
		$placeholder = $field['placeholder'] ?? '';

		if ( in_array( $field['type'], [ 'text', 'url', 'email', 'tel', 'password', 'number' ], true ) ) :
			?>
			<tr class="form-field<?php echo $required ? ' form-required' : ''; ?>">
				<th scope="row">
					<label for="<?php echo esc_attr( $key ); ?>">
						<?php
						echo esc_html( $field['label'] );
						if ( $required ) :
							echo ' ' . wp_required_field_indicator(); // phpcs:ignore
						endif;
						?>
					</label>
				</th>
				<td><input name="gbnp[<?php echo esc_attr( $key ); ?>]" type="<?php echo esc_attr( $field['type'] ); ?>" class="regular-text" id="<?php echo esc_attr( $key ); ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>" <?php echo $required ? 'required' : ''; ?> /></td>
			</tr>
		<?php else : ?>
			<p><?php esc_html_e( 'Unsupported field type.', 'goodbids' ); ?></p>
		<?php endif; ?>
	<?php endforeach; ?>
</table>

<div class="warning-message">
	<p><?php esc_html_e( 'Double check all fields before submitting this form. Form data may not be restored in the event of an error and could be required to be filled in again.', 'goodbids' ); ?></p>
</div>
