<?php
/**
 * GoodBids My Account > Referral Box
 *
 * @since 1.0.0
 * @package GoodBids
 */

?>
<div class="p-4 mt-8 rounded-sm bg-contrast md:p-8">
	<h3 class="mt-0 font-bold normal-case text-base-2"><?php esc_html_e( 'Invite friends, bid for free!', 'goodbids' ); ?></h3>
	<p class="text-sm text-base-2"><?php esc_html_e( 'Earn a free bid for each person who signs up and donates through your link, usable in any GOODBIDS network live auction.', 'goodbids' ); ?></p>
	<?php echo do_shortcode( '[goodbids-referral return="copy-link"]' ); ?>
</div>
