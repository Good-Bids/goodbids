<?php
/**
 * Admin Support Requests Details
 *
 * @global int $request_id
 *
 * @since 1.0.0
 * @package GoodBids
 */

use GoodBids\Frontend\Request;

$request = new Request( $request_id );
$user    = $request->get_user();
?>
<h3>
	<?php esc_html_e( 'Request from' ); ?>
	<?php echo esc_html( $user->get_username() ); ?>
</h3>
<dl>
	<?php
	foreach ( goodbids()->support->get_fields() as $key => $field ) :
		if ( method_exists( $request, 'get' . $key . '_html' ) ) {
			$value = $request->{'get' . $key . '_html'}();
		} else {
			$value = $request->get_field( $key );
		}
		if ( ! $value ) {
			continue;
		}
		?>
		<dt><?php echo esc_html( $field['label'] ); ?></dt>
		<dd><?php echo wp_kses_post( $value ); ?></dd>
	<?php endforeach; ?>
</dl>
