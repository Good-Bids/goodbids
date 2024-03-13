<?php
/**
 * Block: Site Directory
 *
 * @global array $block
 *
 * @since 1.0.0
 * @package GoodBids
 */

use GoodBids\Network\Nonprofit;

if ( ! is_main_site() ) :
	return;
endif;

?>
<div <?php block_attr( $block, 'my-8' ); ?>>
	<ul class="grid grid-cols-1 gap-8 p-0 m-0 list-none sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5">
		<?php
		goodbids()->sites->loop(
			function ( $site_id ) {
				// Skip main site
				if ( get_main_site_id() === $site_id ) {
					return;
				}
				// Skip if site is not live
				$nonprofit = new Nonprofit( get_current_blog_id() );
				if ( Nonprofit::STATUS_LIVE !== $nonprofit->get_status() ) {
					return;
				}
				goodbids()->load_view( 'parts/site-grid.php', compact( 'site_id' ) );
			}
		);
		?>
	</ul>
</div>
