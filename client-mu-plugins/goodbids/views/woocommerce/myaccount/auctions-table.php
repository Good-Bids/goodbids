<?php
/**
 * GoodBids My Account > Dashboard Table
 *
 * @global array $auctions
 *
 * @since 1.0.0
 * @package GoodBids
 */

if ( ! count( $auctions ) ) :
	return;
endif;
?>
<div class="overflow-hidden border border-solid rounded-sm border-black-100">
	<table class="!mb-0 !border-0 bg-base-2 goodbids-auctions-table woocommerce-MyAccount-auctions shop_table shop_table_responsive my_account_auctions account-auctions-table">
		<thead>
			<tr class="text-xs bg-base-3">
				<th class="goodbids-auctions-table__header goodbids-auctions-table__header-auction-title"><span class="nobr"><?php esc_html_e( 'Auction', 'goodbids' ); ?></span></th>
				<th class="goodbids-auctions-table__header goodbids-auctions-table__header-nonprofit"><span class="nobr"><?php esc_html_e( 'Nonprofit', 'goodbids' ); ?></span></th>
				<th class="goodbids-auctions-table__header goodbids-auctions-table__header-status"><span class="nobr"><?php esc_html_e( 'Status', 'goodbids' ); ?></span></th>
				<th class="goodbids-auctions-table__header goodbids-auctions-table__header-bids"><span class="nobr"><?php esc_html_e( 'Your Bids', 'goodbids' ); ?></span></th>
				<th class="goodbids-auctions-table__header goodbids-auctions-table__header-donated"><span class="nobr"><?php esc_html_e( 'Your Donation', 'goodbids' ); ?></span></th>
				<th class="goodbids-auctions-table__header goodbids-auctions-table__header-high-bidder"><span class="nobr"><?php esc_html_e( 'High Bidder', 'goodbids' ); ?></span></th>
				<th class="goodbids-auctions-table__header goodbids-auctions-table__header-view"><span class="sr-only"><?php esc_html_e( 'Action', 'goodbids' ); ?></span></th>
			</tr>
		</thead>

		<tbody>
			<?php
			foreach ( $auctions as $auction_data ) :
				goodbids()->sites->swap(
					function () use ( $auction_data ) {
						$auction_id = $auction_data['auction_id'];
						$bid_count  = $auction_data['count'];

						$auction = goodbids()->auctions->get( $auction_id );
						$status  = $auction->get_status();

						$is_winning    = $auction->is_current_user_winning();
						$bid_orders    = $auction->get_bid_orders( -1, get_current_user_id() );
						$total_donated = collect( $bid_orders )
							->sum( fn( $order ) => $order->get_total( 'edit' ) );
						?>
						<tr class="text-xs odd:bg-base-2 even:bg-contrast-5 goodbids-auctions-table__row goodbids-auctions-table__row--status-<?php echo esc_attr( sanitize_title( $status ) ); ?> auction">
							<td class="goodbids-auctions-table__cell goodbids-auctions-table__cell-auction-title" data-title="<?php esc_attr_e( 'Auction', 'goodbids' ); ?>">
								<span><?php echo esc_html( $auction->get_title() ); ?></span>
							</td>
							<td class="goodbids-auctions-table__cell goodbids-auctions-table__cell-nonprofit" data-title="<?php esc_attr_e( 'Nonprofit', 'goodbids' ); ?>">
								<span><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
							</td>
							<td class="goodbids-auctions-table__cell goodbids-auctions-table__cell-status" data-title="<?php esc_attr_e( 'Status', 'goodbids' ); ?>">
								<span><?php echo esc_html( $status ); ?></span>
							</td>
							<td class="goodbids-auctions-table__cell goodbids-auctions-table__cell-bids" data-title="<?php esc_attr_e( 'Bids', 'goodbids' ); ?>">
								<span><?php echo esc_html( number_format( $bid_count, 0 ) ); ?></span>
							</td>
							<td class="goodbids-auctions-table__cell goodbids-auctions-table__cell-donated" data-title="<?php esc_attr_e( 'Donated', 'goodbids' ); ?>">
								<span><?php echo wp_kses_post( wc_price( $total_donated ) ); ?></span>
							</td>
							<td class="goodbids-auctions-table__cell goodbids-auctions-table__cell-high-bidder" data-title="<?php esc_attr_e( 'High Bidder', 'goodbids' ); ?>">
								<span><?php $is_winning ? esc_html_e( 'Yes', 'goodbids' ) : esc_html_e( 'No', 'goodbids' ); ?></span>
							</td>
							<td class="goodbids-auctions-table__cell goodbids-auctions-table__cell-view" data-title="<?php esc_attr_e( 'View', 'goodbids' ); ?>">
								<span><a href="<?php echo esc_url( get_permalink( $auction_id ) ); ?>" class="btn-fill-sm"><?php esc_html_e( 'View', 'goodbids' ); ?></a></span>
							</td>
						</tr>
						<?php
					},
					$auction_data['site_id'],
				);
				endforeach;
			?>
		</tbody>
	</table>
</div>
