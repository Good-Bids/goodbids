<?php
/**
 * Orders
 *
 * Shows Bid orders on the account page.
 *
 * @global string $wp_button_class
 * @global int    $current_page
 *
 * @see https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.5.0
 */

defined( 'ABSPATH' ) || exit;

// Our custom overrides.
$goodbids_orders = goodbids()->sites->get_user_bid_orders();
$has_orders      = count( $goodbids_orders );

// Handle pagination.
$max_per_page    = goodbids()->get_config( 'woocommerce.account.default-orders-per-page' );
$max_pages       = ceil( $has_orders / $max_per_page );
$offset          = ( $current_page - 1 ) * $max_per_page;
$goodbids_orders = array_slice( $goodbids_orders, $offset, $max_per_page );

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<?php if ( $has_orders ) : ?>
	<h1><?php esc_html_e( 'Bids', 'goodbids' ); ?></h1>

	<?php goodbids()->load_view( 'woocommerce/myaccount/orders-header.php' ); ?>

	<table class="bg-base-2 woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
		<thead>
			<tr class="bg-base-3">
				<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
					<th class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr( $column_id ); ?>">
						<span class="<?php echo esc_attr( 'order-actions' === $column_id ? 'sr-only' : 'nobr' ); ?>">
							<?php echo esc_html( $column_name ); ?>
						</span>
					</th>
				<?php endforeach; ?>
			</tr>
		</thead>

		<tbody>
			<?php
			foreach ( $goodbids_orders as $goodbids_order ) {
				goodbids()->sites->swap(
					function () use ( $goodbids_order ) {
						$order = wc_get_order( $goodbids_order['order_id'] ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
						?>
						<tr class="odd:bg-base-2 even:bg-contrast-5 woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> order">
							<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
								<td class="text-xs woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
									<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
										<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order, $goodbids_order['site_id'] ); ?>
									<?php elseif ( 'order-donation' === $column_id ) : ?>

										<?php foreach ( $order->get_items() as $item ) : ?>
											<?php $product = $item->get_product(); ?>
											<?php if ( $product ) : ?>
												<a href="<?php echo esc_url( get_permalink( $item['product_id'] ) ); ?>">
													<?php echo esc_html( $item['name'] ); ?>
												</a>
											<?php endif; ?>
										<?php endforeach; ?>

									<?php elseif ( 'order-nonprofit' === $column_id ) : ?>
										<a href="<?php echo esc_url( get_blog_details( $goodbids_order['site_id'] )->siteurl ); ?>">
											<?php echo esc_html( get_blog_details( $goodbids_order['site_id'] )->blogname ); ?>
										</a>

									<?php elseif ( 'order-date' === $column_id ) : ?>
										<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>">
											<?php echo esc_html( $order->get_date_created()->date( 'h/i/s' ) ); ?>
										</time>


									<?php elseif ( 'order-total' === $column_id ) : ?>
										<?php
												/* translators: 1: formatted order total 2: total order items */
												echo wp_kses_post( sprintf( '%1$s', $order->get_formatted_order_total() ) );
										?>

									<?php elseif ( 'order-actions' === $column_id ) : ?>
										<?php
												$actions = wc_get_account_orders_actions( $order );
										if ( ! empty( $actions ) ) {
											foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
												printf(
													'<a href="%s" class="btn-fill-sm !m-0 %s">%s</a>',
													esc_url( $action['url'] ),
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
					$goodbids_order['site_id']
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
						'base'      => esc_url_raw( wc_get_endpoint_url( 'orders', '%_%' ) ),
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

	<?php wc_print_notice( esc_html__( 'You have not bid on any auctions yet.', 'goodbids' ), 'notice' ); // phpcs:ignore WooCommerce.Commenting.CommentHooks.MissingHookComment ?>

<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
