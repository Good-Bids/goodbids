<?php
/**
 * My Account: Free Bids Page
 *
 * @global array $free_bids
 *
 * @package GoodBids
 */

?>
<div class="goodbids-free-bids">
	<h1><?php esc_html_e( 'Free Bids', 'goodbids' ); ?></h1>

	<?php if ( ! count( $free_bids ) ) : ?>
		<p><?php esc_html_e( 'You have not earned any free bids yet.', 'goodbids' ); ?></p>
	<?php else : ?>
		<?php var_dump( $free_bids ); ?>
	<?php endif; ?>
</div>
