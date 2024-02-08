<?php
/**
 * Admin Invoice Details
 *
 * @global GoodBids\Nonprofits\Invoice $invoice
 * @global int $auction_id
 *
 * @since 1.0.0
 * @package GoodBids
 */

?>
<h3>
	<strong><?php esc_html_e( 'Auction:', 'goodbids' ); ?></strong>
	<a href="<?php echo esc_url( get_edit_post_link( $auction_id ) ); ?>">
		<?php echo esc_html( get_the_title( $auction_id ) ); ?>
	</a>
</h3>
<p>
	<strong><?php esc_html_e( 'Total Raised:', 'goodbids' ); ?></strong>
	<?php echo wp_kses_post( wc_price( goodbids()->auctions->get_total_raised( $auction_id ) ) ); ?><br>

	<strong><?php esc_html_e( 'Invoice Amount:', 'goodbids' ); ?></strong>
	<?php echo wp_kses_post( wc_price( $invoice->get_amount() ) ); ?><br>

	<strong><?php esc_html_e( 'Please pay by:', 'goodbids' ); ?></strong>
	<?php echo esc_html( $invoice->get_due_date( 'n/j/Y' ) ); ?>
</p>

<?php
$stripe_invoices = goodbids()->invoices->stripe->get_invoices();
foreach ( $stripe_invoices as $stripe_invoice ) {
	echo '<pre>';
	var_dump( $stripe_invoice );
	echo '</pre>';
}
