<?php
/**
 * Site Directory Block
 *
 * @since 1.0.0
 *
 * @package GoodBids
 */

namespace GoodBids\Blocks;

use GoodBids\Plugins\ACF\ACFBlock;

/**
 * Class for Site Directory Block
 *
 * @since 1.0.0
 */
class SiteDirectory extends ACFBlock {

	/**
	 * Returns a list of all the Nonprofits Sites
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_sites(): array {
		$sites = [];

		foreach ( get_sites() as $nonprofit ) {
			$nonprofit_id = get_object_vars( $nonprofit )['blog_id'];

			// Skip main site
			if ( is_main_site( $nonprofit_id ) ) {
				continue;
			}
			$sites[] = [ 'site_id' => $nonprofit_id ];

		}

		return $sites;
	}
}
