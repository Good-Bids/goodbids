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

$classes     = 'wp-block-buttons is-vertical is-content-justification-center is-layout-flex wp-block-buttons-is-layout-flex';
$button_url  = $bid_product_id && ! is_admin() ? add_query_arg( 'add-to-cart', $bid_product_id, wc_get_checkout_url() ) : '#';
$button_text = __( 'Bid Now', 'goodbids' );

if ( $bid_product_id && ! is_admin() ) {
	$bid_product = wc_get_product( $bid_product_id );
	$button_text = sprintf(
		/* translators: %s: Bid Price */
		__( 'Bid %s Now', 'goodbids' ),
		wc_price( $bid_product->get_regular_price() )
	);
}
?>
<div <?php block_attr( $block, $classes ); ?>>
	<div class="wp-block-button has-custom-width wp-block-button__width-100">
		<a href="<?php echo esc_url( $button_url ); ?>" class="wp-block-button__link wp-element-button">
			<?php echo wp_kses_post( $button_text ); ?>
		</a>
	</div>
</div>
