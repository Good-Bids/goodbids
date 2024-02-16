<?php
/**
 * User Referrals Admin View
 *
 * @global Referrer $referrer
 * @global int $user_id
 *
 * @since 1.0.0
 * @package GoodBids
 */

use GoodBids\Users\Referrals\Referral;
use GoodBids\Users\Referrals\Referrer;

$referrer_id = goodbids()->referrals->get_referrer_id( $user_id );
$referrals   = $referrer->get_referrals();
?>
<br>
<h2 id="goodbids-referrals-user-edit"><?php esc_html_e( 'GoodBids Referrals', 'goodbids' ); ?></h2>

<table class="form-table">
	<?php
	if ( $referrer_id ) :
		$referrer = get_user_by( 'ID', $referrer_id );
		?>
		<tr>
			<th><?php esc_html_e( 'Referred By', 'goodbids' ); ?></th>
			<td>
				<p>
					<a href="<?php echo esc_url( admin_url( '/user-edit.php?user_id=' . $referrer_id . '#goodbids-referrals-user-edit' ) ); ?>" target="_blank" rel="noopener">
						<strong class="text-lg">
							<?php echo esc_html( $referrer->display_name ); ?>
						</strong>
					</a>
				<p>
			</td>
		</tr>
	<?php endif; ?>

	<tr>
		<th><?php esc_html_e( 'Referral Link', 'goodbids' ); ?></th>
		<td>
			<?php echo do_shortcode( sprintf( '[goodbids-referral return="copy-link" user_id="%d"]', esc_attr( $user_id ) ) ); ?>
		</td>
	</tr>

	<tr>
		<th><?php esc_html_e( 'Referrals', 'goodbids' ); ?></th>
		<td>
			<?php if ( empty( $referrals ) ) : ?>
				<?php esc_html_e( 'No referrals found.', 'goodbids' ); ?>
			<?php else : ?>

				<ul class="goodbids-referrals">
					<?php
					foreach ( $referrals as $referral_data ) :
						goodbids()->sites->swap(
							function() use ( $referral_data, $user_id ) {
								$referral = new Referral( $referral_data['referral_id'] );
								$user     = $referral->get_user();

								if ( ! $user ) {
									return;
								}
								?>
								<li class="referral-item item" id="<?php echo esc_attr( $user->ID ); ?>">
									<a href="<?php echo esc_url( admin_url( '/user-edit.php?user_id=' . $user->ID ) ); ?>" target="_blank"><?php echo esc_html( $user->display_name ); ?></a>
									<button style="background-color: #dd382d; border-color: #dd382d"
										class="goodbids-referrals-delete button button-small button-primary delete-permanently"
										data-referrer-id="<?php echo esc_attr( $user_id ); ?>"
										data-user-id="<?php echo esc_attr( $user->ID ); ?>"
									>
										<?php esc_html_e( 'Delete', 'goodbids' ); ?>
									</button>
								</li>
								<?php
							},
							$referral_data['site_id']
						);
					endforeach;
					?>
				</ul>
				<?php
			endif;
			?>

			<hr>

			<p>
				<strong><?php esc_html_e( 'Manual Referral', 'goodbids' ); ?></strong>
			</p>

			<?php goodbids()->load_view( 'admin/referrals/select-user.php', compact( 'user_id', 'referrer' ) ); ?>

			<button
					style="min-height:30px;"
					id="goodbids-referrals-add-button"
					data-referrer-id="<?php echo esc_attr( $user_id ); ?>"
					class="goodbids-referrals-add button button-small button-secondary add">
				<?php esc_html_e( 'Add', 'goodbids' ); ?>
			</button>

		</td>
	</tr>
</table>
