<?php
/**
 * My Account: Free Bids Page
 *
 * @global FreeBid[] $free_bids
 *
 * @package GoodBids
 */

?>

<div class="goodbids-free-bids">
	<h1><?php esc_html_e( 'Free Bids', 'goodbids' ); ?></h1>

	<?php if ( ! count( $free_bids ) ) : ?>
		<p><?php esc_html_e( 'You have not earned any free bids yet.', 'goodbids' ); ?></p>
	<?php else : ?>
		<?php goodbids()->load_view( 'woocommerce/myaccount/free-bids-header.php' ); ?>

		<h2 class="mt-12 font-normal text-md">Free Bids Earned</h2>
		<div class="overflow-hidden border border-solid rounded-sm border-black-100">
			<table class="!mb-0 !border-0 bg-base-2 goodbids-free-bids-table woocommerce-MyAccount-free-bids shop_table shop_table_responsive my_account_free_bids account-free-bids-table">
				<thead>
					<tr class="text-xs bg-base-3">
						<th class="goodbids-free-bids-table__header goodbids-free-bids-table__header-id"><span class="nobr">#</span></th>
						<th class="goodbids-free-bids-table__header goodbids-free-bids-table__header-earned"><span class="nobr"><?php esc_html_e( 'Earned', 'goodbids' ); ?></span></th>
						<th class="goodbids-free-bids-table__header goodbids-free-bids-table__header-status"><span class="nobr"><?php esc_html_e( 'Status', 'goodbids' ); ?></span></th>
						<th class="goodbids-free-bids-table__header goodbids-free-bids-table__header-used"><span class="nobr"><?php esc_html_e( 'Used', 'goodbids' ); ?></span></th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ( $free_bids as $index => $free_bid ) : ?>
						<tr class="text-xs odd:bg-base-2 even:bg-contrast-5 goodbids-free-bids-table__row goodbids-free-bids-table__row--status-<?php echo esc_attr( $free_bid->get_status() ); ?> free-bid">
							<td class="goodbids-free-bids-table__cell goodbids-free-bids-table__cell-id" data-title="<?php esc_attr_e( 'Free Bid #', 'goodbids' ); ?>">
								<span><?php echo esc_html( $index + 1 ); ?></span>
							</td>
							<td class="goodbids-free-bids-table__cell goodbids-free-bids-table__cell-earned" data-title="<?php esc_attr_e( 'Earned', 'goodbids' ); ?>">
								<div title="<?php echo esc_attr( $free_bid->get_details() ); ?>">
									<span><?php $free_bid->display_auction_link( $free_bid->auction_id_earned, $free_bid->get_details() ); ?></span>
									<?php esc_html_e( 'on', 'goodbids' ); ?>
									<span><?php $free_bid->display_earned_date(); ?></span>
								</div>
							</td>
							<td class="goodbids-free-bids-table__cell goodbids-free-bids-table__cell-status" data-title="<?php esc_attr_e( 'Status', 'goodbids' ); ?>">
								<span><?php $free_bid->display_status(); ?></span>
							</td>
							<td class="goodbids-free-bids-table__cell goodbids-free-bids-table__cell-used" data-title="<?php esc_attr_e( 'Used', 'goodbids' ); ?>">
								<span><?php $free_bid->display_auction_link( $free_bid->auction_id_used ); ?></span>
								<?php if ( $free_bid->used_date ) : ?>
									<?php esc_html_e( 'on', 'goodbids' ); ?>
									<span><?php $free_bid->display_used_date(); ?></span>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>

		<p class="mb-0 font-bold"><?php esc_html_e( 'Share with Friends to earn Free Bids!', 'goodbids' ); ?></p>
		<?php echo do_shortcode( '[goodbids-referral return="copy-link"]' ); ?>
	<?php endif; ?>
</div>
