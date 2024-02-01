<?php
/**
 * All Auctions Block
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Blocks;

use GoodBids\Plugins\ACF\ACFBlock;
use GoodBids\Auctions\Auctions;

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
	const SORT_QUERY_ARG = 'gba-sort';

	/**
	 * Get popular query arg
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public static function popular_query_arg(): array {
		return [
			'label' => __( 'Most Popular', 'goodbids' ),
			'value' => __( 'popular', 'goodbids' ),
		];
	}

	/**
	 * Get newest query arg
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public static function newest_query_arg(): array {
		return [
			'label' => __( 'Newest', 'goodbids' ),
			'value' => __( 'newest', 'goodbids' ),
		];
	}

	/**
	 * Get lowbid query arg
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public static function lowbid_query_arg(): array {
		return [
			'label' => __( 'Lowest Current Bid', 'goodbids' ),
			'value' => __( 'lowbid', 'goodbids' ),
		];
	}

	/**
	 * Get opening soon query arg
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public static function opening_query_arg(): array {
		return [
			'label' => __( 'Opening Soon', 'goodbids' ),
			'value' => __( 'opening', 'goodbids' ),
		];
	}

	/**
	 * Get lowbid query arg
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public static function start_bid_query_arg(): array {
		return [
			'label' => __( 'Lowest Starting Bid', 'goodbids' ),
			'value' => __( 'startbid', 'goodbids' ),
		];
	}

	/**
	 * Get ending soon query arg
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public static function ending_query_arg(): array {
		return [
			'label' => __( 'Ending Soon', 'goodbids' ),
			'value' => __( 'ending', 'goodbids' ),
		];
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
		return ! empty( $_GET[ self::PAGE_QUERY_ARG ] ) ? intval( $_GET[ self::PAGE_QUERY_ARG ] ) : 1;
	}

	/**
	 * Is it for upcoming auctions
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_displaying_upcoming(): bool {
		return ! empty( $_GET[ self::UPCOMING_QUERY_ARG ] ) ? boolval( $_GET[ self::UPCOMING_QUERY_ARG ] ) : false;
	}

	/**
	 * Is it sorting by $query_arg
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function is_sortby(): string {
		$sort = $_GET[ self::SORT_QUERY_ARG ] ?? '';
		return $sort;
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

		// default to get all auctions from the current site TODO maybe good to make this a transient as well
		return collect( ( goodbids()->auctions->get_all() )->posts )
			->map(
				fn ( int $post_id ) => [
					'post_id' => $post_id,
					'site_id' => get_current_blog_id(),
				]
			)
			->sortByDesc(
				fn ( array $auction ) => [
					'bid_count'    => fn () => goodbids()->auctions->get_bid_count( $auction['post_id'] ),
					'total_raised' => fn () => goodbids()->auctions->get_total_raised( $auction['post_id'] ),
				]
			)
			->all();
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
				fn () => Auctions::STATUS_UPCOMING === goodbids()->auctions->get_status( $auction['post_id'] ),
				$auction['site_id']
			)
		)
		->sortBy(
			fn ( $data ) =>  goodbids()->auctions->get_start_date_time( $data['post_id'] )
		)
		->all();
	}

	/**
	 * Sort Auctions by start date
	 *
	 * @return array
	 */
	public function sortby_start_date( array $auctions ): array {
		return collect( $auctions )
			->sortBy(
				fn ( array $data ) => goodbids()->sites->swap(
					function () use ( &$data ) {
						return goodbids()->auctions->get_start_date_time( $data['post_id'] );
					},
					$data['site_id']
				)
			)
			->all();
	}

	/**
	 * Sort Auctions by end date
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function sortby_end_date( array $auctions ): array {
		return collect( $auctions )
		->sortBy(
			fn ( array $data ) => goodbids()->sites->swap(
				function () use ( &$data ) {
					return goodbids()->auctions->get_end_date_time( $data['post_id'] );
				},
				$data['site_id']
			)
		)->all();
	}

	/**
	 * Sort Auctions by lowest bid
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function sortby_lowbid( array $auctions ): array {
		return collect( $auctions )
		->sortBy(
			fn ( array $data ) => goodbids()->sites->swap(
				function () use ( &$data ) {
					return goodbids()->auctions->bids->get_variation( $data['post_id'] )?->get_price( 'edit' );
				},
				$data['site_id']
			)
		)->all();
	}

	/**
	 * Sort Auctions by most watched
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function sortby_watched( array $auctions ): array {
		// TODO once we have watch auctions set up we can sort by most watched
	}


	/**
	 * Sort Auctions by starting bid
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function sortby_starting_bid( array $auctions ): array {
		return collect( $auctions )
		->sortBy(
			fn ( array $data ) => goodbids()->sites->swap(
				function () use ( &$data ) {
					return goodbids()->auctions->calculate_starting_bid( $data['post_id'] );
				},
				$data['site_id']
			)
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
		if ( $this->is_sortby() == $this->newest_query_arg()['value'] ) {
			return $this->sortby_start_date( $auctions );
		}

		if ( $this->is_sortby() == $this->ending_query_arg()['value'] ) {
			return $this->sortby_end_date( $auctions );
		}

		if ( $this->is_sortby() == $this->lowbid_query_arg()['value'] ) {
			return $this->sortby_lowbid( $auctions );
		}

		if ( $this->is_sortby() == $this->start_bid_query_arg()['value'] ) {
			return $this->sortby_starting_bid( $auctions );
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
	public function get_sort_dropdown_options(): array {
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
		return [
			$this->popular_query_arg(),
			$this->newest_query_arg(),
			$this->ending_query_arg(),
			$this->lowbid_query_arg(),
		];
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
			$this->opening_query_arg(),
			$this->start_bid_query_arg(),
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
