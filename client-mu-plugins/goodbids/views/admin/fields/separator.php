<?php
/**
 * Admin Field: Separator
 *
 * @var array  $field
 * @var string $field_id
 * @var bool   $wrap
 *
 * @since 1.0.0
 * @package GoodBids
 */

?>
<?php if ( $wrap ) : ?>
	<tr class="separator">
		<td colspan="2">
<?php endif; ?>

		<?php
		if ( ! empty( $field['before'] ) ) :
			echo wp_kses_post( $field['before'] );
		endif;
		?>

		<hr class="goodbids-separator" id="<?php echo esc_attr( $field_id ); ?>">

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
