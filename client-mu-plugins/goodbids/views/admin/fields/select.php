<?php
/**
 * Admin Field: Select
 *
 * @var bool   $required
 * @var array  $field
 * @var string $prefix
 * @var string $key
 * @var string $placeholder
 * @var string $field_id
 * @var mixed  $value
 * @var bool   $wrap
 *
 * @since 1.0.0
 * @package GoodBids
 */

?>
<?php if ( $wrap ) : ?>
	<tr class="<?php echo $required ? ' form-required' : ''; ?>">
		<th scope="row">
			<label for="<?php echo esc_attr( $field_id ); ?>">
				<?php
				echo esc_html( $field['label'] );
				if ( $required ) :
					echo '&nbsp;' . wp_required_field_indicator(); // phpcs:ignore
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
			<?php disabled( ! empty( $field['disabled'] ) ) ?>
			<?php echo $required ? 'required' : ''; ?>
		>
			<?php if ( ! $required || ! $value ) : ?>
				<option value="" <?php selected( $value, '' ); ?>><?php esc_html_e( 'Select One', 'goodbids' ); ?></option>
			<?php endif; ?>

			<?php foreach ( $field['options'] as $index => $option ) :
				$opt_label = is_string( $option ) ? $option : $option['label'];
				$opt_value = ! is_numeric( $index ) ? $index : ( is_string( $option ) ? $option : $option['value'] );
				?>
				<option
					value="<?php echo esc_attr( $opt_value ); ?>"
					<?php selected( $value, $opt_value ); ?>
				>
					<?php echo esc_html( $opt_label ); ?>
				</option>
			<?php endforeach; ?>
		</select>

		<?php
		if ( ! empty( $field['after'] ) ) :
			echo wp_kses_post( $field['after'] );
		endif;

		if ( ! empty( $field['description'] ) ) :
			?>
			<p class="description"><?php echo wp_kses_post( $field['description'] ); ?></p>
		<?php endif; ?>

<?php if ( $wrap ) : ?>
		</td>
	</tr>
	<?php
endif;
