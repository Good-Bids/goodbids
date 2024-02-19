<?php
/**
 * View Order
 *
 * Shows the details of a particular order on the account page.
 *
 * @global WC_Order $order
 * @global int $order_id
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.0.0
 */

defined( 'ABSPATH' ) || exit;

$notes = $order->get_customer_order_notes();
?>
<p>
	<?php if ( goodbids()->woocommerce->orders->is_bid_order( $order_id ) ) : ?>
		<?php
			printf(
				/* translators: 1: order number 2: blog title 3: order date 4: order status */
				esc_html__( 'Your donation #%1$s to %2$s was placed on %3$s and is currently %4$s.', 'woocommerce' ),
				'<mark class="order-number">' . $order->get_order_number() . '</mark>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				'<mark class="order-site-title">' . get_bloginfo( 'title' ) . '</mark>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				'<mark class="order-date">' . wc_format_datetime( $order->get_date_created() ) . '</mark>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				'<mark class="order-status">' . wc_get_order_status_name( $order->get_status() ) . '</mark>' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		?>
	<?php elseif ( goodbids()->woocommerce->orders->is_reward_order( $order_id ) ) : ?>
		<?php
			printf(
				/* translators: 1: blog title 2: order date 3: order status 4: order number */
				esc_html__( 'Your reward claim with %1$s was placed on %2$s and is currently %3$s. Your order number is #%4$s', 'woocommerce' ),
				'<mark class="order-site-title">' . get_bloginfo( 'title' ) . '</mark>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				'<mark class="order-date">' . wc_format_datetime( $order->get_date_created() ) . '</mark>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				'<mark class="order-status">' . wc_get_order_status_name( $order->get_status() ) . '</mark>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				'<mark class="order-number">' . $order->get_order_number() . '</mark>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		?>
	<?php else : ?>
		<?php
			printf(
			/* translators: 1: order number 2: order date 3: order status */
				esc_html__( 'Order #%1$s was placed on %2$s and is currently %3$s.', 'woocommerce' ),
				'<mark class="order-number">' . $order->get_order_number() . '</mark>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				'<mark class="order-date">' . wc_format_datetime( $order->get_date_created() ) . '</mark>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				'<mark class="order-status">' . wc_get_order_status_name( $order->get_status() ) . '</mark>' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		?>
	<?php endif; ?>
</p>

<?php if ( $notes ) : ?>
	<h2><?php esc_html_e( 'Order updates', 'woocommerce' ); ?></h2>
	<ol class="woocommerce-OrderUpdates commentlist notes">
		<?php foreach ( $notes as $note ) : ?>
		<li class="woocommerce-OrderUpdate comment note">
			<div class="woocommerce-OrderUpdate-inner comment_container">
				<div class="woocommerce-OrderUpdate-text comment-text">
					<p class="woocommerce-OrderUpdate-meta meta"><?php echo date_i18n( esc_html__( 'l jS \o\f F Y, h:ia', 'woocommerce' ), strtotime( $note->comment_date ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
					<div class="woocommerce-OrderUpdate-description description">
						<?php echo wpautop( wptexturize( $note->comment_content ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>
		</li>
		<?php endforeach; ?>
	</ol>
<?php endif; ?>

<?php do_action( 'woocommerce_view_order', $order_id ); ?>
