<?php
/**
 * Block: Auction Share
 *
 * @global array $block
 *
 * @since 1.0.0
 * @package GoodBids
 */


if ( ! is_user_logged_in() ) {
	return;
}
?>
<div <?php block_attr( $block ); ?>>
	<?php echo do_shortcode( '[goodbids-referral return="copy-link-btn"]' ); ?>
</div>
