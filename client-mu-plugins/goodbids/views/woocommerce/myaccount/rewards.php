<?php
/**
 * Rewards (copy of Orders) page.
 *
 * Shows Reward orders on the account page.
 *
 * @global array  $reward_orders
 * @global bool   $has_orders
 * @global string $wp_button_class
 * @global int    $current_page
 *
 * @see https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.5.0
 */

defined( 'ABSPATH' ) || exit;

// Customizations
$disabled_columns = [ 'order-total' ];

// Handle pagination.
$max_per_page  = goodbids()->get_config( 'woocommerce.account.default-orders-per-page' );
$max_pages     = ceil( $has_orders / $max_per_page );
$offset        = ( $current_page - 1 ) * $max_per_page;
$reward_orders = array_slice( $reward_orders, $offset, $max_per_page );

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<?php if ( $has_orders ) : ?>

	<?php goodbids()->load_view( 'woocommerce/myaccount/rewards-header.php' ); ?>

	<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
		<thead>
		<tr>
			<?php
			foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) :
				if ( in_array( $column_id, $disabled_columns, true ) ) {
					continue;
				}
				?>
				<th class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
			<?php endforeach; ?>
		</tr>
		</thead>

		<tbody>
			<?php
			foreach ( $reward_orders as $reward_order ) {
				goodbids()->sites->swap(
					function () use ( $reward_order, $disabled_columns, $wp_button_class ) {
						$order      = wc_get_order( $reward_order['order_id'] ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
						$item_count = $order->get_item_count() - $order->get_item_count_refunded();
						?>
						<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> order">
							<?php
							foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) :
								if ( in_array( $column_id, $disabled_columns, true ) ) {
									continue;
								}
								?>
								<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
									<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
										<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order, $reward_order['site_id'] ); ?>
									<?php elseif ( 'order-number' === $column_id ) : ?>
										<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
											<?php echo esc_html( _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number() ); ?>.<?php echo esc_html( $reward_order['site_id'] ); ?>
										</a>

									<?php elseif ( 'order-date' === $column_id ) : ?>
										<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>

									<?php elseif ( 'order-status' === $column_id ) : ?>
										<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>

									<?php elseif ( 'order-total' === $column_id ) : ?>
										<?php
										/* translators: 1: formatted order total 2: total order items */
										echo wp_kses_post( sprintf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count ) );
										?>

									<?php elseif ( 'order-actions' === $column_id ) : ?>
										<?php
										$actions = wc_get_account_orders_actions( $order );

										if ( ! empty( $actions ) ) {
											foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
												printf(
													'<a href="%s" class="woocommerce-button%s button %s">%s</a>',
													esc_url( $action['url'] ),
													esc_attr( $wp_button_class ),
													sanitize_html_class( $key ),
													esc_html( $action['name'] )
												);
											}
										}
										?>
									<?php endif; ?>
								</td>
							<?php endforeach; ?>
						</tr>
						<?php
					},
					$reward_order['site_id']
				);
			}
			?>
		</tbody>
	</table>

	<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

	<?php if ( 1 < $max_pages ) : ?>
		<div class="woocommerce-pagination woocommerce-pagination--with-numbers woocommerce-Pagination">
			<?php
			echo wp_kses_post(
				paginate_links(
					[
						'base'      => esc_url_raw( wc_get_endpoint_url( 'rewards', '%_%' ) ),
						'format'    => '%#%',
						'add_args'  => false,
						'current'   => max( 1, $current_page ),
						'total'     => $max_pages,
						'prev_text' => '&larr;',
						'next_text' => '&rarr;',
						'type'      => 'list',
						'end_size'  => 3,
						'mid_size'  => 3,
					]
				)
			);
			?>
		</div>
	<?php endif; ?>

<?php else : ?>

	<?php
	$message = sprintf(
		'%s <a href="%s">%s</a> %s',
		esc_html__( 'Check out the', 'goodbids' ),
		esc_url( wc_get_endpoint_url( 'auctions' ) ),
		esc_html__( 'Auctions page', 'goodbids' ),
		esc_html__( 'to find auctions you\'ve recently won and claim your reward!', 'goodbids' )
	);

	wc_print_notice( $message, 'notice' ); // phpcs:ignore WooCommerce.Commenting.CommentHooks.MissingHookComment
	?>

<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
