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
$reward = goodbids()->rewards->get_product();
?>
<section <?php block_attr( $block, 'woocommerce' ); ?>>
	<div class="woocommerce product">
		<?php
		if ( $reward ) :
			$o_product = $product;
			$product   = $reward;
			wc_get_template( 'single-product/product-image.php' );
			$product = $o_product;
		else :
			printf(
				'<p style="text-align: center;">%s</p>',
				esc_html__( 'No Auction Product selected', 'goodbids' )
			);
		endif;
		?>
	</div>
</section>
