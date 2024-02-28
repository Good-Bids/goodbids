<?php
/**
 * GoodBids My Account > Dashboard Auctions
 *
 * @since 1.0.0
 * @package GoodBids
 */

$auctions = goodbids()->sites->get_watched_bid_auctions_by_user();

if ( ! count( $auctions ) ) {
	return;
}

printf(
	'<h2 class="mt-10 font-normal text-md>%s</h2>',
	esc_html__( 'Get back into the action!', 'goodbids' )
);

goodbids()->load_view( 'parts/auctions-grid.php', compact( 'auctions' ) );
