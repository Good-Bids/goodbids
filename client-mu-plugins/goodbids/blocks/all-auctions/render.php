<?php
/**
 * Block: All Auctions
 *
 * @global array $block
 *
 * @since 1.0.0
 * @package GoodBids
 */

$all_auctions = new GoodBids\Blocks\AllAuctions( $block );
$auctions     = $all_auctions->get_all_site_auctions();

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
			?>
			<li>
				<a href="<?php echo is_admin() ? '#' : get_the_permalink(); ?>" class="block">
				<?php if ( has_post_thumbnail() ) : ?>
						<figure class="wp-block-post-featured-image aspect-square *:w-full *:h-full">
							<?php the_post_thumbnail( 'woocommerce_thumbnail' ); ?>
						</figure>
					<?php endif; ?>
					<h2 class="wp-block-post-title has-large-font-size">
						<?php the_title(); ?>
					</h2>
				</a>
			</li>
			<?php
			restore_current_blog();
		endforeach;
		$post = $og_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		?>
	</ul>
</section>
