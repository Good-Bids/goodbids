<?php
/**
 * Update User Referral Code
 *
 * @global \GoodBids\Users\Referrals\Referral $referral
 * @global int $user_id
 *
 * @since 1.0.0
 * @package GoodBids
 */

?>
<table class="form-table">
	<?php wp_nonce_field( 'update_referral_code', 'wrc_update_ref_code_nonce' ); ?>
	<tr>
		<th><?php esc_html_e( 'Update Referral Code', 'goodbids' ); ?></th>

		<td>
			<label>
				<input type="text" name="wrc_new_ref_code"
					placeholder="<?php esc_attr_e( 'Referral Code', 'goodbids' ); ?>"
					value="<?php echo esc_attr( $referral->get_code() ); ?>"/>
				<br>
				<small><?php esc_html_e( 'Custom refer code', 'goodbids' ); ?></small>
			</label>
		</td>
	</tr>

</table>
