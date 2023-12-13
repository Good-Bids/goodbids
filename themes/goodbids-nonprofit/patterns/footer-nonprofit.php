<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|50"}}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--50)">

	<!-- wp:image {"width":"30px","aspectRatio":"1","scale":"contain"} -->
		<figure class="wp-block-image is-resized">
			<a href="/">
				<img style="aspect-ratio:1;object-fit:contain;width:30px" src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/images/goodbids-icon.png" alt="<?php esc_attr_e( 'GoodBids', 'goodbids-nonprofit' ); ?>"/>
			</a>
		</figure>
	<!-- /wp:image -->

	<!-- wp:group {"style":{"spacing":{"margin":{"top":"var:preset|spacing|20"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--20)">
		<!-- wp:navigation {"overlayMenu":"never","layout":{"type":"flex","justifyContent":"center"},"fontSize":"small"} /-->
	</div>
	<!-- /wp:group -->

	<!-- wp:paragraph {"align":"center","style":{"elements":{"link":{"color":{"text":"var:preset|color|contrast"}}},"spacing":{"margin":{"top":"var:preset|spacing|20","bottom":"0"}}},"textColor":"secondary","fontSize":"x-small"} -->
	<p class="has-text-align-center has-secondary-color has-text-color has-link-color" style="margin-top:var(--wp--preset--spacing--20);margin-bottom:0;font-size:1rem">
	<?php
	/* Translators: WordPress link. */
		$wordpress_link = '<a href="' . esc_url( __( 'https://wordpress.org', 'twentytwentyfour' ) ) . '" rel="nofollow">' . esc_attr_e( 'WordPress', 'goodbids-nonprofit' ) . '</a>';
		printf(
			/* Translators: Designed with WordPress */
			esc_html__( 'Designed with %1$s', 'twentytwentyfour' ),
			$wordpress_link
		);
		?>
	</p>
	<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
