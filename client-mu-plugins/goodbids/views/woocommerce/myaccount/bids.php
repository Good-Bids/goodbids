<?php
/**
 * Bid Orders
 *
 * Shows Bid orders on the account page.
 *
 * @var array $bid_orders
 * @var bool  $has_orders
 * @var int   $current_page
 * @var int   $total_orders
 * @var int   $max_pages
 *
 * @see https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.5.0
 */

use GoodBids\Plugins\WooCommerce\Account;

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<?php if ( $has_orders ) : ?>
	<h1><?php esc_html_e( 'Bids', 'goodbids' ); ?></h1>

	<?php goodbids()->load_view( 'woocommerce/myaccount/bids-header.php' ); ?>

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
				foreach ( $bid_orders as $bid_order ) :
					goodbids()->sites->swap(
						function () use ( $bid_order ) {
							$order = wc_get_order( $bid_order['order_id'] ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
							$auction_id = goodbids()->woocommerce->orders->get_auction_id( $order->get_id() );
							$auction    = goodbids()->auctions->get( $auction_id );
							?>
							<tr class="odd:bg-base-2 even:bg-contrast-5 woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> order">
								<td class="text-xs woocommerce-orders-table__cell woocommerce-orders-table__cell-donation" data-title="donation">
									<?php foreach ( $order->get_items() as $item ) : ?>
										<?php
										$product_id = goodbids()->products->get_parent_product_id( $item['product_id'] );
										$product    = wc_get_product( $product_id );

										if ( $product ) :
											?>
											<a href="<?php echo esc_url( $auction->get_url() ); ?>">
												<?php echo esc_html( $product->get_title() ); ?>
											</a>
										<?php endif; ?>
									<?php endforeach; ?>
								</td>

								<td class="text-xs woocommerce-orders-table__cell woocommerce-orders-table__cell-nonprofit" data-title="nonprofit">
									<a href="<?php echo esc_url( get_blog_details( $bid_order['site_id'] )->siteurl ); ?>">
										<?php echo esc_html( get_blog_details( $bid_order['site_id'] )->blogname ); ?>
									</a>
								</td>

								<td class="text-xs woocommerce-orders-table__cell woocommerce-orders-table__cell-date" data-title="date">
									<?php
									if ( $order->get_total() <= 0 ) {
										printf(
											'<span style="text-decoration:line-through;opacity:0.5;">%s</span>&nbsp;',
											wp_kses_post( $order->get_subtotal_to_display() )
										);
									}
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
									if ( ! empty( $actions ) ) :
										foreach ( $actions as $key => $wc_action ) :
											printf(
												'<a href="%s" class="!mb-0 capitalize btn-fill-sm %s">%s</a>',
												esc_url( $wc_action['url'] ),
												sanitize_html_class( $key ),
												esc_html( $wc_action['name'] )
											);
										endforeach;
									endif;
									?>
								</td>
							</tr>
							<?php
						},
						$bid_order['site_id']
					);
				endforeach;
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
						'base'      => esc_url_raw( wc_get_endpoint_url( Account::BIDS_SLUG, '%_%' ) ),
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
