<?php
/**
 * Stripe Customer Data
 *
 * @since 1.0.0
 *
 * @var stdClass $stripe_customer
 *
 * @package GoodBids
 */

if ( ! $stripe_customer ) {
	esc_html_e('No Stripe Customer Data', 'goodbids');
	return;
}
?>
<dl>
	<dt><?php esc_html_e( 'Customer ID:', 'goodbids' ); ?></dt>
	<dd><?php echo esc_html( $stripe_customer->id ); ?></dd>
	<dt><?php esc_html_e( 'Customer Email:', 'goodbids' ); ?></dt>
	<dd><?php echo esc_html( $stripe_customer->email ); ?></dd>
</dl>
