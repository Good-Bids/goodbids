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
<!-- wp:group {"tagName":"main","align":"wide"} -->
<main class="wp-block-group alignwide">
	<!-- wp:columns -->
	<div class="wp-block-columns">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:post-featured-image {"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|40"}}}} /-->

			<!-- wp:acf/reward-product-gallery {"name":"acf/reward-product-gallery","mode":"preview"} /-->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:post-title /-->

			<!-- wp:post-excerpt /-->

			<!-- wp:acf/reward-product-stats {"name":"acf/reward-product-stats","mode":"preview"} /-->

			<!-- wp:acf/bid-now {"name":"acf/bid-now","data":{},"mode":"preview"} /-->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</main>
<!-- /wp:group -->
