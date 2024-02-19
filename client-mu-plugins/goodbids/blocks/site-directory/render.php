<?php
/**
 * Block: Site Directory
 *
 * @global array $block
 *
 * @since 1.0.0
 * @package GoodBids
 */

if ( ! is_main_site() ) :
	return;
endif;

?>
<div <?php block_attr( $block, 'my-8' ); ?>>
	<ul class="grid grid-cols-5 gap-8 p-0 m-0 list-none">
		<?php
		goodbids()->sites->loop(
			function ( $site_id ) {
				// Skip main site
				if ( get_main_site_id() === $site_id ) {
					return;
				}
				goodbids()->load_view( 'parts/site-grid.php', compact( 'site_id' ) );
			}
		);
		?>
	</ul>
</div>
