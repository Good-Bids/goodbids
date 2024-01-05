<?php
/**
 * Block: Reward Product Gallery
 *
 * @global array $block
 *
 * @since 1.0.0
 * @package GoodBids
 */

global $product;
$reward_id = goodbids()->auctions->get_reward_product_id( goodbids()->auctions->get_auction_id() );
$reward    = wc_get_product( $reward_id );
if ( $reward ) {
	$product = $reward;
}
?>
<section <?php block_attr( $block, 'woocommerce' ); ?>>
	<div class="woocommerce product">
		<?php
		if ( $product ) :
			wc_get_template( 'single-product/product-image.php' );
		else :
			printf(
				'<p style="text-align: center;">%s</p>',
				esc_html__( 'No Auction Product selected', 'goodbids' )
			);
		endif;
		?>
	</div>
</section>
