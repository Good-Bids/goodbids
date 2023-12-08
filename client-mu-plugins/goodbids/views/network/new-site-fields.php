<?php
/**
 * New Site Form Fields
 *
 * @global array $fields
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
		goodbids()->admin->render_field( $key, $field, $prefix );
	endforeach;
	?>
</table>

<div class="warning-message">
	<p><?php esc_html_e( 'Double check all fields before submitting this form. Form data may not be restored in the event of an error and could be required to be filled in again.', 'goodbids' ); ?></p>
</div>
