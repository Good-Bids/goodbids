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

$site_directory = new GoodBids\Blocks\SiteDirectory( $block );

?>
<div <?php block_attr( $block, 'my-8' ); ?>>
	<ul class="grid grid-cols-5 gap-8 p-0 m-0 list-none">
		<?php
		foreach ( $site_directory->get_sites() as $nonprofit ) {
			goodbids()->sites->swap(
				function () {
					goodbids()->load_view( 'parts/site-grid.php', compact( 'post' ) );
				},
				$nonprofit['site_id']
			);
		}
		?>
	</ul>
</div>
