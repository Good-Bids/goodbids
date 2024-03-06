<?php
/**
 * Main Footer Block
 *
 * @since 1.0.0
 *
 * @package GoodBids
 */

namespace GoodBids\Blocks;

use GoodBids\Plugins\ACF\ACFBlock;

/**
 * Class for Main Footer Block
 *
 * @since 1.0.0
 */
class MainFooter extends ACFBlock {

	/**
	 * Returns the text for the main site
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_sitename_text(): string {
		$site_name        = __( 'GOODBIDS', 'goodbids' );
		$site_description = __( 'We reimagined nonprofit auctions.', 'goodbids' );

		if ( ! is_multisite() ) {
			return $site_name;
		}

		if ( get_blog_option( get_main_site_id(), 'blogname' ) ) {
			$site_name = get_blog_option( get_main_site_id(), 'blogname' );
		}

		if ( get_blog_option( get_main_site_id(), 'blogdescription' ) ) {
			$site_description = get_blog_option( get_main_site_id(), 'blogdescription' );
		}

		return sprintf(
			'<a href="%s">%s</a> %s',
			esc_url( get_home_url( get_main_site_id() ) ),
			$site_name,
			$site_description
		);
	}
}
