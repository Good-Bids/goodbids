<?php
/**
 * Payment methods
 *
 * Shows customer payment methods on the account page.
 *
 * @see https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.8.0
 */

defined( 'ABSPATH' ) || exit;

$saved_methods = wc_get_customer_saved_methods_list( get_current_user_id() );
$has_methods   = (bool) $saved_methods;
$types         = wc_get_account_payment_methods_types();

do_action( 'woocommerce_before_account_payment_methods', $has_methods ); ?>

<h1><?php esc_html_e( 'Payment Methods', 'goodbids' ); ?></h1>

<?php if ( $has_methods ) : ?>
<div class="mb-4 overflow-hidden border border-solid rounded-sm border-black-100">
	<table class="!mb-0 !border-0 bg-base-2 woocommerce-MyAccount-paymentMethods shop_table shop_table_responsive account-payment-methods-table">
		<thead>
			<tr class="text-xs bg-base-3">
				<?php foreach ( wc_get_account_payment_methods_columns() as $column_id => $column_name ) : ?>
				<th class="woocommerce-PaymentMethod woocommerce-PaymentMethod--<?php echo esc_attr( $column_id ); ?> payment-method-<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<?php foreach ( $saved_methods as $type => $methods ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
			<?php foreach ( $methods as $method ) : ?>
				<tr class="odd:bg-base-2 even:bg-contrast-5 payment-method<?php echo ! empty( $method['is_default'] ) ? ' default-payment-method' : ''; ?>">
						<?php foreach ( wc_get_account_payment_methods_columns() as $column_id => $column_name ) : ?>
							<td class="text-xs woocommerce-PaymentMethod woocommerce-PaymentMethod--<?php echo esc_attr( $column_id ); ?> payment-method-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
							<?php
							if ( has_action( 'woocommerce_account_payment_methods_column_' . $column_id ) ) {
								do_action( 'woocommerce_account_payment_methods_column_' . $column_id, $method );
							} elseif ( 'method' === $column_id ) {
								if ( ! empty( $method['method']['last4'] ) ) {
									/* translators: 1: credit card type 2: last 4 digits */
									printf( esc_html__( '%1$s ending in %2$s', 'woocommerce' ), esc_html( wc_get_credit_card_type_label( $method['method']['brand'] ) ), esc_html( $method['method']['last4'] ) );
								} else {
									echo esc_html( wc_get_credit_card_type_label( $method['method']['brand'] ) );
								}
							} elseif ( 'expires' === $column_id ) {
								echo esc_html( $method['expires'] );
							} elseif ( 'actions' === $column_id ) {
								foreach ( $method['actions'] as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
									echo '<a href="' . esc_url( $action['url'] ) . '" class="button btn-fill ' . sanitize_html_class( $key ) . '' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>&nbsp;';
								}
							}
							?>
					</td>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
		<?php endforeach; ?>
	</table>
</div>


<p class="text-xs">
	<?php
		printf(
			// translators: %s represents the name of the Nonprofit site.
			esc_html__( 'These are the payment methods you\'ve saved on the %s Nonprofit site. Saved payment methods are not shared between Nonprofit sites.', 'woocommerce' ),
			esc_html( get_bloginfo( 'title' ) )
		);
	?>
</p>

<?php else : ?>
	<?php wc_print_notice( esc_html__( 'No saved methods found.', 'woocommerce' ), 'notice' ); ?>
<?php endif; ?>

<?php do_action( 'woocommerce_after_account_payment_methods', $has_methods ); ?>

<?php if ( WC()->payment_gateways->get_available_payment_gateways() ) : ?>
<div class="mt-8">
	<a class="button btn-fill" href="<?php echo esc_url( wc_get_endpoint_url( 'add-payment-method' ) ); ?>"><?php esc_html_e( 'Add payment method', 'woocommerce' ); ?></a>
</div>
<?php endif; ?>
