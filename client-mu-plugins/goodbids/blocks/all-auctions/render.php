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

global $post;
$og_post = $post;
?>

<section <?php block_attr( $block ); ?>>
	<ul class="grid grid-cols-1 gap-8 list-none lg:grid-cols-3 sm:grid-cols-2">
		<?php
		foreach ( $auctions as $auction ) :
			switch_to_blog( $auction['site_id'] );
			$post = get_post( $auction['post_id'] ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

			include GOODBIDS_PLUGIN_PATH . 'views/parts/auction.php';

			restore_current_blog();
		endforeach;
		$post = $og_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		?>
	</ul>
</section>
