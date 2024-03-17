<?php
/**
 * Form Field: Select
 *
 * @var bool   $required
 * @var array  $field
 * @var string $prefix
 * @var string $key
 * @var string $name
 * @var string $placeholder
 * @var string $field_id
 * @var mixed  $value
 * @var bool   $wrap
 *
 * @since 1.0.0
 * @package GoodBids
 */

$class = 'rounded border-solid border-base w-full p-4 pr-10 bg-[right_1rem_center] bg-no-repeat appearance-none bg-contrast text-base bg-select-arrow-reverse bg-[length:20px_20px] focus:outline-contrast-3';

if ( ! empty( $field['class'] ) ) {
	$class .= ' ' . $field['class'];
}
?>
<?php if ( $wrap ) : ?>
	<div class="mb-6">
		<label for="<?php echo esc_attr( $field_id ); ?>" class="block mb-2 text-sm font-medium text-gb-green-100<?php echo $required ? ' form-required' : ''; ?>">
			<?php
			echo esc_html( $field['label'] );
			if ( $required ) :
				echo '&nbsp;' . wp_required_field_indicator(); // phpcs:ignore
			endif;
			?>
		</label>
<?php endif; ?>

		<?php
		if ( ! empty( $field['before'] ) ) :
			echo wp_kses_post( $field['before'] );
		endif;
		?>

		<select
			name="<?php echo esc_attr( $name ); ?>"
			id="<?php echo esc_attr( $field_id ); ?>"
			class="<?php echo esc_attr( $class ); ?>"
			<?php
			disabled( ! empty( $field['disabled'] ) );

			echo $required ? ' required' : '';

			if ( ! empty( $field['attr'] ) ) :
				foreach ( $field['attr'] as $attr => $val ) :
					echo ' ' . esc_attr( $attr ) . '="' . esc_attr( $val ) . '"';
				endforeach;
			endif;
			?>
		>
			<?php if ( ! $required || ! $value ) : ?>
				<option value="" <?php selected( $value, '' ); ?>><?php esc_html_e( 'Select One', 'goodbids' ); ?></option>
			<?php endif; ?>

			<?php
			foreach ( $field['options'] as $index => $option ) :
				if ( ! empty( $option['hidden'] ) ) {
					continue;
				}

				$opt_label = is_string( $option ) ? $option : $option['label'];
				$opt_value = ! is_numeric( $index ) ? $index : ( is_string( $option ) ? $option : $option['value'] );
				$disabled  = ! is_string( $option ) && ! empty( $option['disabled'] );
				?>
				<option
					value="<?php echo esc_attr( $opt_value ); ?>"
					<?php selected( $value, $opt_value ); ?>
					<?php disabled( true, $disabled ); ?>
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
	</div>
	<?php
endif;
