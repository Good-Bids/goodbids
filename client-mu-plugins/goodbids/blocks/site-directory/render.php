<?php
/**
 * Block: Site Directory
 *
 * @global array $block
 *
 * @since 1.0.0
 * @package GoodBids
 */

$site_directory = new GoodBids\Blocks\SiteDirectory( $block );

?>
<div <?php block_attr( $block, 'flex gap-3' ); ?>>
	<?php foreach ( $site_directory->get_site_ids() as $nonprofit ) : ?>
	<div>
		<h3>
			<a href="<?php echo esc_url( get_blog_details( $nonprofit )->siteurl ); ?>">
				<?php esc_html_e( get_blog_details( $nonprofit )->blogname, 'goodbids' ); ?>
			</a>
		</h3>
	</div>
	<?php endforeach; ?>
</div>
