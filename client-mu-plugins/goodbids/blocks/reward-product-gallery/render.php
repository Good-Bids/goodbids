<?php
/**
 * Block: Authentication
 *
 * @global array $block
 *
 * @since 1.0.0
 * @package GoodBids
 */
global $product;
$auction_id = is_admin() ? intval( sanitize_text_field( $_GET['post'] ) ) : get_queried_object_id();
$reward_id  = goodbids()->auctions->get_reward_product_id( $auction_id );
$product    = wc_get_product( $reward_id );
?>


<section <?php block_attr( $block ); ?>>
	<?php
	if ( $product ) {
		wc_get_template( 'single-product/product-image.php' );
	} else {
		printf(
			'<p style="text-align: center;">%s</p>',
			esc_html__( 'This will render the the rewards product gallery once an Auction Product is selected', 'goodbids' )
		);
	}
	?>
</section>
