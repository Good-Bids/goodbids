<?php
/**
 * Invoice Status View
 *
 * @global int $auction_id
 *
 * @since 1.0.1
 * @package GoodBids
 */


$auction = goodbids()->auctions->get( $auction_id );
$invoice = false;
if ( $auction->get_invoice_id() ) {
	$invoice = goodbids()->invoices->get_invoice( $auction->get_invoice_id() );
}
?>
<div class="gb-auction-invoice">
	<h3><?php esc_html_e( 'Invoice Status', 'goodbids' ); ?></h3>

	<?php
	printf(
		'<p><strong>%s</strong><span>%s</span></p>',
		esc_html__( 'Generated', 'goodbids' ),
		$invoice ? esc_html( $invoice->get_sent_date() ) : esc_html__( 'No', 'goodbids' )
	);

	if ( $auction->has_ended() ) {
		if ( ! $invoice ) {
			printf(
				'<p><a href="%s" data-auction-id="%s" data-nonce="%s" class="button button-secondary">%s</a></p>',
				'#gb-generate-invoice',
				esc_attr( $auction_id ),
				esc_attr( wp_create_nonce( 'gb-generate-invoice' ) ),
				esc_html__( 'Generate', 'goodbids' )
			);
		} else {
			printf(
				'<p><strong>%s</strong><span>%s</span></p>',
				esc_html__( 'Sent', 'goodbids' ),
				$invoice->is_sent() ? esc_html( $invoice->get_sent_date() ) : esc_html__( 'No', 'goodbids' )
			);

			printf(
				'<p><strong>%s</strong><span>%s</span></p>',
				esc_html__( 'Payment', 'goodbids' ),
				$invoice->is_paid() ? esc_html( $invoice->get_payment_date() ) : esc_html__( 'Unpaid', 'goodbids' )
			);
		}
	}
	?>
</div>
