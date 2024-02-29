<?php
/**
 * Admin Field: Toggle
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

$toggle_class = ! empty( $field['disabled'] ) ? 'cursor-default' : 'cursor-pointer';
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

	<div class="gb-toggle">
		<label
			class="relative inline-flex items-center <?php echo esc_attr( $toggle_class ); ?>"
			title="<?php echo esc_attr( $value ); ?>"
		>
			<input type="hidden" name="<?php echo esc_attr( $prefix ); ?>[<?php echo esc_attr( $key ); ?>]" value="0">
			<input
				type="checkbox" name="<?php echo esc_attr( $prefix ); ?>[<?php echo esc_attr( $key ); ?>]"
				id="<?php echo esc_attr( $field_id ); ?>"
				value="1"
				class="sr-only peer"
				<?php checked( boolval( $value ) ); ?>
				<?php disabled( ! empty( $field['disabled'] ) ) ?>
			>
			<div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-admin-blue-600 dark:peer-focus:ring-admin-blue-600 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white peer-disabled:after:bg-[#45536a] after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-admin-blue-300"></div>
			<?php if ( ! empty( $field['text'] ) ): ?>
				<span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300"><?php echo esc_html( $field['text'] ); ?></span>
			<?php endif; ?>
		</label>
	</div>

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
