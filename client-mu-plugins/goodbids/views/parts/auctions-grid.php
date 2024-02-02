<?php
/**
 * Auctions Grid Template
 *
 * @global array $auctions
 *
 * @since 1.0.0
 * @package GoodBids
 */

?>
<ul class="grid grid-cols-1 gap-8 p-0 list-none lg:grid-cols-3 sm:grid-cols-2">
	<?php
	foreach ( $auctions as $auction ) :
		goodbids()->sites->swap(
			function () use ( $auction ) {
				global $post;
				$og_post = $post;
				$post    = get_post( $auction['post_id'] ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

				goodbids()->load_view( 'parts/auction.php', compact( 'post' ) );

				$post = $og_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			},
			$auction['site_id']
		);
	endforeach;
	?>
</ul>
