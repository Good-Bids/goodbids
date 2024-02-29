<?php
/**
 * My Account: Auctions Page
 *
 * @global array $auctions
 *
 * @since 1.0.0
 * @package GoodBids
 */

?>
<div class="goodbids-auctions">
	<h1><?php esc_html_e( 'Auctions', 'goodbids' ); ?></h1>

	<?php
	if ( ! count( $auctions ) ) :
		wc_print_notice( esc_html__( 'You have not bid on any auctions yet.', 'goodbids' ), 'notice' );
	else :
		goodbids()->load_view( 'woocommerce/myaccount/my-auctions-header.php' );

		goodbids()->load_view( 'woocommerce/myaccount/auctions-table.php', compact( 'auctions' ) );
	endif;
	?>
</div>
