<?php
/**
 * Block: Featured Auctions
 *
 * @global array $block
 *
 * @since 1.0.0
 * @package GoodBids
 */

$auctions = goodbids()->sites->get_featured_auctions();
?>
<section <?php block_attr( $block ); ?>>
	<?php goodbids()->load_view( 'parts/auctions-grid.php', compact( 'auctions' ) ); ?>
</section>
