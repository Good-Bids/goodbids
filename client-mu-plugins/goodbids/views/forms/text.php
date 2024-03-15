<?php
/**
 * Form Field: Text
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

$class = 'block w-full p-4 text-gb-green-100 border border-gb-green-100 rounded bg-transparent text-base focus:ring-gb-green-500 focus:border-gb-green-500';

if ( ! empty( $field['class'] ) ) {
	$class .= ' ' . $field['class'];
}
?>
<?php if ( $wrap ) : ?>
	<div class="mb-6">
		<label for="<?php echo esc_attr( $field_id ); ?>" class="block mb-2 text-sm font-medium text-gb-green-100<?php echo $required ? 'form-required' : ''; ?>">
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

		<input
			name="<?php echo esc_attr( $name ); ?>"
			type="<?php echo esc_attr( $field['type'] ); ?>"
			class="<?php echo esc_attr( $class ); ?>"
			id="<?php echo esc_attr( $field_id ); ?>"
			placeholder="<?php echo esc_attr( $placeholder ); ?>"
			value="<?php echo esc_attr( $value ); ?>"
			<?php disabled( ! empty( $field['disabled'] ) ) ?>
			<?php echo $required ? 'required' : ''; ?>
		/>

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
