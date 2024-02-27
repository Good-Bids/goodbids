<?php
/**
 * Admin Field: Toggle
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

	<div class="gb-toggle">
		<label class="relative inline-flex items-center cursor-pointer">
			<input type="hidden" name="<?php echo esc_attr( $prefix ); ?>[<?php echo esc_attr( $key ); ?>]" value="0">
			<input
				type="checkbox" name="<?php echo esc_attr( $prefix ); ?>[<?php echo esc_attr( $key ); ?>]"
				id="<?php echo esc_attr( $field_id ); ?>"
				value="1" <?php checked( 1, intval( $value ) ); ?>
				class="sr-only peer"
				<?php disabled( ! empty( $field['disabled'] ) ) ?>
			>
			<div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-admin-blue-600 dark:peer-focus:ring-admin-blue-600 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-admin-blue-300"></div>
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
