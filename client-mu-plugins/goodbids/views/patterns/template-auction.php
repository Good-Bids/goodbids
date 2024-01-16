<?php
/**
 * Pattern: Auction Template
 *
 * Auto loaded as the default template for the gb-auction custom post type
 *
 * @since 1.0.0
 * @package GoodBids
 */

?>

<!-- wp:group {"tagName":"main","align":"wide","layout":{"type":"constrained"}} -->
<main class="wp-block-group alignwide">
	<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|30"}}}} -->
	<div class="wp-block-columns alignwide">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:acf/reward-product-gallery {"name":"acf/reward-product-gallery","mode":"preview"} /-->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:post-title /-->

			<!-- wp:post-excerpt /-->

			<!-- wp:acf/auction-metrics-general {"name":"acf/auction-metrics-general","mode":"preview"} /-->

			<!-- wp:acf/bid-now {"name":"acf/bid-now","mode":"preview"} /-->

			<!-- wp:goodbids/bidding {"auctionId":0} -->
				<div id="goodbids-bidding" data-auction-id="0" class="wp-block-goodbids-bidding"></div>
			<!-- /wp:goodbids/bidding -->

		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</main>
<!-- /wp:group -->
