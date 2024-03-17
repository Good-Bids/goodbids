<?php
/**
 * Admin Invoice Details
 *
 * @global Invoice $invoice
 * @global int     $auction_id
 *
 * @since 1.0.0
 * @package GoodBids
 */

use GoodBids\Nonprofits\Invoice;

$auction = goodbids()->auctions->get( $auction_id );
?>
<h3>
	<strong><?php esc_html_e( 'Auction:', 'goodbids' ); ?></strong>
	<a href="<?php echo esc_url( get_edit_post_link( $auction_id ) ); ?>">
		<?php echo esc_html( $auction->get_title() ); ?>
	</a>
</h3>
<p>
	<strong><?php esc_html_e( 'Total Raised:', 'goodbids' ); ?></strong>
	<?php echo wp_kses_post( wc_price( $auction->get_total_raised() ) ); ?><br>

	<strong><?php esc_html_e( 'Invoice Amount:', 'goodbids' ); ?></strong>
	<?php echo wp_kses_post( wc_price( $invoice->get_amount() ) ); ?><br>

	<strong><?php esc_html_e( 'Please pay by:', 'goodbids' ); ?></strong>
	<?php echo esc_html( $invoice->get_due_date( 'n/j/Y' ) ); ?>
</p>
