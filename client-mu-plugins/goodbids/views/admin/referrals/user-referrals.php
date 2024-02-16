<?php
/**
 * User Referrals
 *
 * @global array $referrals
 *
 * @since 1.0.0
 * @package GoodBids
 */

use \GoodBids\Users\Referrals\Referral;

if ( ! $referrals ) :
	?>
	<p class="goodbids-referrals-empty">
		<?php echo esc_html__( 'You haven\'t referred anyone one yet!', 'goodbids' ); ?>
	</p>
	<?php

	return;
endif;
?>
<ul class="goodbids-referrals-list">
	<?php foreach ( $referrals as $referral_data ) :
		goodbids()->sites->swap(
			function() use ( $referral_data ) {
				$referral = new Referral( get_the_ID() );
				$user_id  = $referral->get_user_id();
				$user     = $referral->get_user();
				?>
				<li class="goodbids-referral" id="goodbids-referral-<?php echo esc_attr( $user_id ); ?>">
					<?php if ( $user ) : ?>
						<?php echo esc_html( $user->user_login ); ?>
					<?php else : ?>
						<?php echo esc_html( $user_id ); ?>
					<?php endif; ?>
				</li>
				<?php
			},
			$referral_data['site_id']
		);
	endforeach;
	?>
</ul>
