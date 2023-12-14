<!-- wp:group {"align":"wide","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide has-background">
	<!-- wp:group {"align":"wide","layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignwide">

		<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|50"}}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
		<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--50)">

			<!-- wp:image {"width":"30px","aspectRatio":"1","scale":"contain"} -->
				<figure class="wp-block-image is-resized">
					<a href="<?php echo esc_url( home_url() ); ?>">
						<img style="aspect-ratio:1;object-fit:contain;width:30px" src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/images/goodbids-icon.png" alt="<?php esc_attr_e( 'GoodBids', 'goodbids-nonprofit' ); ?>"/>
					</a>
				</figure>
			<!-- /wp:image -->

			<!-- wp:site-title /-->

			<!-- wp:group {"layout":{"type":"constrained","contentSize":"350px"},"fontSize":"small"} -->
			<div class="wp-block-group has-small-font-size">
				<!-- wp:site-tagline {"align":"center","fontSize":"small"} /-->
			</div>
			<!-- /wp:group -->

			<!-- wp:group {"style":{"spacing":{"margin":{"top":"var:preset|spacing|20"}}},"layout":{"type":"constrained"}} -->
			<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--20)">
				<!-- wp:navigation {"overlayMenu":"never","layout":{"type":"flex","justifyContent":"center"},"fontSize":"small"} /-->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->

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
