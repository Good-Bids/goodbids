<?php
/**
 * Block: All Auctions
 *
 * @global array $block
 *
 * @since 1.0.0
 * @package GoodBids
 */

$auctions = goodbids()->sites->get_all_auctions();

if ( ! count( $auctions ) ) :
	if ( is_admin() ) :
		printf(
			'<p>%s</p>',
			esc_html__( 'No auctions found.', 'goodbids' )
		);
	endif;

	return;
endif;
?>
<section <?php block_attr( $block ); ?>>
	<?php goodbids()->load_view( 'parts/auctions-grid.php', compact( 'auctions' ) ); ?>
</section>
