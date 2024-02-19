<?php
/**
 * Update User Referral Code
 *
 * @global Referrer $referrer
 *
 * @since 1.0.0
 * @package GoodBids
 */

use GoodBids\Users\Referrals;
use GoodBids\Users\Referrals\Admin;
use GoodBids\Users\Referrals\Referrer;
?>
<table class="form-table">
	<?php wp_nonce_field( Admin::UPDATE_CODE_NONCE_ACTION, Admin::UPDATE_CODE_NONCE ); ?>
	<tr>
		<th>
			<label for="<?php echo esc_attr( Referrals::REFERRAL_CODE_META_KEY ); ?>">
				<?php esc_html_e( 'Referral Code', 'goodbids' ); ?>
			</label>
		</th>

		<td>
			<input
				type="text"
				name="<?php echo esc_attr( Referrals::REFERRAL_CODE_META_KEY ); ?>"
				id="<?php echo esc_attr( Referrals::REFERRAL_CODE_META_KEY ); ?>"
				placeholder="<?php esc_attr_e( 'Referral Code', 'goodbids' ); ?>"
				value="<?php echo esc_attr( $referrer->get_code() ); ?>"
				style="text-transform: uppercase;"
			/>
		</td>
	</tr>

</table>
