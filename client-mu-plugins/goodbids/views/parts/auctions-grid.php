<?php
/**
 * Auctions Grid Template
 *
 * @global array $auctions
 *
 * @since 1.0.0
 * @package GoodBids
 */

if ( ! count( $auctions ) ) :
	printf(
		'<p>%s</p>',
		esc_html__( 'No auctions found.', 'goodbids' )
	);
endif;
?>
<ul class="grid grid-cols-1 p-0 list-none gap-x-10 gap-y-16 lg:grid-cols-3 sm:grid-cols-2">
	<?php
	foreach ( $auctions as $auction ) :
		goodbids()->sites->swap(
			function () use ( $auction ) {
				goodbids()->load_view( 'parts/auction.php', [ 'auction_id' => $auction['post_id'] ] );
			},
			$auction['site_id']
		);
	endforeach;
	?>
</ul>
