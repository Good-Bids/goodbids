<?php
/**
 * HTML Field Type
 *
 * @var bool   $required
 * @var array  $field
 * @var array  $data
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

	<?php
	if ( ! empty( $field['callback'] ) && is_callable( $field['callback'] ) ) {
		/**
		 * Callback Field
		 *
		 * @since 1.0.0
		 *
		 * @param array  $field
		 * @param string $key
		 * @param string $prefix
		 * @param array  $data
		 * @param bool   $wrap
		 */
		call_user_func( $field['callback'], $field, $key, $prefix, $data, $wrap );
	} elseif ( ! empty( $field['html'] ) ) {
		echo wp_kses_post( $field['html'] );
	} else {
		?>
		<p><?php esc_html_e( 'Could not render field contents.', 'goodbids' ); ?></p>
		<?php
	}
	?>

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
