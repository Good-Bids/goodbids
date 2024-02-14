<?php
/**
 * Select User
 *
 * @global int $user_id
 *
 * @since 1.0.0
 * @package GoodBids
 */

?>
<label>
	<select id="goodbids-referrals-search-user-select"
		class="goodbids-referrals-search-users"
		style="width: 100%">
		<option value="-1"><?php esc_html_e( 'Search for a user', 'goodbids' ); ?></option>
	</select>
</label>

<script>
	jQuery(document).ready(function ($) {

		const $gbrSearchUser = $('#goodbids-referrals-search-user-select');
		$gbrSearchUser.select2({
			ajax: {
				url: ajaxurl,
				dataType: 'json',
				delay: 250,
				data: function (params) {
					return {
						term: params.term,
						user_id: <?php echo esc_html( $user_id ); ?>,
						page: params.page || 1,
						action: 'goodbids_referrals_search_user_select2',
						nonce: '<?php echo esc_html( wp_create_nonce( 'goodbids_referrals_search_user_select2' ) ); ?>',
					};
				},
			},
			width: '300px',
			placeholder: '<?php esc_html_e( 'Search for a user by name or email', 'goodbids' ); ?>',
		})
	});
</script>
