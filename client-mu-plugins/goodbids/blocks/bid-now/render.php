<?php
/**
 * Block: Bid Now
 *
 * @global array $block
 *
 * @since 1.0.0
 * @package GoodBids
 */

if ( ! is_admin() && goodbids()->auctions->get_post_type() !== get_post_type() ) :
	// Bail early if we're not inside an Auction post type.
	return;
endif;

$auction_id     = goodbids()->auctions->get_auction_id();
$bid_product_id = goodbids()->auctions->get_bid_product_id( $auction_id );

$classes    = 'wp-block-buttons is-vertical is-content-justification-center is-layout-flex wp-block-buttons-is-layout-flex';
$button_url = $bid_product_id && ! is_admin() ? add_query_arg( 'add-to-cart', $bid_product_id, wc_get_checkout_url() ) : '#';
?>
<div <?php block_attr( $block, $classes ); ?>>
	<div class="wp-block-button has-custom-width wp-block-button__width-100">
		<a href="<?php echo esc_url( $button_url ); ?>" class="wp-block-button__link wp-element-button">
			<?php esc_html_e( 'Bid Now', 'goodbids' ); ?>
		</a>
	</div>
</div>
