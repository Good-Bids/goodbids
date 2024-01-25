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
	 * @since 1.0.0
	 * @var string
	 */
	const PAGE_QUERY_ARG = 'gba-page';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const UPCOMING_QUERY_ARG = 'gba-upcoming';

	/**
	 * @since 1.0.0
	 * @var ini
	 */
	const AUCTION_PER_PAGE = 9;


	/**
	 * Get the current page number
	 *
	 * @return int
	 */
	public function get_current_page(): int {
		return ! empty( $_GET[ self::PAGE_QUERY_ARG ] ) ? intval( $_GET[ self::PAGE_QUERY_ARG ] ) : 1;
	}

	/**
	 * Is it for upcoming auctions
	 *
	 * @return bool
	 */
	public function is_upcoming(): bool {
		return ! empty( $_GET[ self::UPCOMING_QUERY_ARG ] ) ? boolval( $_GET[ self::UPCOMING_QUERY_ARG ] ) : false;
	}


	/**
	 * Get the offset page number
	 *
	 * @return int
	 */
	public function get_offset(): int {
		return $this->get_current_page() == 1 ? 0 : intval( $this->get_current_page() * self::AUCTION_PER_PAGE ) - self::AUCTION_PER_PAGE;
	}


	/**
	 * Get the live auctions for the current pagination
	 *
	 * @param array $all_auctions
	 * @return array
	 */
	public function get_live( array $all_auctions ): array {
		return collect( $all_auctions )->filter(
			function ( $data ) {
				return goodbids()->auctions->has_started( $data['post_id'] ) && ! goodbids()->auctions->has_ended( $data['post_id'] );
			}
		)->all();
	}

	/**
	 * Get the upcoming auctions for the current pagination
	 *
	 * @param array $all_auctions
	 * @return array
	 */
	public function get_upcoming( array $all_auctions ): array {
		return collect( $all_auctions )->filter(
			function ( $data ) {
				return goodbids()->auctions->is_upcoming( $data['post_id'] );
			}
		)->all();
	}


	/**
	 * Filter Auctions
	 *
	 * @return array
	 */
	public function filter_auctions( array $auctions ): array {
		// Determine which auctions to display
		// TODO: more filters will be added
		return collect( $auctions )->slice( $this->get_offset(), $this::AUCTION_PER_PAGE )->all();
	}

	/**
	 * Pagination for all auctions
	 *
	 * @return ?string
	 */
	public function get_pagination( $page_url, $total_pages ): ?string {
		$pre_format = $this->get_current_page() > 0 ? '?' : '&';

		return paginate_links(
			[
				'base'      => $page_url . '%_%',
				'format'    => $pre_format . self::PAGE_QUERY_ARG . '=%#%',
				'current'   => $this->get_current_page(),
				'total'     => $total_pages,
				'prev_next' => true,
				'type'      => 'list',
			]
		);
	}
}
