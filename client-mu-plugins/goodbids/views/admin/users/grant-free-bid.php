<?php
/**
 * Grant Free Bid to Users.
 *
 * @var int $user_id The Current User ID.
 *
 * @since 1.0.0
 * @package GoodBids
 */

use GoodBids\Users\Users;
?>
<br>
<h2 id="goodbids-user-free-bids"><?php esc_html_e( 'GoodBids Free Bids', 'goodbids' ); ?></h2>

<table class="form-table">
	<tr>
		<th>
			<label for="<?php echo esc_attr( Users::FREE_BID_REASON_FIELD ); ?>">
				<?php esc_html_e( 'Grant Free Bid', 'goodbids' ); ?>
			</label>
		</th>

		<td>
			<input
				type="text"
				class="regular-text"
				name="<?php echo esc_attr( Users::FREE_BID_REASON_FIELD ); ?>"
				id="<?php echo esc_attr( Users::FREE_BID_REASON_FIELD ); ?>"
				placeholder="<?php esc_attr_e( 'Reason for granting free bid', 'goodbids' ); ?>"
				value=""
			/>
			<button
				type="button"
				class="button button-secondary"
				id="goodbids-grant-free-bid-button"
				data-user-id="<?php echo esc_attr( $user_id ); ?>"
			>
				<?php esc_html_e( 'Grant Free Bid', 'goodbids' ); ?>
			</button>
		</td>
	</tr>

</table>
