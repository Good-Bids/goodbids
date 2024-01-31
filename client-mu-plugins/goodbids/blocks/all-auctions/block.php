<?php
/**
 * All Auctions Block
 *
 * @since 1.0.0
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
	 * @var string
	 */
	const POPULAR_QUERY_ARG = 'gba-popular';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const NEWEST_QUERY_ARG = 'gba-newest';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const ENDING_QUERY_ARG = 'gba-ending';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const LOWBID_QUERY_ARG = 'gba-lowbid';


	/**
	 * Returns the amount of Auctions to display per page
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_auctions_per_page(): int {
		return intval( goodbids()->get_config( 'blocks.all-auctions.auctions-per-page' ) );
	}

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
	public function is_displaying_upcoming(): bool {
		return ! empty( $_GET[ self::UPCOMING_QUERY_ARG ] ) ? boolval( $_GET[ self::UPCOMING_QUERY_ARG ] ) : false;
	}

	/**
	 * Is it for sorting by newest
	 *
	 * @return bool
	 */
	public function is_sortby_newest(): bool {
		return ! empty( $_GET[ self::NEWEST_QUERY_ARG ] ) ? boolval( $_GET[ self::NEWEST_QUERY_ARG ] ) : false;
	}

	/**
	 * Is it for sorting by ending soonest
	 *
	 * @return bool
	 */
	public function is_sortby_ending(): bool {
		return ! empty( $_GET[ self::ENDING_QUERY_ARG ] ) ? boolval( $_GET[ self::ENDING_QUERY_ARG ] ) : false;
	}

	/**
	 * Is it for sorting by lowest bid
	 *
	 * @return bool
	 */
	public function is_sortby_lowbid(): bool {
		return ! empty( $_GET[ self::LOWBID_QUERY_ARG ] ) ? boolval( $_GET[ self::LOWBID_QUERY_ARG ] ) : false;
	}

	/**
	 * Get the offset for pagination
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_offset(): int {
		$per_page = $this->get_auctions_per_page();
		return 1 === $this->get_current_page() ? 0 : intval( $this->get_current_page() * $per_page ) - $per_page;
	}

	/**
	 * Get the live auctions
	 *
	 * @since 1.0.0
	 *
	 * @param array $all_auctions
	 *
	 * @return array
	 */
	public function get_live_auctions( array $all_auctions = [] ): array {
		if ( ! $all_auctions ) {
			$all_auctions = goodbids()->sites->get_all_auctions();
		}

		return collect( $all_auctions )
			->filter(
				fn ( array $auction ) => goodbids()->sites->swap(
					fn () => goodbids()->auctions->has_started( $auction['post_id'] ) && ! goodbids()->auctions->has_ended( $auction['post_id'] ),
					$auction['site_id']
				)
			)->all();
	}

	/**
	 * Get the upcoming auctions
	 *
	 * @since 1.0.0
	 *
	 * @param array $all_auctions
	 *
	 * @return array
	 */
	public function get_upcoming_auctions( array $all_auctions = [] ): array {
		if ( ! $all_auctions ) {
			$all_auctions = goodbids()->sites->get_all_auctions();
		}

		return collect( $all_auctions )
			->filter(
				fn ( array $auction ) => goodbids()->sites->swap(
					fn () => goodbids()->auctions->is_upcoming( $auction['post_id'] ),
					$auction['site_id']
				)
			)->all();
	}

	/**
	 * Sort Auctions by start date
	 *
	 * @return array
	 */
	public function sortby_start_date( array $auctions ): array {
		return collect( $auctions )->sortBy(
			function ( $data ) {
				return goodbids()->auctions->get_start_date_time( $data['post_id'] );
			}
		)->all();
	}

	/**
	 * Sort Auctions by end date
	 *
	 * @return array
	 */
	public function sortby_end_date( array $auctions ): array {
		return collect( $auctions )->sortBy(
			function ( $data ) {
				return goodbids()->auctions->get_end_date_time( $data['post_id'] );
			}
		)->all();
	}

	/**
	 * Apply filters to the auctions
	 *
	 * @since 1.0.0
	 *
	 * @param array $auctions
	 *
	 * @return array
	 */
	public function apply_filters( array $auctions ): array {
		if ( $this->is_sortby_newest() ) {
			return $this->sortby_start_date( $auctions );
		}

		if ( $this->is_sortby_ending() ) {
			return $this->sortby_end_date( $auctions );
		}

		if ( $this->is_sortby_lowbid() ) {
			return $this->sortby_lowbid( $auctions );
		}

		return $auctions;
	}

	/**
	 * Sort options
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_sort_options(): array {
		if ( $this->is_displaying_upcoming() ) {
			return $this->get_upcoming_sort_options();
		}

		return $this->get_live_sort_options();
	}

	/**
	 * Sort options for live auctions
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_live_sort_options(): array {
		$options = [
			[
				'label' => __( 'Most Popular', 'goodbids' ),
				'value' => self::POPULAR_QUERY_ARG,
			],
			[
				'label'    => __( 'Newest', 'goodbids' ),
				'value'    => self::NEWEST_QUERY_ARG,
				'selected' => $this->is_sortby_newest(),
			],
			[
				'label'    => __( 'Ending Soon', 'goodbids' ),
				'value'    => self::ENDING_QUERY_ARG,
				'selected' => $this->is_sortby_ending(),
			],
			[
				'label'    => __( 'Lowest Current Bid', 'goodbids' ),
				'value'    => self::LOWBID_QUERY_ARG,
				'selected' => $this->is_sortby_lowbid(),
			],
		];

		return $options;
	}

	/**
	 * Sort options for upcoming auctions
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_upcoming_sort_options(): array {
		$options = [
			[
				'label' => __( 'Opening Soon', 'goodbids' ),
				'value' => '',
			],
			[
				'label'    => __( 'Most Watched', 'goodbids' ),
				'value'    => '',
				'selected' => false,
			],
			[
				'label'    => __( 'Lowest Starting Bid', 'goodbids' ),
				'value'    => '',
				'selected' => false,
			],
		];

		return $options;
	}

	/**
	 * Apply pagination to the filtered auctions
	 *
	 * @since 1.0.0
	 *
	 * @param array $auctions
	 *
	 * @return array
	 */
	public function apply_pagination( array $auctions ): array {
		return collect( $auctions )
			->slice( $this->get_offset(), $this->get_auctions_per_page() )
			->all();
	}

	/**
	 * Pagination for all auctions
	 *
	 * @since 1.0.0
	 *
	 * @param string $page_url
	 * @param int    $total_pages
	 *
	 * @return string
	 */
	public function get_pagination( string $page_url, int $total_pages ): string {
		$separator = false === strpos( $page_url, '?' ) ? '?' : '&';

		return paginate_links(
			[
				'base'      => esc_url_raw( $page_url . '%_%' ),
				'format'    => $separator . self::PAGE_QUERY_ARG . '=%#%',
				'add_args'  => false,
				'current'   => $this->get_current_page(),
				'total'     => $total_pages,
				'prev_text' => '&larr;',
				'next_text' => '&rarr;',
				'type'      => 'list',
				'end_size'  => 3,
				'mid_size'  => 3,
			]
		);
	}
}
