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


<h1><?php esc_html_e( 'Rewards', 'goodbids' ); ?></h1>

<?php if ( $has_orders ) : ?>
	<?php goodbids()->load_view( 'woocommerce/myaccount/my-rewards-header.php' ); ?>
	<div class="overflow-hidden border border-solid rounded-sm border-black-100">
		<table class="!mb-0 !border-0 bg-base-2 woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
			<thead>
				<tr class="bg-base-3">
					<th class="goodbids-auctions-table__header goodbids-auctions-table__header-nonprofit"><span class="nobr"><?php esc_html_e( 'Reward', 'goodbids' ); ?></span></th>
					<th class="goodbids-auctions-table__header goodbids-auctions-table__header-nonprofit"><span class="nobr"><?php esc_html_e( 'Nonprofit', 'goodbids' ); ?></span></th>
					<th class="goodbids-auctions-table__header goodbids-auctions-table__header-nonprofit"><span class="nobr"><?php esc_html_e( 'Status', 'goodbids' ); ?></span></th>
					<th class="goodbids-auctions-table__header goodbids-auctions-table__header-nonprofit"><span class="nobr"><?php esc_html_e( 'Date', 'goodbids' ); ?></span></th>
					<th class="goodbids-auctions-table__header goodbids-auctions-table__header-nonprofit"><span class="sr-only"><?php esc_html_e( 'Action', 'goodbids' ); ?></span></th>
				</tr>
			</thead>

			<tbody>
				<?php
				foreach ( $reward_orders as $reward_order ) {
					goodbids()->sites->swap(
						function () use ( $reward_order, $disabled_columns ) {
							$order = wc_get_order( $reward_order['order_id'] ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
							?>
							<tr class="odd:bg-base-2 even:bg-contrast-5 woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> order">
								<td class="text-xs woocommerce-orders-table__cell woocommerce-orders-table__cell-reward" data-title="<?php esc_attr_e( 'Reward', 'goodbids' ); ?>">
									<?php
									foreach ( $order->get_items() as $item ) :
										$product = $item->get_product();
										if ( $product ) :
											?>
											<a href="<?php echo esc_url( get_permalink( $item['product_id'] ) ); ?>">
												<?php echo esc_html( $item['name'] ); ?>
											</a>
											<?php
										endif;
									endforeach;
									?>
								</td>
								<td class="text-xs woocommerce-orders-table__cell woocommerce-orders-table__cell-nonprofit" data-title="<?php esc_attr_e( 'Nonprofit', 'goodbids' ); ?>">
									<a href="<?php echo esc_url( get_blog_details( $reward_order['site_id'] )->siteurl ); ?>">
										<?php echo esc_html( get_blog_details( $reward_order['site_id'] )->blogname ); ?>
									</a>
								</td>

								<td class="text-xs woocommerce-orders-table__cell woocommerce-orders-table__cell-status" data-title="<?php esc_attr_e( 'Status', 'goodbids' ); ?>">
									<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
								</td>

								<td class="text-xs woocommerce-orders-table__cell woocommerce-orders-table__cell-date" data-title="<?php esc_attr_e( 'Date', 'goodbids' ); ?>">
									<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>">
										<?php echo esc_html( $order->get_date_created()->date( 'm/d/y' ) ); ?>
									</time>
								</td>

								<td class="text-xs woocommerce-orders-table__cell woocommerce-orders-table__cell-action" data-title="<?php esc_attr_e( 'Action', 'goodbids' ); ?>">
									<?php
											$actions = wc_get_account_orders_actions( $order );

									if ( ! empty( $actions ) ) {
										foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
											printf(
												'<a href="%s" class="btn-fill-sm %s">%s</a>',
												esc_url( $action['url'] ),
												sanitize_html_class( $key ),
												esc_html( $action['name'] )
											);
										}
									}
									?>
								</td>
							</tr>
							<?php
						},
						$reward_order['site_id']
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
		esc_url( wc_get_endpoint_url( 'my-auctions' ) ),
		esc_html__( 'Auctions page', 'goodbids' ),
		esc_html__( 'to find auctions you\'ve recently won and claim your reward!', 'goodbids' )
	);

	wc_print_notice( $message, 'notice' ); // phpcs:ignore WooCommerce.Commenting.CommentHooks.MissingHookComment
	?>

<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
