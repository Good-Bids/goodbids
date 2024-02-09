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
	function __construct() {
		parent::__construct(
			[
				'singular' => __( 'Invoice', 'goodbids' ),
				'plural'   => __( 'Invoices', 'goodbids' ),
				'ajax'     => false
			]
		);
	}

	/**
	 * Column Default
	 *
	 * @since 1.0.0
	 *
	 * @param array|object $item
	 * @param string $column_name
	 *
	 * @return string
	 */
	function column_default( $item, $column_name ): string {
		return $item[ $column_name ];
	}

	/**
	 * Get Columns
	 *
	 * @since 1.0.0
	 * @return string[]
	 */
	function get_columns(): array {
		return [
			'site_name'    => __( 'Site Name', 'goodbids' ),
			'auction'      => __( 'Auction', 'goodbids' ),
			'status'       => __( 'Status', 'goodbids' ),
			'amount'       => __( 'Amount', 'goodbids' ),
			'number'       => __( 'Number', 'goodbids' ),
			'date_created' => __( 'Date Created', 'goodbids' ),
			'due_date'     => __( 'Due Date', 'goodbids' ),
			'url'          => __( 'URL', 'goodbids' ),
		];
	}

	/**
	 * Prepare Table Items
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function prepare_items(): void {
		$columns  = $this->get_columns();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = [ $columns, [], $sortable ];
		$this->items           = $this->get_data();
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
	 * Add Status Filters.
	 *
	 * @since 1.0.0
	 *
	 * @param string $which
	 *
	 * @return void
	 */
	function extra_tablenav( $which ): void {
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

		foreach ( $statuses as $key => $label ) {
			if ( $key === $status ) {
				$filters[] = sprintf(
					'<span>%s</span> (%d)',
					esc_html( $label ),
					$counts[ $key ]
				);
			} else {
				$filters[] = sprintf(
					'<a href="%s">%s</a> (%d)',
					esc_url( add_query_arg( 'status', $key ) ),
					esc_html( $label ),
					$counts[ $key ]
				);
			}
		}

		printf(
		'<div class="alignleft actions">%s</div>',
			wp_kses_post( implode( ' | ', $filters ) )
		);
	}

	/**
	 * Get Table data.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	function get_data(): array {
		$filter   = ! empty( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : ''; // phpcs:ignore
		$invoices = goodbids()->network->invoices->get_all_invoices();
		$data     = [];

		foreach ( $invoices as $invoice ) :
			goodbids()->sites->swap(
				function() use ( $invoice, &$data, $filter ) {
					$invoice = goodbids()->invoices->get_invoice( $invoice['post_id'] );

					if ( $filter && 'all' !== $filter && $filter !== $invoice->get_status() ) {
						return;
					}

					$columns = $this->get_columns();
					$row     = [];

					foreach ( $columns as $column => $label ) {
						$row[ $column ] = $this->get_column( $column, $invoice );
					}

					$data[] = $row;

				},
				$invoice['site_id']
			);
		endforeach;

		return $data;
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
	 * Get Column
	 *
	 * @since 1.0.0
	 *
	 * @param string $column
	 * @param Invoice $invoice
	 *
	 * @return string
	 */
	private function get_column( string $column, Invoice $invoice ): string {
		if ( method_exists( $this, "get_col_$column" ) ) {
			return call_user_func( [ $this, "get_col_$column" ], $invoice );
		}

		return match ( $column ) {
			'site_name'    => get_bloginfo( 'name' ),
			'auction'      => get_the_title( $invoice->get_auction_id() ),
			'status'       => $invoice->get_status(),
			'amount'       => wc_price( $invoice->get_amount() ),
			'number'       => $invoice->get_stripe_invoice_number(),
			'date_created' => get_the_date( 'n/j/Y', $invoice->get_id() ),
			'due_date'     => $invoice->get_due_date( 'n/j/Y' ),
		};
	}

	/**
	 * Get URL Column
	 *
	 * @since 1.0.0
	 *
	 * @param Invoice $invoice
	 *
	 * @return string
	 */
	public function get_col_url( Invoice $invoice ): string {
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
