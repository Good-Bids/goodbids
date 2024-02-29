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
		$actions = [
			'visit'     => sprintf(
				'<a href="%s" target="_blank">%s</a>',
				esc_url( get_site_url( $item['ID'] ) ),
				esc_html__( 'Visit Site', 'goodbids' )
			),
			'dashboard' => sprintf(
				'<a href="%s">%s</a>',
				esc_url( goodbids()->network->nonprofits->get_admin_url( $item['ID'] ) ),
				esc_html__( 'Edit Site', 'goodbids' )
			),
			'edit'      => sprintf(
				'<a href="%s?id=%d">%s</a>',
				esc_url( network_admin_url( 'sites.php' ) ),
				esc_attr( urlencode( $item['ID'] ) ),
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
			'standing'  => __( 'Account Standing', 'goodbids' ),
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

		$per_page     = 10;
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
	 * @return array[]
	 */
	function get_sortable_columns(): array {
		return [];
	}

	/**
	 * Get Available Status Filters and counts.
	 *
	 * @since 1.0.0
	 *
	 * @return int[]
	 */
	public function get_standings_filters(): array {
		$nonprofits = goodbids()->network->nonprofits->get_all_nonprofits();
		$filters  = [
			'all' => 0,
		];

		foreach ( $nonprofits as $site_id ) :
			$filters['all'] ++;

			$standing = goodbids()->network->nonprofits->get_standing( $site_id );

			if ( array_key_exists( $standing, $filters ) ) {
				$filters[ $standing ] ++;
			} else {
				$filters[ $standing ] = 1;
			}
		endforeach;

		return $filters;
	}

	/**
	 * Add Standings Filters.
	 *
	 * @since 1.0.0
	 *
	 * @param string $which
	 *
	 * @return void
	 */
	function display_tablenav( $which ): void {
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
	function get_data(): array {
		$filter     = ! empty( $_GET['standing'] ) ? sanitize_text_field( $_GET['standing'] ) : ''; // phpcs:ignore
		$nonprofits = goodbids()->network->nonprofits->get_all_nonprofits();
		$data       = [];
		$columns    = $this->get_columns();

		foreach ( $nonprofits as $site_id ) :
			goodbids()->sites->swap(
				function( $site_id ) use ( &$data, $columns, $filter ) {
					$standing = goodbids()->network->nonprofits->get_standing( $site_id );

					if ( $filter && 'all' !== $filter && $filter !== $standing ) {
						return;
					}
					$row = [
						'ID' => $site_id,
					];

					foreach ( $columns as $column => $label ) {
						$row[ $column ] = $this->get_column( $column, $site_id );
					}

					$data[] = $row;

				},
				$site_id
			);
		endforeach;

		return $data;
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

		return match ( $column ) {
			'site_name' => sprintf( '<strong>%s</strong>', esc_html( goodbids()->network->nonprofits->get_name( $site_id ) ) ),
			'auctions'  => goodbids()->network->nonprofits->get_total_auctions( $site_id ),
			'raised'    => wc_price( goodbids()->network->nonprofits->get_total_raised( $site_id ) ),
			'revenue'   => wc_price( goodbids()->network->nonprofits->get_total_revenue( $site_id ) ),
			'standing'  => goodbids()->network->nonprofits->get_standing( $site_id ),
		};
	}

}
