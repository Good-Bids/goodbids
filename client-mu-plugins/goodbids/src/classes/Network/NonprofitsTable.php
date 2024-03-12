<?php
/**
 * GoodBids Nonprofit Sites Table
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Network;

use WP_List_Table;

/**
 * Nonprofits Table Class
 * @since 1.0.0
 */
class NonprofitsTable extends WP_List_Table {

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct(
			[
				'singular' => __( 'Nonprofit', 'goodbids' ),
				'plural'   => __( 'Nonprofits', 'goodbids' ),
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
	public function column_site_name( array $item ): string {
		$nonprofit = new Nonprofit( $item['ID'] );
		$actions   = [
			'visit'     => sprintf(
				'<a href="%s" target="_blank" rel="noopener">%s</a>',
				esc_url( $nonprofit->get_url() ),
				esc_html__( 'Visit Site', 'goodbids' )
			),
			'dashboard' => sprintf(
				'<a href="%s" rel="noopener">%s</a>',
				esc_url( $nonprofit->get_admin_url() ),
				esc_html__( 'Dashboard', 'goodbids' )
			),
			'edit'      => sprintf(
				'<a href="%s">%s</a>',
				esc_url( $nonprofit->get_edit_url() ),
				esc_html__( 'Edit Site', 'goodbids' )
			),
		];

		return $item['site_name'] . $this->row_actions( $actions );
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
			'site_name' => __( 'Site Name', 'goodbids' ),
			'auctions'  => __( 'Total Auctions', 'goodbids' ),
			'raised'    => __( 'Total Raised', 'goodbids' ),
			'revenue'   => __( 'Total Revenue', 'goodbids' ),
			'status'    => __( 'Status', 'goodbids' ),
			'standing'  => __( 'Account Standing', 'goodbids' ),
			'age'       => __( 'Age', 'goodbids' ),
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

		$per_page     = apply_filters( 'goodbids_nonprofits_table_per_page', 10 );
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
			'site_name' => [
				'site_name', // Sort Parameter
				false, // First Direction: ASC or DESC
				__( 'Site Name', 'goodbids' ), // Abbreviated Label
				__( 'Table ordered by Site Name.', 'goodbids' ), // Full Label
			],
			'auctions'  => [
				'auctions',
				true,
				__( 'Total Auctions', 'goodbids' ),
				__( 'Table ordered by Total Auctions.', 'goodbids' ),
			],
			'raised'    => [
				'raised',
				false,
				__( 'Total Raised', 'goodbids' ),
				__( 'Table ordered by Total Raised.', 'goodbids' ),
			],
			'revenue'   => [
				'revenue',
				false,
				__( 'Total Revenue', 'goodbids' ),
				__( 'Table ordered by Total Revenue.', 'goodbids' ),
			],
			'status'  => [
				'status',
				false,
				__( 'Status', 'goodbids' ),
				__( 'Table ordered by Status.', 'goodbids' ),
			],
			'standing'  => [
				'standing',
				false,
				__( 'Account Standing', 'goodbids' ),
				__( 'Table ordered by Account Standing.', 'goodbids' ),
			],
			'age'       => [
				'registered',
				true,
				_x( 'Age', 'nonprofit', 'goodbids' ),
				__( 'Table ordered by Age.', 'goodbids' ),
				'desc', // Is sorted by default (desc)
			],
		];
	}

	/**
	 * Get Available Standings Filters and counts.
	 *
	 * @since 1.0.0
	 *
	 * @return int[]
	 */
	public function get_standings_filters(): array {
		$nonprofits = goodbids()->network->nonprofits->get_all_nonprofits();
		$filters    = [
			'all' => 0,
		];

		foreach ( $nonprofits as $site_id ) {
			$filters['all'] ++;

			$nonprofit = new Nonprofit( $site_id );
			$standing  = $nonprofit->get_standing();

			if ( array_key_exists( $standing, $filters ) ) {
				$filters[ $standing ] ++;
			} else {
				$filters[ $standing ] = 1;
			}
		}

		return $filters;
	}

	/**
	 * Display Navigation
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
	 * Display Nonprofit Filters
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
		$counts    = $this->get_standings_filters();
		$standing  = ! empty( $_GET['standing'] ) ? sanitize_text_field( $_GET['standing'] ) : 'all'; // phpcs:ignore
		$standings = [
			'all' => __( 'All', 'goodbids' ),
		];

		foreach ( array_keys( $counts ) as $key ) {
			if ( ! array_key_exists( $key, $standings ) ) {
				$standings[ $key ] = $key;
			}
		}

		$count = count( $standings );

		foreach ( $standings as $key => $label ) {
			$filters[] = sprintf(
				'<li class="%s"><a href="%s"%s>%s <span class="count">(%d)</span></a>%s</li>',
				esc_attr( strtolower( $key ) ),
				esc_url( add_query_arg( 'standing', $key ) ),
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
		$filter     = ! empty( $_GET['standing'] ) ? sanitize_text_field( $_GET['standing'] ) : ''; // phpcs:ignore
		$nonprofits = goodbids()->network->nonprofits->get_all_nonprofits();
		$data       = [];
		$columns    = $this->get_columns();
		$order_by   = ! empty( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : false; // phpcs:ignore
		$order      = ! empty( $_GET['order'] ) ? sanitize_text_field( $_GET['order'] ) : 'asc'; // phpcs:ignore

		foreach ( $nonprofits as $site_id ) {
			$nonprofit = new Nonprofit( $site_id );
			$standing  = $nonprofit->get_standing();

			if ( $filter && 'all' !== $filter && $filter !== $standing ) {
				continue;
			}

			// Default row values.
			$row = [
				'ID' => $site_id,
			];

			foreach ( $columns as $column => $label ) {
				$row[ $column ] = $this->get_column( $column, $site_id );
			}

			$row['registered'] = $this->get_column( 'registered', $site_id );

			$data[] = $row;
		}

		if ( ! $order_by ) {
			return $data;
		}

		if ( 'registered' === $order_by ) {
			$order = 'asc' === $order ? 'desc' : 'asc';
		}

		return collect( $data )
			->sortBy(  $order_by, SORT_NATURAL, 'desc' === $order )
			->all();
	}

	/**
	 * Get Column Content
	 *
	 * @since 1.0.0
	 *
	 * @param string $column
	 * @param int    $site_id
	 *
	 * @return string
	 */
	private function get_column( string $column, int $site_id ): string {
		if ( method_exists( $this, "get_col_$column" ) ) {
			return call_user_func( [ $this, "get_col_$column" ], $site_id );
		}

		$nonprofit = new Nonprofit( $site_id );

		return match ( $column ) {
			'site_name'  => sprintf(
				'<a href="%s" target="_blank" rel="noopener" title="%s"><strong>%s</strong></a>',
				esc_html( $nonprofit->get_admin_url() ),
				esc_attr( __( 'Site ID: ', 'goodbids' ) .  $site_id ),
				esc_html( $nonprofit->get_name() )
			),
			'auctions'   => $nonprofit->get_total_auctions(),
			'raised'     => wc_price( $nonprofit->get_total_raised() ),
			'revenue'    => wc_price( $nonprofit->get_total_revenue() ),
			'status'     => ucwords( $nonprofit->get_status() ),
			'standing'   => $nonprofit->get_standing(),
			'age'        => sprintf(
				'<span title="%s">%s</span>',
				esc_attr( $nonprofit->get_registered_date() ),
				esc_html( $nonprofit->get_age() )
			),
			'registered' => $nonprofit->get_registered_date( 'Y-m-d H:i:s' ),
		};
	}

}
