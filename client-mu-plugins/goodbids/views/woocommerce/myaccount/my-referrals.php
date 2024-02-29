<?php
/**
 * My Account: Referrals Page
 *
 * @global array $referrals
 *
 * @package GoodBids
 */

use GoodBids\Users\Referrals\Referral;

?>
<div class="goodbids-account-referrals">
	<h1><?php esc_html_e( 'Referrals', 'goodbids' ); ?></h1>

	<div class="p-4 rounded-sm bg-contrast md:p-8">
		<h3 class="mt-0 font-bold normal-case text-base-2"><?php esc_html_e( 'Invite friends, bid for free!', 'goodbids' ); ?></h3>
		<p class="text-sm text-base-2"><?php esc_html_e( 'Earn a free bid for each person who signs up and donates through your link, usable in any GODBIDS network live auction.', 'goodbids' ); ?></p>
		<?php echo do_shortcode( '[goodbids-referral return="copy-link"]' ); ?>
	</div>

	<?php if ( ! $referrals ) : ?>
		<p><?php esc_html_e( 'You have not made any referrals yet.', 'goodbids' ); ?></p>
	<?php else : ?>
		<div class="mt-10 overflow-hidden border border-solid rounded-sm border-black-100">
			<table class="!mb-0 !border-0 bg-base-2 woocommerce-MyAccount-referrals shop_table shop_table_responsive my_account_referrals account-referrals-table">
				<thead>
					<tr class="text-xs bg-base-3">
						<th class="goodbids-referrals-table__header goodbids-referrals-table__header-referral"><span class="nobr"><?php esc_html_e( 'Referral Username', 'goodbids' ); ?></span></th>
						<th class="goodbids-referrals-table__header goodbids-referrals-table__header-created"><span class="nobr"><?php esc_html_e( 'Referral Date', 'goodbids' ); ?></span></th>
						<th class="goodbids-referrals-table__header goodbids-referrals-table__header-status"><span class="nobr"><?php esc_html_e( 'Status', 'goodbids' ); ?></span></th>
					</tr>
				</thead>

				<tbody>
					<?php
					foreach ( $referrals as $referral_data ) :
						goodbids()->sites->swap(
							function () use ( $referral_data ) {
								$referral = new Referral( $referral_data['referral_id'] );
								$display  = $referral->get_user() ? $referral->get_user()->display_name : $referral->get_user_id();
								$r_status = $referral->is_converted() ? __( 'Placed Bid!', 'goodbids' ) : __( 'Pending', 'goodbids' );
								?>
								<tr class="text-xs odd:bg-base-2 even:bg-contrast-5 goodbids-referrals-table__row goodbids-referrals-table__row--status-<?php echo esc_attr( strtolower( $r_status ) ); ?> referral">
									<td class="text-xs goodbids-referrals-table__cell goodbids-referrals-table__cell-referral" data-title="<?php esc_attr_e( 'Referral Name', 'goodbids' ); ?>">
										<?php echo esc_html( $display ); ?>
									</td>
									<td class="text-xs goodbids-referrals-table__cell goodbids-referrals-table__cell-created" data-title="<?php esc_attr_e( 'Referral Date', 'goodbids' ); ?>">
										<?php echo esc_html( $referral->get_created_date( 'n/j/Y' ) ); ?>
									</td>
									<td class="text-xs goodbids-referrals-table__cell goodbids-referrals-table__cell-status" data-title="<?php esc_attr_e( 'Status', 'goodbids' ); ?>">
										<?php echo esc_html( $r_status ); ?>
									</td>
								</tr>
								<?php
							},
							$referral_data['site_id']
						);
					endforeach;
					?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
</div>
