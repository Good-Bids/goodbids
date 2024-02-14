<?php
/**
 * User Referral Invitations
 *
 * @global \GoodBids\Users\Referrals\Referral $referral
 * @global int $user_id
 *
 * @since 1.0.0
 * @package GoodBids
 */

$referrer_id = $referral->get_referrer_id();
$invitations = $referral->get_invitations();
?>
<br>
<h2 id="goodbids-referrals-user-edit"><?php esc_html_e( 'GoodBids Referrals', 'goodbids' ); ?></h2>

<table class="form-table">
	<tr>
		<th><?php esc_html_e( 'Referral Code info', 'goodbids' ); ?></th>
		<td>
			<!-- Lists  -->
			<br>
			<ul class="invited-users_list list">
				<?php if ( ! empty( $referrer_id ) ) : ?>
					<a href="<?php echo esc_url( admin_url( '/user-edit.php?user_id=' . $referrer_id . '#goodbids-referrals-user-edit' ) ); ?>"  target="_blank">
						<?php esc_html_e( 'this user has been invited by ', 'goodbids' ); ?>
						<strong class="text-lg">
							<?php
							echo esc_html(
								get_user_meta( $referrer_id, 'first_name', true ) . ' ' .
								get_user_meta( $referrer_id, 'last_name', true )
							);
							?>
						</strong></a>
					<br>
					<hr>
				<?php else : ?>
					<?php esc_html_e( 'No one invited this user', 'goodbids' ); ?> <br>
					<hr>
				<?php endif; ?>

				<?php esc_html_e( 'This user\'s invite link: ', 'goodbids' ); ?>
				<a href="<?php esc_url( $referral->get_ref_link() ); ?>" target="_blank"><?php echo esc_url( $referral->get_ref_link() ); ?></a>
				<br>
				<hr>

				<div style="margin: 1rem 0;">
					<p><strong><?php esc_html_e( 'Manually refer a user', 'goodbids' ); ?></strong></p>
					<br>

					<?php goodbids()->load_view( 'admin/referrals/select-user.php' ); ?>

					<button style="background-color: #2ddd30; border-color: #389d05"
							id="goodbids-referrals-add-button"
							data-referrer-id="<?php echo esc_attr( $user_id ); ?>"
							class="goodbids-referrals-add-relation button button-small button-primary add">
						<?php esc_html_e( 'Add', 'goodbids' ); ?>
					</button>
				</div>

				<hr>
				<?php if ( empty( $invitations ) ) : ?>
					<?php esc_html_e( 'This user 0 invitations.', 'goodbids' ); ?>
				<?php else : ?>

					<h4><?php esc_html_e( 'This user has invited the following users: ', 'goodbids' ); ?></h4>

					<ul class="goodbids-referrals-invitations">
						<?php
						foreach ( $invitations as $invited_user_id ) :
							$invited_user = new WP_User( $invited_user_id );
							?>
							<li class="invitation-item item" id="<?php echo esc_attr( $invited_user_id ); ?>">
								<a href="<?php echo esc_url( admin_url( '/user-edit.php?user_id=' . $invited_user_id ) ); ?>" target="_blank">
									<?php echo esc_html( $invited_user->get( 'first_name' ) . ' ' . $invited_user->get( 'last_name' ) . "( $invited_user->user_login )" ); ?>
								</a>
								<button style="background-color: #dd382d; border-color: #dd382d"
									class="goodbids-referrals-delete button button-small button-primary delete-permanently"
									data-referrer-id="<?php echo esc_attr( $user_id ); ?>"
									data-user-id="<?php echo esc_attr( $invited_user_id ); ?>">
									<?php esc_html_e( 'Delete', 'goodbids' ); ?>
								</button>
							</li>
							<?php
						endforeach;
					endif;
					?>
				</ul>
			</ul>
		</td>
	</tr>
</table>
