<?php
/**
 * Bid Buttons View
 *
 * @global BidNow $bid_now
 *
 * @since 1.0.1
 * @package GoodBids
 */

use GoodBids\Blocks\BidNow;

?>
<div class="wp-block-buttons is-vertical is-content-justification-center is-layout-flex wp-block-buttons-is-layout-flex flex-col" style="flex-direction: column;">

	<a class="w-full btn-fill text-center transition-colors" href="h<?php echo esc_url( $bid_now->get_place_bid_url() ); ?>" aria-live="polite" style="opacity: 1;">
		<?php echo wp_kses_post( $bid_now->get_place_bid_text() ); ?>
	</a>

	<a href="#" class="w-full btn-fill-secondary text-center pointer-events-none cursor-not-allowed !text-contrast-4"
	   aria-disabled="true" aria-live="polite" style="opacity: 1;">
		Place free bid (0 available)
	</a>
</div>
