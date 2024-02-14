<?php
/**
 * User Invitations
 *
 * @global array $invitations
 *
 * @since 1.0.0
 * @package GoodBids
 */

if ( empty( $invitations ) ) :
	?>
	<p class="goodbids-referral-invitations-empty">
		<?php echo esc_html__( 'You haven\'t invited anyone one yet!', 'goodbids' ); ?>
	</p>
	<?php
	return;
endif;
?>

<ul class="goodbids-referral-invitations">
	<?php foreach ( $invitations as $user_id ) : ?>
		<?php
		$user = get_user_by( 'id', $user_id );
		if ( false !== $user ) :
			?>
			<li class="goodbids-referral-invitation" id="goodbids-referral-invitation-<?php echo esc_attr( $user_id ); ?>">
				<?php echo esc_html( $user->user_login ); ?>
			</li>
		<?php endif; ?>

	<?php endforeach; ?>
</ul>
