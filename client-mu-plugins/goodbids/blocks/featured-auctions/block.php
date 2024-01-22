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
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_featured_auctions(): array {
		$auctions = goodbids()->sites->get_all_auctions();

		// Sort auctions by highest bid count and then by highest total raised.
		return collect( $auctions )
				->sortByDesc( 'bid_count' )
				->slice( 0, 3 )
				->sortByDesc( 'total_raised' )
				->values()->all();
	}
}
