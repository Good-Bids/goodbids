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

/**
 * Class for All Auctions Block
 *
 * @since 1.0.0
 */
class AllAuctions extends ACFBlock {

	/**
	 * Returns an array of all active auctions
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_all_auctions(): array {
		$all_auctions = [];
		foreach ( get_sites() as $nonprofit ) {
			$nonprofit_id = get_object_vars( $nonprofit )['blog_id'];

			$all_auctions[] = $this->get_site_auctions( $nonprofit_id );
		}

		return array_filter( array_merge( ...$all_auctions ) );
	}

	/**
	 * Returns an array of all active auctions
	 *
	 * @since 1.0.0
	 *
	 * @return mixed
	 */
	private function get_site_auctions( $nonprofit_id ): mixed {
		$args = [
			'post_type' => 'gb-auction',
		];

		switch_to_blog( $nonprofit_id );
		$auctions = get_posts( $args );
		foreach ( $auctions as $auction ) {
			if ( has_post_thumbnail( $auction->ID ) ) {
				$auction->img = get_the_post_thumbnail( $auction->ID, 'woocommerce_thumbnail' );
			}
		}
		restore_current_blog();

		return $auctions;
	}
}
