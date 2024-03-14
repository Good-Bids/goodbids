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
	esc_html_e( 'Stripe customer data for this Nonprofit will display after the first invoice is generated.', 'goodbids' );
	return;
}
?>
<dl>
	<dt><?php esc_html_e( 'Customer ID:', 'goodbids' ); ?></dt>
	<dd><?php echo esc_html( $stripe_customer->id ); ?></dd>
	<dt><?php esc_html_e( 'Customer Email:', 'goodbids' ); ?></dt>
	<dd><?php echo esc_html( $stripe_customer->email ); ?></dd>
</dl>
