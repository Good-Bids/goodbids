<?php
/**
 * Form Field: Textarea
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

$class = 'flex-1 block p-4 text-base bg-transparent border rounded-sm resize-y border-base focus:outline-contrast-3 placeholder:text-base';

if ( ! empty( $field['class'] ) ) {
	$class .= ' ' . $field['class'];
}
?>
<?php if ( $wrap ) : ?>
	<div class="flex flex-col mb-6">
		<label for="<?php echo esc_attr( $field_id ); ?>" class="block mb-2 text-sm font-medium text-base<?php echo $required ? ' form-required' : ''; ?>">
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

		<textarea
			name="<?php echo esc_attr( $name ); ?>"
			type="<?php echo esc_attr( $field['type'] ); ?>"
			class="<?php echo esc_attr( $class ); ?>"
			id="<?php echo esc_attr( $field_id ); ?>"
			placeholder="<?php echo esc_attr( $placeholder ); ?>"
			<?php
			disabled( ! empty( $field['disabled'] ) );

			echo $required ? ' required' : '';

			if ( ! empty( $field['attr'] ) ) :
				foreach ( $field['attr'] as $attr => $val ) :
					echo ' ' . esc_attr( $attr ) . '="' . esc_attr( $val ) . '"';
				endforeach;
			endif;
			?>
		><?php echo esc_textarea( $value ); ?></textarea>

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
