<?php
/**
 * Admin Field: Checkbox
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

	<label title="<?php echo esc_attr( $value ); ?>">
		<input type="hidden" name="<?php echo esc_attr( $prefix ); ?>[<?php echo esc_attr( $key ); ?>]" value="0">
		<input
			type="checkbox" name="<?php echo esc_attr( $prefix ); ?>[<?php echo esc_attr( $key ); ?>]"
			id="<?php echo esc_attr( $field_id ); ?>"
			value="1"
			<?php checked( boolval( $value ) ); ?>
			<?php disabled( ! empty( $field['disabled'] ) ) ?>
		>
	</label>

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
