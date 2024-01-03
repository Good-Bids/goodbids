<?php
/**
 * Site Directory Block
 *
 * @since 1.0.0
 *
 * @package GoodBids
 */

namespace GoodBids\Blocks;

/**
 * Class for Site Directory Block
 *
 * @since 1.0.0
 */
class SiteDirectory {

	/**
	 * Returns a list of all the Nonprofits Sites
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_site_ids(): array {
		$all_nonprofit_ids = [];

		foreach ( get_sites() as $nonprofit ) {
			$nonprofit_id       = get_object_vars( $nonprofit )['blog_id'];
			$nonprofit_siteurl  = get_blog_details( $nonprofit_id )->siteurl;
			$nonprofit_blogname = get_blog_details( $nonprofit_id )->blogname;

			// TODO: filter list by site status and/or other items

			$all_nonprofit_ids[] =
				[
					'blogname' => $nonprofit_blogname,
					'siteurl'  => $nonprofit_siteurl,
				];

		}

		return $all_nonprofit_ids;
	}
}
