<?php
/**
 * Admin Field: Text
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

$class = $field['class'] ?? 'regular-text';
?>
<?php if ( $wrap ) : ?>
	<tr class="<?php echo $required ? 'form-required' : ''; ?>">
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

		<input
			name="<?php echo esc_attr( $prefix ); ?>[<?php echo esc_attr( $key ); ?>]"
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
		</td>
	</tr>
	<?php
endif;
