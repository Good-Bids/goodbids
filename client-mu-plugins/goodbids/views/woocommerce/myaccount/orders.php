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

	<h2 class="mt-12 font-normal text-md"><?php esc_html_e( 'Donation History', 'goodbids' ); ?></h2>

	<div class="overflow-hidden border border-solid rounded-sm border-black-100">
		<table class="!mb-0 !border-0 bg-base-2 woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
			<thead>
				<tr class="bg-base-3">
					<th class="woocommerce-orders-table__header woocommerce-orders-table__header-donation"><span class="nobr"><?php esc_html_e( 'Donation', 'goodbids' ); ?></span></th>
					<th class="woocommerce-orders-table__header woocommerce-orders-table__header-nonprofit"><span class="nobr"><?php esc_html_e( 'Nonprofit', 'goodbids' ); ?></span></th>
					<th class="woocommerce-orders-table__header woocommerce-orders-table__header-total"><span class="nobr"><?php esc_html_e( 'Total', 'goodbids' ); ?></span></th>
					<th class="woocommerce-orders-table__header woocommerce-orders-table__header-date"><span class="nobr"><?php esc_html_e( 'Date', 'goodbids' ); ?></span></th>
					<th class="woocommerce-orders-table__header woocommerce-orders-table__header-actions"><span class="sr-only"><?php esc_html_e( 'Actions', 'goodbids' ); ?></span></th>
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
								<td class="text-xs woocommerce-orders-table__cell woocommerce-orders-table__cell-donation" data-title="donation">
									<?php foreach ( $order->get_items() as $item ) : ?>
										<?php $product = $item->get_product(); ?>
										<?php if ( $product ) : ?>
											<a href="<?php echo esc_url( get_permalink( $item['product_id'] ) ); ?>">
												<?php echo esc_html( $item['name'] ); ?>
											</a>
										<?php endif; ?>
									<?php endforeach; ?>
								</td>

								<td class="text-xs woocommerce-orders-table__cell woocommerce-orders-table__cell-nonprofit" data-title="nonprofit">
									<a href="<?php echo esc_url( get_blog_details( $goodbids_order['site_id'] )->siteurl ); ?>">
										<?php echo esc_html( get_blog_details( $goodbids_order['site_id'] )->blogname ); ?>
									</a>
								</td>

								<td class="text-xs woocommerce-orders-table__cell woocommerce-orders-table__cell-date" data-title="date">
									<?php
									/* translators: 1: formatted order total 2: total order items */
									echo wp_kses_post( $order->get_formatted_order_total() );
									?>
								</td>

								<td class="text-xs woocommerce-orders-table__cell woocommerce-orders-table__cell-total" data-title="total">
									<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>">
										<?php echo esc_html( $order->get_date_created()->date( 'm/d/y' ) ); ?>
									</time>
								</td>

								<td class="text-xs woocommerce-orders-table__cell woocommerce-orders-table__cell-actions" data-title="actions">
									<?php
									$actions = wc_get_account_orders_actions( $order );
									if ( ! empty( $actions ) ) {
										foreach ( $actions as $key => $wc_action ) {
											printf(
												'<a href="%s" class="!mb-0 capitalize btn-fill-sm %s">%s</a>',
												esc_url( $wc_action['url'] ),
												sanitize_html_class( $key ),
												esc_html( $wc_action['name'] )
											);
										}
									}
									?>
								</td>
							</tr>
							<?php
						},
						$goodbids_order['site_id']
					);
				}
				?>
				</tbody>
			</table>
		</div>

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
