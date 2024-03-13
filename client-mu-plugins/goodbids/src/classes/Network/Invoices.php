<?php
/**
 * GoodBids Network Invoices
 *
 * @since 1.0.0
 * @package GoodBids
 */

namespace GoodBids\Network;

use GoodBids\Utilities\Log;

/**
 * Network Admin Invoices Class
 *
 * @since 1.0.0
 */
class Invoices {

	/**
	 * Invoices Page Slug
	 *
	 * @since 1.0.0
	 * @var string
	 */
	const PAGE_SLUG = 'gb-invoices';

	/**
	 * @since 1.0.0
	 * @var array
	 */
	private array $invoices = [];

	/**
	 * @since 1.0.0
	 */
	public function __construct() {
		// Handle Bulk Actions.
		$this->process_bulk_actions();

		// Render Admin Notices.
		$this->render_admin_notices();
	}

	/**
	 * Get Invoices from all Nonprofit Sites
	 *
	 * @since 1.0.0
	 *
	 * @return array[]
	 */
	public function get_all_invoices(): array {
		if ( ! empty( $this->invoices ) ) {
			return $this->invoices;
		}

		goodbids()->sites->loop(
			function( $site_id ) {
				if ( is_main_site() ) {
					return;
				}

				$site_invoices = goodbids()->invoices->get_all_ids();

				while ( $site_invoices->have_posts() ) {
					$site_invoices->the_post();
					$this->invoices[] = [
						'post_id' => get_the_ID(),
						'site_id' => $site_id,
					];
				}

				wp_reset_postdata();
			}
		);

		return $this->invoices;
	}

	/**
	 * Process single and bulk Invoice actions
	 *
	 * TODO: Move to Background Process.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function process_bulk_actions(): void {
		add_action(
			'admin_init',
			function (): void {
				if ( ! isset( $_GET['page'] ) || self::PAGE_SLUG !== $_GET['page'] ) { // phpcs:ignore
					return;
				}

				$invoices_table = new InvoicesTable();

				$action   = $invoices_table->current_action();
				$ids      = [];
				$redirect = false;

				if ( ! $action || 'heartbeat' === $action ) {
					return;
				}

				if ( ! empty( $_POST['bulk-action'] ) ) { // phpcs:ignore
					$ids = $_POST['bulk-action']; // phpcs:ignore
				} elseif ( ! empty( $_GET['invoice'] ) ) { // phpcs:ignore
					$ids      = [ sanitize_text_field( urldecode( $_GET['invoice'] ) ) ]; // phpcs:ignore
					$redirect = true;
				}

				if ( 'integrity_check' === $action ) {
					foreach ( $ids as $id ) {
						list( $site_id, $invoice_id ) = explode( '|', $id );

						goodbids()->sites->swap(
							function () use ( $invoice_id ) {
								$invoice = goodbids()->invoices->get_invoice( $invoice_id );
								$results = $invoice->integrity_check();

								Log::info( 'Integrity Check Results for Invoice #' . $invoice->get_id() . ': ', compact( 'results' ) );
							},
							$site_id
						);
					}
				} elseif ( 'validation_check' === $action ) {
					goodbids()->invoices->validation_check();
					$redirect = true;
				}

				if ( $redirect ) {
					$redirect = remove_query_arg( [ 'action', 'invoice' ] );
					$redirect = add_query_arg( 'done', $action, $redirect );
					wp_safe_redirect( $redirect );
					exit;
				}
			}
		);
	}

	/**
	 * Render an Admin Notice
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function render_admin_notices(): void {
		add_action(
			'admin_init',
			function (): void {
				if ( empty( $_GET['done'] ) || ! is_network_admin() ) { // phpcs:ignore
					return;
				}

				$message = '';
				$action  = sanitize_text_field( $_GET['done'] ); // phpcs:ignore

				if ( 'integrity_check' === $action ) {
					$message = __( 'Integrity Check Completed. Review logs for report.', 'goodbids' );
				} elseif ( 'validation_check' === $action ) {
					$message = __( 'Validation Check Completed. Review logs for report.', 'goodbids' );
				}

				if ( $message ) {
					goodbids()->utilities->display_admin_success( $message, true, true );
				}
			}
		);
	}
}
