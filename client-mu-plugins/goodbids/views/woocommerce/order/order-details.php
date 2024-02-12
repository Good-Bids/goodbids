<?php
/**
 * Order details
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.5.0
 *
 * @var bool $show_downloads Controls whether the downloads table should be rendered.
 */

defined( 'ABSPATH' ) || exit;

$order = wc_get_order( $order_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

if ( ! $order ) {
	return;
}

$order_items           = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
$show_purchase_note    = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
$downloads             = $order->get_downloadable_items();

if ( $show_downloads ) {
	wc_get_template(
		'order/order-downloads.php',
		array(
			'downloads'  => $downloads,
			'show_title' => true,
		)
	);
}
?>
<section class="woocommerce-order-details">
	<?php do_action( 'woocommerce_order_details_before_order_table', $order ); ?>
	<h2 class="woocommerce-order-details__title">
		<?php if ( goodbids()->woocommerce->orders->is_bid_order( $order_id ) ) : ?>
			<?php esc_html_e( 'Donation Details', 'woocommerce' ); ?>
		<?php elseif ( goodbids()->woocommerce->orders->is_reward_order( $order_id ) ) : ?>
			<?php esc_html_e( 'Reward Claim Details', 'woocommerce' ); ?>
		<?php else : ?>
			<?php esc_html_e( 'Details', 'woocommerce' ); ?>
		<?php endif; ?>
	</h2>

	<table class="bg-base-2 woocommerce-table woocommerce-table--order-details shop_table order_details">

		<thead>
			<tr class="bg-base-3">
				<th class="woocommerce-table__product-name product-name">
					<?php if ( goodbids()->woocommerce->orders->is_bid_order( $order_id ) ) : ?>
						<?php esc_html_e( 'Auction', 'woocommerce' ); ?>
					<?php elseif ( goodbids()->woocommerce->orders->is_reward_order( $order_id ) ) : ?>
						<?php esc_html_e( 'Reward', 'woocommerce' ); ?>
					<?php else : ?>
						<?php esc_html_e( 'Product', 'woocommerce' ); ?>
					<?php endif; ?>
				</th>
				<th class="woocommerce-table__product-table product-total"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
			</tr>
		</thead>

		<tbody>
			<?php
			do_action( 'woocommerce_order_details_before_order_table_items', $order );

			foreach ( $order_items as $item_id => $item ) {
				$product = $item->get_product();

				wc_get_template(
					'order/order-details-item.php',
					array(
						'order'              => $order,
						'item_id'            => $item_id,
						'item'               => $item,
						'show_purchase_note' => $show_purchase_note,
						'purchase_note'      => $product ? $product->get_purchase_note() : '',
						'product'            => $product,
					)
				);
			}

			do_action( 'woocommerce_order_details_after_order_table_items', $order );
			?>
		</tbody>

		<tfoot>
			<?php foreach ( $order->get_order_item_totals() as $key => $total ) : ?>
				<tr class="even:bg-base-2 odd:bg-contrast-5">
					<th scope="row"><?php echo esc_html( $total['label'] ); ?></th>
					<td><?php echo wp_kses_post( $total['value'] ); ?></td>
				</tr>
			<?php endforeach; ?>
			<?php if ( $order->get_customer_note() ) : ?>
				<tr>
					<th><?php esc_html_e( 'Note:', 'woocommerce' ); ?></th>
					<td><?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?></td>
				</tr>
			<?php endif; ?>
		</tfoot>
	</table>

	<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
</section>

<?php
/**
 * Action hook fired after the order details.
 *
 * @since 4.4.0
 * @param WC_Order $order Order data.
 */
do_action( 'woocommerce_after_order_details', $order );

if ( $show_customer_details ) {
	wc_get_template( 'order/order-details-customer.php', array( 'order' => $order ) );
}
