<?php
/**
 * Edit Site Form Fields
 *
 * @global array  $data
 * @global array  $fields
 * @global string $prefix
 *
 * @since 1.0.0
 * @package GoodBids
 */

?>
<h3><?php esc_html_e( 'GoodBids Nonprofit Settings', 'goodbids' ); ?></h3>

<table class="form-table" role="presentation">
	<?php
	foreach ( $fields as $key => $field ) :
		goodbids()->admin->render_field( $key, $field, $prefix, $data );
	endforeach;
	?>
</table>
