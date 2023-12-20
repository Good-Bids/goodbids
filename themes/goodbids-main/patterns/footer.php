<?php
/**
 * Title: Footer with colophon, 4 columns
 * Slug: goodbids-main/footer
 * Categories: footer
 * Block Types: core/template-part/footer
 */
?>

<!-- wp:group {"align":"wide","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide has-background">
	<!-- wp:group {"align":"wide","layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignwide">

		<!-- wp:group {"templateLock":"all","lock":{"move":true,"remove":true},"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"},"fontSize":"small"} -->
		<div class="wp-block-group alignwide has-small-font-size" style="margin-top:var(--wp--preset--spacing--40);margin-bottom:var(--wp--preset--spacing--40)">
			<!-- wp:paragraph -->
				<p><?php esc_html_e( 'GOODBIDS positive auctions', 'goodbids-nonprofit' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
			<div class="wp-block-group">
				<!-- wp:paragraph -->
					<p><a href="#"><?php esc_html_e( 'Terms &amp; Conditions', 'goodbids-nonprofit' ); ?></a></p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph -->
					<p><a href="#"><?php esc_html_e( 'Privacy Policy', 'goodbids-nonprofit' ); ?></a></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
