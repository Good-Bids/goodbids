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
		$sitename_text = __( 'GOODBIDS Positive Auctions', 'goodbids' );

		if ( ! is_multisite() ) {
			return $sitename_text;
		}

		return sprintf(
			'%s %s',
			get_blog_option( get_main_site_id(), 'blogname' ),
			get_blog_option( get_main_site_id(), 'blogdescription' )
		);
	}
}
