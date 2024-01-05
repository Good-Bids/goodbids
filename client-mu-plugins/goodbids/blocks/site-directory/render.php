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
<div <?php block_attr( $block, 'flex gap-3' ); ?>>
	<?php foreach ( $site_directory->get_sites() as $nonprofit ) : ?>
		<div>
			<h3>
				<a href="<?php echo esc_url( $nonprofit['siteurl'] ); ?>">
					<?php echo esc_html( $nonprofit['blogname'] ); ?>
				</a>
			</h3>
		</div>
	<?php endforeach; ?>
</div>