<?php
/**
 * GoodBids Nonprofit Invoices Table
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Network;

use GoodBids\Nonprofits\Invoice;
use WP_List_Table;

/**
 * Invoices Table Class
 * @since 1.0.0
 */
class InvoicesTable extends WP_List_Table {

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct(
			[
				'singular' => __( 'Invoice', 'goodbids' ),
				'plural'   => __( 'Invoices', 'goodbids' ),
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
		$page    = ! empty( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : '';
		$actions = [
			'integrity_check' => sprintf(
				'<a href="?page=%s&action=integrity_check&invoice=%s">%s</a>',
				esc_attr( $page ),
				esc_attr( urlencode( $item['ID'] ) ),
				esc_html__( 'Integrity Check', 'goodbids' )
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
		return [
			'integrity_check' => __( 'Integrity Check', 'goodbids' ),
		];
	}

	/**
	 * Get Columns
	 *
	 * @since 1.0.0
	 * @return string[]
	 */
	public function get_columns(): array {
		$check_all = sprintf(
			'<label class="screen-reader-text" for="cb-select-all-1">%s</label>
			<input id="cb-select-all-1" type="checkbox">',
			esc_html__( 'Select All', 'goodbids' )
		);

		return [
			'cb'           => $check_all,
			'site_name'    => __( 'Site Name', 'goodbids' ),
			'auction'      => __( 'Auction', 'goodbids' ),
			'amount'       => __( 'Amount', 'goodbids' ),
			'number'       => __( 'Number', 'goodbids' ),
			'status'       => __( 'Status', 'goodbids' ),
			'due_date'     => __( 'Due Date', 'goodbids' ),
			'date_created' => __( 'Date Created', 'goodbids' ),
			'payment'      => __( 'Payment', 'goodbids' ),
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
	public function get_sortable_columns(): array {
		return [];
	}

	/**
	 * Get Available Status Filters and counts.
	 *
	 * @since 1.0.0
	 *
	 * @return int[]
	 */
	public function get_status_filters(): array {
		$invoices = goodbids()->network->invoices->get_all_invoices();
		$filters  = [
			'all' => 0,
		];

		foreach ( $invoices as $invoice ) :
			goodbids()->sites->swap(
				function() use ( $invoice, &$filters ) {
					$invoice = goodbids()->invoices->get_invoice( $invoice['post_id'] );

					$filters['all'] ++;

					if ( array_key_exists( $invoice->get_status(), $filters ) ) {
						$filters[ $invoice->get_status() ] ++;
					} else {
						$filters[ $invoice->get_status() ] = 1;
					}
				},
				$invoice['site_id']
			);
		endforeach;

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
			<div class="alignleft actions bulkactions" style="margin-top: 0;">
				<?php
				if ( $this->has_items() ) :
					$this->bulk_actions( $which );
				endif;

				$this->validation_button();
				?>
			</div>
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
	 * Display Validation Check
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function validation_button(): void {
		$validation_url = add_query_arg( 'action', 'validation_check', $this->base_url() );
		printf(
			'<a href="%s" class="button button-secondary">%s</a>',
			esc_url( $validation_url ),
			esc_html__( 'Validation Check', 'goodbids' )
		);
	}

	/**
	 * Get the base URL for the page
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	private function base_url(): string {
		return network_admin_url( 'admin.php?page=' . Invoices::PAGE_SLUG );
	}

	/**
	 * Display Invoice Filters
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

		$filters  = [];
		$counts   = $this->get_status_filters();
		$status   = ! empty( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : 'all'; // phpcs:ignore
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
				esc_url( add_query_arg( 'status', $key, $this->base_url() ) ),
				$key === $status ? ' class="current" aria-current="page"' : '',
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
		$invoices = goodbids()->network->invoices->get_all_invoices();
		$data     = [];
		$columns  = $this->get_columns();

		foreach ( $invoices as $invoice ) :
			goodbids()->sites->swap(
				function( $site_id ) use ( $invoice, &$data, $columns, $filter ) {
					$invoice = goodbids()->invoices->get_invoice( $invoice['post_id'] );

					if ( $filter && 'all' !== $filter && $filter !== $invoice->get_status() ) {
						return;
					}

					$id  = $site_id . '|' . $invoice->get_id();
					$row = [
						'ID' => $id,
					];

					foreach ( $columns as $column => $label ) {
						if ( 'cb' === $column ) {
							continue;
						}

						$row[ $column ] = $this->get_column( $column, $invoice, $id );
					}

					$data[] = $row;

				},
				$invoice['site_id']
			);
		endforeach;

		return $data;
	}

	/**
	 * Get Column Content
	 *
	 * @since 1.0.0
	 *
	 * @param string  $column
	 * @param Invoice $invoice
	 * @param string  $id
	 *
	 * @return string
	 */
	private function get_column( string $column, Invoice $invoice, string $id ): string {
		if ( method_exists( $this, "get_col_$column" ) ) {
			return call_user_func( [ $this, "get_col_$column" ], $invoice, $id );
		}

		return match ( $column ) {
			'site_name'    => sprintf( '<strong>%s</strong>', esc_html( get_bloginfo( 'name' ) ) ),
			'status'       => $invoice->get_status(),
			'amount'       => wc_price( $invoice->get_amount() ),
			'number'       => $invoice->get_stripe_invoice_number(),
			'date_created' => get_the_date( 'n/j/Y', $invoice->get_id() ),
			'due_date'     => $invoice->get_due_date( 'n/j/Y' ),
		};
	}

	/**
	 * Output the checkbox column
	 *
	 * @since 1.0.0
	 *
	 * @param $item
	 *
	 * @return string
	 */
	public function column_cb( $item ): string {
		return sprintf(
			'<input type="checkbox" name="bulk-action[]" value="%s" />',
			esc_attr( $item['ID'] )
		);
	}

	/**
	 * Display Auction Info
	 *
	 * @since 1.0.0
	 *
	 * @param Invoice $invoice
	 *
	 * @return string
	 */
	public function get_col_auction( Invoice $invoice ): string {
		$auction_id = $invoice->get_auction_id();
		$edit_url   = get_edit_post_link( $auction_id, 'link' );

		if ( ! $edit_url ) {
			return get_the_title( $auction_id );
		}

		return sprintf(
			'<a href="%s" target="_blank" rel="noopener">%s</a> (<a href="%s" target="_blank" rel="noopener">%s</a>)',
			esc_url( $edit_url ),
			esc_html( get_the_title( $auction_id ) ),
			esc_url( get_permalink( $auction_id ) ),
			esc_html__( 'View', 'goodbids' )
		);
	}

	/**
	 * Get Payment Column
	 *
	 * @since 1.0.0
	 *
	 * @param Invoice $invoice
	 *
	 * @return string
	 */
	public function get_col_payment( Invoice $invoice ): string {
		if ( $invoice->is_paid() ) {
			return sprintf(
				'%s %s',
				esc_html__( 'Paid on', 'goodbids' ),
				esc_html( $invoice->get_payment_date() )
			);
		}

		return sprintf(
			'<a href="%1$s" title="%1$s" class="button button-primary" data-clipboard="%1$s" target="_blank" rel="noopener">%2$s</a>',
			esc_url( $invoice->get_stripe_invoice_url() ),
			esc_html__( 'Copy URL', 'goodbids' )
		);
	}

	/**
	 * Get the Invoice Number
	 *
	 * @since 1.0.0
	 *
	 * @param Invoice $invoice
	 *
	 * @return string
	 */
	public function get_col_number( Invoice $invoice ): string {
		return sprintf(
			'<code title="%1$s" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;display: block;">%1$s</code>',
			esc_attr( $invoice->get_stripe_invoice_number() )
		);
	}
}
