<?php
/**
 * All Auctions Block
 *
 * @since 1.0.0
 *
 * @package GoodBids
 */

namespace GoodBids\Blocks;

use GoodBids\Plugins\ACF\ACFBlock;
use Illuminate\Support\Collection;

/**
 * Class for All Auctions Block
 *
 * @since 1.0.0
 */
class AllAuctions extends ACFBlock {

	/**
	 * Returns an array of all active auctions across all sites
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_all_site_auctions(): array {
		return Collection::make( get_sites() )
			->flatMap(
				function ( $site ) {
					$site_id = get_object_vars( $site )['blog_id'];
					return $this->get_site_auctions( $site_id );
				}
			)
			->filter()
			->all();
	}

	/**
	 * Returns an array of all active auctions for a given site
	 *
	 * @since 1.0.0
	 *
	 * @param int $site_id
	 *
	 * @return array
	 */
	private function get_site_auctions( int $site_id ): array {
		// Start by switching to blog and get all auctions
		switch_to_blog( $site_id );
		$args     = [
			'post_type'      => goodbids()->auctions->get_post_type(),
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		];
		$auctions = new \WP_Query( $args );
		restore_current_blog();

		if ( ! $auctions->have_posts() ) {
			return [];
		}
		return collect( $auctions->posts )->map(
			function ( $post_id ) use ( $site_id ) {
				return [
					'post_id' => $post_id,
					'site_id' => $site_id,
				];
			}
		)->all();
	}
}
