<?php
/**
 * GoodBids Bidders Table
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Network;

use GoodBids\Users\Bidder;
use WP_List_Table;

/**
 * Bidders Table Class
 * @since 1.0.0
 */
class BiddersTable extends WP_List_Table {

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct(
			[
				'singular' => __( 'Bidder', 'goodbids' ),
				'plural'   => __( 'Bidders', 'goodbids' ),
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
	 * Output Row Actions with Username
	 *
	 * @since 1.0.0
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	public function column_username( array $item ): string {
		$user    = new Bidder( $item['ID'] );
		$actions = [
			'edit' => sprintf(
				'<a href="%s" rel="noopener">%s</a>',
				esc_url( $user->get_edit_url() ),
				esc_html__( 'Edit', 'goodbids' )
			),
		];

		return $item['username'] . $this->row_actions( $actions );
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
			'username'   => __( 'Username', 'goodbids' ),
			'email'      => __( 'Email', 'goodbids' ),
			'registered' => __( 'Date Registered', 'goodbids' ),
			'bids'       => __( 'Total Bids', 'goodbids' ),
			'donated'    => __( 'Total Donated', 'goodbids' ),
			'free_bids'  => __( 'Free Bids', 'goodbids' ),
			'referrals'  => __( 'Referrals', 'goodbids' ),
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

		$per_page     = apply_filters( 'goodbids_bidders_table_per_page', 10 );
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
			'username'   => [
				'username', // Sort Parameter
				false, // First Direction: ASC or DESC
				__( 'Username', 'goodbids' ), // Abbreviated Label
				__( 'Table ordered by Username.', 'goodbids' ), // Full Label
			],
			'email'      => [
				'email',
				false,
				__( 'Email', 'goodbids' ),
				__( 'Table ordered by Email.', 'goodbids' ),
			],
			'registered' => [
				'registered_date',
				false,
				__( 'Registered', 'goodbids' ),
				__( 'Table ordered by Registered Date.', 'goodbids' ),
			],
			'bids'       => [
				'bids',
				true,
				__( 'Total Bids', 'goodbids' ),
				__( 'Table ordered by Total Bids.', 'goodbids' ),
			],
			'donated'    => [
				'donated_value',
				true,
				__( 'Total Donated', 'goodbids' ),
				__( 'Table ordered by Total Donated.', 'goodbids' ),
				'desc', // Is sorted by default (desc)
			],
			'free_bids'   => [
				'free_bids',
				true,
				_x( 'Free Bids', 'auction', 'goodbids' ),
				__( 'Table ordered by Free Bids.', 'goodbids' ),
			],
			'referrals'   => [
				'referrals',
				true,
				__( 'Referrals', 'goodbids' ),
				__( 'Table ordered by Referrals.', 'goodbids' ),
			],
		];
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
		}
		?>
		<div class="tablenav <?php echo esc_attr( $which ); ?>">
			<?php $this->pagination( $which ); ?>
			<br class="clear" />
		</div>
		<?php

		if ( 'bottom' === $which ) {
			echo '</form>';
		}
	}

	/**
	 * Get Table data.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_data(): array {
		$users    = goodbids()->sites->get_bidding_users();
		$data     = [];
		$columns  = $this->get_columns();
		$order_by = ! empty( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : 'bids'; // phpcs:ignore
		$order    = ! empty( $_GET['order'] ) ? sanitize_text_field( $_GET['order'] ) : 'desc'; // phpcs:ignore

		foreach ( $users as $user_id ) :
			// Default row values.
			$row = [
				'ID' => $user_id,
			];

			foreach ( $columns as $column => $label ) {
				$row[ $column ] = $this->get_column( $column, $user_id );
			}

			$row['donated_value']   = $this->get_column( 'donated_value', $user_id );
			$row['registered_date'] = $this->get_column( 'registered_date', $user_id );

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
	 * @param int    $user_id
	 *
	 * @return string
	 */
	private function get_column( string $column, int $user_id ): string {
		if ( method_exists( $this, "get_col_$column" ) ) {
			return call_user_func( [ $this, "get_col_$column" ], $user_id );
		}

		$user = new Bidder( $user_id );

		return match ( $column ) {
			'username'          => sprintf(
				'<a href="%s" target="_blank" rel="noopener"><strong>%s</strong></a>',
				esc_html( $user->get_edit_url() ),
				esc_html( $user->get_username() )
			),
			'email'           => $user->get_email(),
			'registered'      => $user->get_registered_date(),
			'registered_date' => $user->get_registered_date( 'Y-m-d H:i:s' ),
			'bids'            => $user->get_total_bids(),
			'donated'         => wc_price( $user->get_total_donated() ),
			'donated_value'   => $user->get_total_donated(),
			'free_bids'       => $user->get_total_free_bids(),
			'referrals'       => $user->get_total_referrals(),
		};
	}

}
