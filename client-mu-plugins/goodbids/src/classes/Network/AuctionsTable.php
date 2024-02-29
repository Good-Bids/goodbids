<?php
/**
 * GoodBids Auctions Table
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Network;

use GoodBids\Auctions\Auction;
use WP_List_Table;

/**
 * Auctions Table Class
 * @since 1.0.0
 */
class AuctionsTable extends WP_List_Table {

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct(
			[
				'singular' => __( 'Auction', 'goodbids' ),
				'plural'   => __( 'Auctions', 'goodbids' ),
				'ajax'     => false
			]
		);
	}

	/**
	 * Default Column output
	 *
	 * @since 1.0.0
	 *
	 * @param array|object $item
	 * @param string $column_name
	 *
	 * @return string
	 */
	public function column_default( $item, $column_name ): string {
		return $item[ $column_name ];
	}

	/**
	 * Output Row Actions with Site Name
	 *
	 * @since 1.0.0
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	public function column_title( array $item ): string {
		$actions = goodbids()->sites->swap(
			function () use ( $item ) {
				$auction = new Auction( $item['ID'] );
				return [
					'view' => sprintf(
						'<a href="%s" target="_blank" rel="noopener">%s</a>',
						esc_url( $auction->get_url() ),
						esc_html__( 'View', 'goodbids' )
					),
					'edit' => sprintf(
						'<a href="%s" rel="noopener">%s</a>',
						esc_url( $auction->get_edit_url() ),
						esc_html__( 'Edit', 'goodbids' )
					),
				];
			},
			$item['site_id']
		);

		return $item['title'] . $this->row_actions( $actions );
	}

	/**
	 * Get Bulk Actions
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_bulk_actions(): array {
		return [];
	}

	/**
	 * Get Columns
	 *
	 * @since 1.0.0
	 * @return string[]
	 */
	public function get_columns(): array {
		return [
			'title'    => __( 'Auction Title', 'goodbids' ),
			'site'     => __( 'Nonprofit', 'goodbids' ),
			'status'   => __( 'Status', 'goodbids' ),
			'bids'     => __( 'Total Bids', 'goodbids' ),
			'raised'   => __( 'Total Raised', 'goodbids' ),
			'high_bid' => __( 'High Bid', 'goodbids' ),
		];
	}

	/**
	 * Prepare Table Items
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function prepare_items(): void {
		$columns  = $this->get_columns();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = [ $columns, [], $sortable ];
		$this->items           = $this->get_data();

		$per_page     = apply_filters( 'goodbids_auctions_table_per_page', 10 );
		$current_page = $this->get_pagenum();
		$total_items  = count( $this->items );

		$this->set_pagination_args(
			[
				'total_items' => $total_items,
				'per_page'    => $per_page,
			]
		);

		$this->items = array_slice( $this->items, ( ( $current_page - 1 ) * $per_page ), $per_page );
	}

	/**
	 * Get Sortable Columns
	 *
	 * @since 1.0.0
	 *
	 * @return array[]
	 */
	public function get_sortable_columns(): array {
		return [
			'title' => [
				'title', // Sort Parameter
				false, // First Direction: ASC or DESC
				__( 'Auction Title', 'goodbids' ), // Abbreviated Label
				__( 'Table ordered by Auction Title.', 'goodbids' ), // Full Label
			],
			'site'  => [
				'site',
				false,
				__( 'Nonprofit Name', 'goodbids' ),
				__( 'Table ordered by Nonprofit Name.', 'goodbids' ),
			],
			'status'    => [
				'status',
				false,
				__( 'Status', 'goodbids' ),
				__( 'Table ordered by Status.', 'goodbids' ),
			],
			'bids'   => [
				'bids',
				true,
				__( 'Total Bids', 'goodbids' ),
				__( 'Table ordered by Total Bids.', 'goodbids' ),
			],
			'raised'  => [
				'raised_value',
				true,
				__( 'Total Raised', 'goodbids' ),
				__( 'Table ordered by Total Raised.', 'goodbids' ),
				'desc', // Is sorted by default (desc)
			],
			'high_bid'    => [
				'high_bid_value',
				true,
				_x( 'High Bid', 'auction', 'goodbids' ),
				__( 'Table ordered by High Bid.', 'goodbids' ),
			],
		];
	}

	/**
	 * Get Available Status Filters and counts.
	 *
	 * @since 1.0.0
	 *
	 * @return int[]
	 */
	public function get_status_filters(): array {
		$auctions = goodbids()->sites->get_all_auctions();
		$filters  = [
			'all' => 0,
		];

		foreach ( $auctions as $array ) :
			$filters['all'] ++;

			$status = goodbids()->sites->swap(
				function () use ( $array ) {
					$auction = new Auction( $array['post_id'] );
					return $auction->get_status();
				},
				$array['site_id']
			);

			if ( array_key_exists( $status, $filters ) ) {
				$filters[ $status ] ++;
			} else {
				$filters[ $status ] = 1;
			}
		endforeach;

		return $filters;
	}

	/**
	 * Add Filters.
	 *
	 * @since 1.0.0
	 *
	 * @param string $which
	 *
	 * @return void
	 */
	public function display_tablenav( $which ): void {
		if ( 'top' === $which ) {
			echo '<form method="POST" action="">';
			wp_nonce_field( 'bulk-' . $this->_args['plural'] );
			$this->extra_tablenav( $which );
		}
		?>
		<div class="tablenav <?php echo esc_attr( $which ); ?>">
			<?php $this->pagination( $which ); ?>
			<br class="clear" />
		</div>
		<?php

		if ( 'bottom' === $which ) {
			$this->extra_tablenav( $which );
			echo '</form>';
		}
	}

	/**
	 * Display Auction Filters
	 *
	 * @since 1.0.0
	 *
	 * @param string $which
	 *
	 * @return void
	 */
	public function extra_tablenav( $which ) {
		if ( 'top' !== $which ) {
			return;
		}

		$filters   = [];
		$counts    = $this->get_status_filters();
		$standing  = ! empty( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : 'all'; // phpcs:ignore
		$statuses = [
			'all' => __( 'All', 'goodbids' ),
		];

		foreach ( array_keys( $counts ) as $key ) {
			if ( ! array_key_exists( $key, $statuses ) ) {
				$statuses[ $key ] = $key;
			}
		}

		$count = count( $statuses );

		foreach ( $statuses as $key => $label ) {
			$filters[] = sprintf(
				'<li class="%s"><a href="%s"%s>%s <span class="count">(%d)</span></a>%s</li>',
				esc_attr( strtolower( $key ) ),
				esc_url( add_query_arg( 'status', $key ) ),
				$key === $standing ? ' class="current" aria-current="page"' : '',
				esc_html( $label ),
				$counts[ $key ],
				-- $count ? ' |' : ''
			);
		}

		printf(
			'<ul class="subsubsub">%s</ul><br class="clear">',
			wp_kses_post( implode( '', $filters ) )
		);
	}

	/**
	 * Get Table data.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_data(): array {
		$filter   = ! empty( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : ''; // phpcs:ignore
		$auctions = goodbids()->sites->get_all_auctions();
		$data     = [];
		$columns  = $this->get_columns();
		$order_by = ! empty( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : 'raised_value'; // phpcs:ignore
		$order    = ! empty( $_GET['order'] ) ? sanitize_text_field( $_GET['order'] ) : 'desc'; // phpcs:ignore

		foreach ( $auctions as $array ) :
			$status = goodbids()->sites->swap(
				function () use ( $array ) {
					$auction = new Auction( $array['post_id'] );
					return $auction->get_status();
				},
				$array['site_id']
			);

			if ( $filter && 'all' !== $filter && $filter !== $status ) {
				continue;
			}

			// Default row values.
			$row = [
				'ID'      => $array['post_id'],
				'site_id' => $array['site_id'],
			];

			foreach ( $columns as $column => $label ) {
				$row[ $column ] = $this->get_column( $column, $array );
			}

			$row['raised_value']   = $this->get_column( 'raised_value', $array );
			$row['high_bid_value'] = $this->get_column( 'high_bid_value', $array );

			$data[] = $row;
		endforeach;

		if ( ! $order_by ) {
			return $data;
		}

		$type = str_contains( $order_by, '_value' ) ? SORT_NUMERIC : SORT_NATURAL;

		return collect( $data )
			->sortBy( $order_by, $type, 'desc' === $order )
			->all();
	}

	/**
	 * Get Column Content
	 *
	 * @since 1.0.0
	 *
	 * @param string $column
	 * @param array  $array
	 *
	 * @return string
	 */
	private function get_column( string $column, array $array ): string {
		if ( method_exists( $this, "get_col_$column" ) ) {
			return call_user_func( [ $this, "get_col_$column" ], $array );
		}

		return goodbids()->sites->swap(
			function () use ( $column, $array ) {
				$auction   = new Auction( $array['post_id'] );
				$nonprofit = new Nonprofit( $array['site_id'] );

				$last_bid       = $auction->get_last_bid();
				$high_bid_value = $last_bid?->get_subtotal() ?? 0;
				$high_bid       = $high_bid_value ? wc_price( $high_bid_value ) : __( 'N/A', 'goodbids' );

				return match ( $column ) {
					'title'          => sprintf(
						'<a href="%s" target="_blank" rel="noopener"><strong>%s</strong></a>',
						esc_html( $auction->get_url() ),
						esc_html( $auction->get_title() )
					),
					'site'           => $nonprofit->get_name(),
					'status'         => $auction->get_status(),
					'bids'           => $auction->get_total_bids(),
					'raised'         => wc_price( $auction->get_total_raised() ),
					'raised_value'   => $auction->get_total_raised(),
					'high_bid'       => $high_bid,
					'high_bid_value' => $high_bid_value,
				};
			},
			$array['site_id']
		);
	}

}
