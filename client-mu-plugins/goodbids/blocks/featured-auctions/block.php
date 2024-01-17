<?php
/**
 * Featured Auctions Block
 *
 * @since 1.0.0
 *
 * @package GoodBids
 */

namespace GoodBids\Blocks;

use GoodBids\Plugins\ACF\ACFBlock;
use Illuminate\Support\Collection;

/**
 * Class for Featured Auctions Block
 *
 * @since 1.0.0
 */
class FeaturedAuctions extends ACFBlock {

	/**
	 * Featured Auctions
	 *
	 * @return array
	 */
	public function get_featured_auctions(): array {
		return goodbids()->auctions->get_all_site_auctions();
	}
}
