<?php
/**
 * All Auctions Block
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Blocks;

use GoodBids\Auctions\Auction;
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
	const FILTER_QUERY_ARG = 'gba-filter';

	/**
	 * @since 1.0.0
	 * @var string
	 */
	const SORT_QUERY_ARG = 'gba-sort';

	/**
	 * Get a query arg value.
	 *
	 * @since 1.0.0
	 *
	 * @param string $query_arg
	 * @param string $default
	 *
	 * @return string
	 */
	private function get_query_arg( string $query_arg, string $default = '' ): string {
		return ! empty( $_GET[ $query_arg ] ) ? sanitize_text_field( $_GET[ $query_arg ] ) : $default; // phpcs:ignore
	}

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
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_current_page(): int {
		return intval( $this->get_query_arg( self::PAGE_QUERY_ARG, 1 ) );
	}

	/**
	 * Get the sorting query arg
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_current_sort(): string {
		$filter  = $this->get_current_filter();
		$default = 'upcoming' === $filter ? 'newest' : 'popular';

		return $this->get_query_arg( self::SORT_QUERY_ARG, $default );
	}

	/**
	 * Get the offset for pagination
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	private function get_offset(): int {
		$per_page = $this->get_auctions_per_page();
		return 1 === $this->get_current_page() ? 0 : intval( $this->get_current_page() * $per_page ) - $per_page;
	}

	/**
	 * Get the auctions
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_all_auctions(): array {
		// if on the main site, get all auctions from all site
		if ( is_main_site() ) {
			return goodbids()->sites->get_all_auctions();
		}

		// default to get all auctions from the current site
		// TODO: maybe good to make this a transient as well
		return collect( ( goodbids()->auctions->get_all() )->posts )
			->map(
				fn ( int $post_id ) => [
					'post_id' => $post_id,
					'site_id' => get_current_blog_id(),
				]
			)
			->filter(
				fn ( array $auction_data ) => goodbids()->sites->swap(
					function () use ( $auction_data ) {
							$auction = goodbids()->auctions->get( $auction_data['post_id'] );
							return ! $auction->has_ended();
						},
					$auction_data['site_id']
				)
			)
			->sortByDesc(
				function ( array $auction_data ): array {
					$auction = goodbids()->auctions->get( $auction_data['post_id'] );
					return [
						'bid_count'    => $auction->get_bid_count(),
						'total_raised' => $auction->get_total_raised(),
					];
				}
			)
			->all();
	}

	/**
	 * Filter Auctions by filter value.
	 *
	 * @since 1.0.0
	 *
	 * @param string $filter
	 * @param array  $auctions
	 *
	 * @return array
	 */
	private function filter_auctions_by( string $filter, array $auctions = [] ): array {
		if ( ! $auctions ) {
			$auctions = $this->get_all_auctions();
		}

		if ( 'upcoming' === $filter ) {
			return collect( $auctions )
				->filter(
					fn ( array $auction_data ) => goodbids()->sites->swap(
						function () use ( $auction_data ) {
								$auction = goodbids()->auctions->get( $auction_data['post_id'] );
								return Auction::STATUS_UPCOMING === $auction->get_status();
							},
						$auction_data['site_id']
					)
				)
				->all();
		} elseif ( 'live' === $filter ) {
			return collect( $auctions )
				->filter(
					fn ( array $auction_data ) => goodbids()->sites->swap(
						function () use ( $auction_data ) {
							$auction = goodbids()->auctions->get( $auction_data['post_id'] );
							return $auction->has_started() && ! $auction->has_ended();
						},
						$auction_data['site_id']
					)
				)
				->all();
		}

		return $auctions;
	}

	/**
	 * Apply sorting to the auctions
	 *
	 * @since 1.0.0
	 *
	 * @param array $auctions
	 *
	 * @return array
	 */
	public function apply_sort( array $auctions ): array {
		return $this->sort_auctions_by( $this->get_current_sort(), $auctions );
	}

	/**
	 * Get the sorting options based on if filtered by upcoming or live
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_sort_options(): array {
		if ( 'upcoming' === $this->get_current_filter() ) {
			return [
				'newest'   => __( 'Opening Soon', 'goodbids' ),
				'starting' => __( 'Lowest Starting Bid', 'goodbids' ),
			];
		}

		return [
			'popular' => __( 'Most Popular', 'goodbids' ),
			'newest'  => __( 'Newest', 'goodbids' ),
			'ending'  => __( 'Ending Soon', 'goodbids' ),
			'low_bid' => __( 'Lowest Current Bid', 'goodbids' ),
		];
	}

	/**
	 * Sort Auctions by sort value.
	 * Sorting options:
	 *   - Live & Upcoming:
	 *     - newest: Sort by start date
	 *   - Upcoming:
	 *     - starting: Sort by starting bid
	 *   - Live:
	 *     - popular: Sort by most popular
	 *     - ending: Sort by end date
	 *     - low_bid: Sort by lowest current bid (desc)
	 *
	 * @since 1.0.0
	 *
	 * @param string $sort
	 * @param array  $auctions
	 *
	 * @return array
	 */
	private function sort_auctions_by( string $sort, array $auctions = [] ): array {

		$sort_method = match ( $sort ) {
			'newest'   => 'get_start_date_time',
			'starting' => 'calculate_starting_bid',
			'ending'   => 'get_end_date_time',
			'low_bid'  => 'bid_variation_price',
			'popular'  => 'get_watch_count',
			default    => false,
		};

		if ( $sort_method ) {
			return collect( $auctions )
				->sortBy(
					fn( array $auction_data ) => goodbids()->sites->swap(
						function () use ( $auction_data, $sort_method ) {
							$auction = goodbids()->auctions->get( $auction_data['post_id'] );

							if ( method_exists( $auction, $sort_method ) ) {
								return $auction->$sort_method();
							}

							if ( 'bid_variation_price' === $sort_method ) {
								return goodbids()->bids->get_variation( $auction->get_id() )?->get_price( 'edit' );
							}

							return null;
						},
						$auction_data['site_id']
					)
				)
				->all();
		}

		return $auctions;
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
	public function apply_filters( array $auctions = [] ): array {
		return $this->filter_auctions_by( $this->get_current_filter(), $auctions );
	}

	/**
	 * Get Auction filters
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_filters(): array {
		return [
			[
				'label'   => __( 'Live', 'goodbids' ),
				'value'   => 'live',
				'default' => true,
			],
			[
				'label'   => __( 'Coming Soon', 'goodbids' ),
				'value'   => 'upcoming',
				'default' => false,
			],
		];
	}

	/**
	 * Get the current applied filter
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_current_filter(): string {
		$default = 'live';
		$filter  = $this->get_query_arg( self::FILTER_QUERY_ARG, $default );

		foreach ( $this->get_filters() as $option ) {
			if ( $option['value'] === $filter ) {
				return $option['value'];
			}
			if ( $option['default'] ) {
				$default = $option['value'];
			}
		}

		return $default;
	}

	/**
	 * Apply pagination to the auctions array
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
	 * Get the current URL with filters and sorting.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_current_url(): string {
		$auction_post_type = goodbids()->auctions->get_post_type();
		$current_url       = get_permalink( get_queried_object_id() );

		if ( is_post_type_archive( $auction_post_type ) ) {
			$current_url = get_post_type_archive_link( $auction_post_type );
		}

		if ( $this->get_current_filter() ) {
			$current_url = add_query_arg( self::FILTER_QUERY_ARG, $this->get_current_filter(), $current_url );
		}

		if ( $this->get_current_sort() ) {
			$current_url = add_query_arg( self::SORT_QUERY_ARG, $this->get_current_sort(), $current_url );
		}

		return $current_url;
	}

	/**
	 * Get pagination
	 *
	 * @since 1.0.0
	 *
	 * @param int $total_pages
	 *
	 * @return string
	 */
	public function get_pagination( int $total_pages ): string {
		$page_url  = $this->get_current_url();
		$separator = ! str_contains( $page_url, '?' ) ? '?' : '&';

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
