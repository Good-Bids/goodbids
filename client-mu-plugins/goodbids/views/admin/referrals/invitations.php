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
	<?php if ( ! empty( $referrer_id ) ) : ?>
		<tr>
			<th><?php esc_html_e( 'Referred By', 'goodbids' ); ?></th>
			<td>
				<p>
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
				<p>
			</td>
		</tr>
	<?php endif; ?>

	<tr>
		<th><?php esc_html_e( 'Referral Link', 'goodbids' ); ?></th>
		<td>
			<?php echo do_shortcode( '[goodbids-referral show="copy-link"]' ); ?>

			<p>
				<?php esc_html_e( 'This user\'s invite link: ', 'goodbids' ); ?>
				<a href="<?php esc_url( $referral->get_link() ); ?>" target="_blank"><?php echo esc_url( $referral->get_link() ); ?></a>
			</p>
		</td>
	</tr>

	<tr>
		<th><?php esc_html_e( 'Referrals', 'goodbids' ); ?></th>
		<td>
			<?php if ( empty( $invitations ) ) : ?>
				<?php esc_html_e( 'This user has 0 successful referrals.', 'goodbids' ); ?>
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
					?>
				</ul>
			<?php
			endif;
			?>

			<hr>

			<p>
				<strong><?php esc_html_e( 'Manual Referral', 'goodbids' ); ?></strong>
			</p>
			<br>

			<?php goodbids()->load_view( 'admin/referrals/select-user.php' ); ?>

			<button
					id="goodbids-referrals-add-button"
					data-referrer-id="<?php echo esc_attr( $user_id ); ?>"
					class="goodbids-referrals-add-relation button button-small button-secondary add">
				<?php esc_html_e( 'Add', 'goodbids' ); ?>
			</button>

		</td>
	</tr>
</table>
