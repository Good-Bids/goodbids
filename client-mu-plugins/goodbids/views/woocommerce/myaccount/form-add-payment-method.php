<?php
/**
 * Add payment method form
 *
 * @see https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.8.0
 */

defined( 'ABSPATH' ) || exit;

$available_gateways = WC()->payment_gateways->get_available_payment_gateways();

if ( $available_gateways ) : ?>
	<form id="add_payment_method" method="post">
		<div id="payment" class="woocommerce-Payment">
			<ul class="woocommerce-PaymentMethods payment_methods methods">
				<?php
				// Chosen Method.
				if ( count( $available_gateways ) ) {
					current( $available_gateways )->set_current();
				}

				foreach ( $available_gateways as $gateway ) {
					$icon = $gateway->get_icon() ?? '';
					?>
					<li class="woocommerce-PaymentMethod woocommerce-PaymentMethod--<?php echo esc_attr( $gateway->id ); ?> payment_method_<?php echo esc_attr( $gateway->id ); ?>">
						<input id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> />
						<label for="payment_method_<?php echo esc_attr( $gateway->id ); ?>"><?php echo wp_kses_post( $gateway->get_title() ); ?> <?php echo wp_kses_post( $icon ); ?></label>
						<?php
						if ( $gateway->has_fields() || $gateway->get_description() ) {
							echo '<div class="woocommerce-PaymentBox woocommerce-PaymentBox--' . esc_attr( $gateway->id ) . ' payment_box payment_method_' . esc_attr( $gateway->id ) . '" style="display: none;">';
							$gateway->payment_fields();
							echo '</div>';
						}
						?>
					</li>
					<?php
				}
				?>
			</ul>

			<?php do_action( 'woocommerce_add_payment_method_form_bottom' ); ?>

			<div class="form-row">
				<?php wp_nonce_field( 'woocommerce-add-payment-method', 'woocommerce-add-payment-method-nonce' ); ?>
				<button type="submit" class="woocommerce-Button woocommerce-Button--alt button alt<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" id="place_order" value="<?php esc_attr_e( 'Add payment method', 'woocommerce' ); ?>"><?php esc_html_e( 'Add payment method', 'woocommerce' ); ?></button>
				<input type="hidden" name="woocommerce_add_payment_method" id="woocommerce_add_payment_method" value="1" />
			</div>
		</div>
	</form>
<?php else : ?>
	<?php wc_print_notice( esc_html__( 'New payment methods can only be added during checkout. Please contact us if you require assistance.', 'woocommerce' ), 'notice' ); ?>
<?php endif; ?>
