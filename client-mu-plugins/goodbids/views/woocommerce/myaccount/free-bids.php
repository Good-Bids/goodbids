<?php
/**
 * My Account: Free Bids Page
 *
 * @global \GoodBids\Auctions\FreeBid[] $free_bids
 *
 * @package GoodBids
 */

?>
<div class="goodbids-free-bids">
	<h1><?php esc_html_e( 'Free Bids', 'goodbids' ); ?></h1>

	<?php if ( ! count( $free_bids ) ) : ?>
		<p><?php esc_html_e( 'You have not earned any free bids yet.', 'goodbids' ); ?></p>
	<?php else : ?>
		<table class="goodbids-free-bids-table woocommerce-MyAccount-free-bids shop_table shop_table_responsive my_account_free_bids account-free-bids-table">
			<thead>
				<tr>
					<th class="goodbids-free-bids-table__header goodbids-free-bids-table__header-id"><span class="nobr"><?php esc_html_e( 'ID', 'goodbids' ); ?></span></th>
					<th class="goodbids-free-bids-table__header goodbids-free-bids-table__header-earned-date"><span class="nobr"><?php esc_html_e( 'Earned Date', 'goodbids' ); ?></span></th>
					<th class="goodbids-free-bids-table__header goodbids-free-bids-table__header-auction-earned"><span class="nobr"><?php esc_html_e( 'Auction Earned', 'goodbids' ); ?></span></th>
					<th class="goodbids-free-bids-table__header goodbids-free-bids-table__header-status"><span class="nobr"><?php esc_html_e( 'Status', 'goodbids' ); ?></span></th>
					<th class="goodbids-free-bids-table__header goodbids-free-bids-table__header-auction-used"><span class="nobr"><?php esc_html_e( 'Auction Used', 'goodbids' ); ?></span></th>
					<th class="goodbids-free-bids-table__header goodbids-free-bids-table__header-used-date"><span class="nobr"><?php esc_html_e( 'Used Date', 'goodbids' ); ?></span></th>
				</tr>
			</thead>

			<tbody>
				<?php foreach ( $free_bids as $free_bid ) : ?>
					<tr class="goodbids-free-bids-table__row goodbids-free-bids-table__row--status-<?php echo esc_attr( $free_bid->get_status() ); ?> free-bid">
						<td class="goodbids-free-bids-table__cell goodbids-free-bids-table__cell-id" data-title="<?php esc_attr_e( 'ID', 'goodbids' ); ?>">
							<span><?php echo esc_html( $free_bid->id ); ?></span>
						</td>
						<td class="goodbids-free-bids-table__cell goodbids-free-bids-table__cell-earned-date" data-title="<?php esc_attr_e( 'Earned Date', 'goodbids' ); ?>">
							<span><?php echo esc_html( $free_bid->earned_date ); ?></span>
						</td>
						<td class="goodbids-free-bids-table__cell goodbids-free-bids-table__cell-auction-earned" data-title="<?php esc_attr_e( 'Auction Earned', 'goodbids' ); ?>">
							<span><?php echo esc_html( $free_bid->auction_id_earned ); ?></span>
						</td>
						<td class="goodbids-free-bids-table__cell goodbids-free-bids-table__cell-status" data-title="<?php esc_attr_e( 'Status', 'goodbids' ); ?>">
							<span><?php echo esc_html( $free_bid->get_status() ); ?></span>
						</td>
						<td class="goodbids-free-bids-table__cell goodbids-free-bids-table__cell-auction-used" data-title="<?php esc_attr_e( 'Auction Used', 'goodbids' ); ?>">
							<span><?php echo esc_html( $free_bid->auction_id_used ); ?></span>
						</td>
						<td class="goodbids-free-bids-table__cell goodbids-free-bids-table__cell-used-date" data-title="<?php esc_attr_e( 'Used Date', 'goodbids' ); ?>">
							<span><?php echo esc_html( $free_bid->used_date ); ?></span>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>
