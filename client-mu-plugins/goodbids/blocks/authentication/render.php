<?php
/**
 * Block: User Login/Registration
 *
 * @global array $block
 *
 * @since 1.0.0
 * @package GoodBids
 */

if ( is_admin() ) :
	printf(
		'<p style="text-align: center;">%s</p>',
		esc_html__( 'This will render the Login and Registration form if the user is currently not signed in.', 'goodbids' )
	);
	return;
endif;
?>
<section <?php block_attr( $block ); ?>>
	<?php echo do_shortcode( '[woocommerce_my_account]' ); ?>
</section>
