<?php
/**
 * Admin Field: Select
 *
 * @global bool   $required
 * @global array  $field
 * @global string $prefix
 * @global string $key
 * @global string $placeholder
 * @global string $field_id
 * @global mixed  $value
 * @global bool   $wrap
 *
 * @since 1.0.0
 * @package GoodBids
 */

?>
<?php if ( $wrap ) : ?>
	<tr class="form-field<?php echo $required ? ' form-required' : ''; ?>">
		<th scope="row">
			<label for="<?php echo esc_attr( $field_id ); ?>">
				<?php
				echo esc_html( $field['label'] );
				if ( $required ) :
					echo ' ' . wp_required_field_indicator(); // phpcs:ignore
				endif;
				?>
			</label>
		</th>
		<td>
<?php endif; ?>

		<?php
		if ( ! empty( $field['before'] ) ) :
			echo wp_kses_post( $field['before'] );
		endif;
		?>

		<select
			name="<?php echo esc_attr( $prefix ); ?>[<?php echo esc_attr( $key ); ?>]"
			id="<?php echo esc_attr( $field_id ); ?>"
			<?php echo $required ? 'required' : ''; ?>
		>
			<?php if ( ! $required || ! $value ) : ?>
				<option value="" <?php selected( $value, '' ); ?>><?php esc_html_e( 'Select One', 'goodbids' ); ?></option>
			<?php endif; ?>

			<?php foreach ( $field['options'] as $option ) : ?>
				<option
					value="<?php echo esc_attr( $option['value'] ); ?>"
					<?php selected( $value, $option['value'] ); ?>
				>
					<?php echo esc_html( $option['label'] ); ?>
				</option>
			<?php endforeach; ?>
		</select>

		<?php
		if ( ! empty( $field['after'] ) ) :
			echo wp_kses_post( $field['after'] );
		endif;
		?>

<?php if ( $wrap ) : ?>
		</td>
	</tr>
	<?php
endif;
