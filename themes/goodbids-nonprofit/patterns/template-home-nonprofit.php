<!-- wp:group {"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull">
	<!-- wp:media-text {"align":"wide","mediaPosition":"right","verticalAlignment":"top"} -->
	<div class="wp-block-media-text alignwide has-media-on-the-right is-stacked-on-mobile is-vertically-aligned-top">
		<div class="wp-block-media-text__content">
			<!-- wp:heading {"level":1} -->
			<h1 class="wp-block-heading"><?php esc_html_e( 'Nonprofit Name Positive auctions', 'goodbids' ); ?></h1>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p><?php esc_html_e( 'Support your favorite cause and bid on valuable prizes at the same time.', 'goodbids' ); ?></p>
			<!-- /wp:paragraph -->

			<!-- wp:buttons {"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|40"}}}} -->
			<div class="wp-block-buttons" style="margin-bottom:var(--wp--preset--spacing--40)">
				<!-- wp:button -->
				<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( get_post_type_archive_link( goodbids()->auctions->get_post_type() ) ); ?>"><?php esc_html_e( 'See our live auctions', 'goodbids' ); ?></a>
				</div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<figure class="wp-block-media-text__media"></figure>
	</div>
	<!-- /wp:media-text -->

	<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}},"border":{"radius":"0px"}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignwide" style="border-radius:0px;padding-top:0;padding-right:var(--wp--preset--spacing--40);padding-bottom:0;padding-left:var(--wp--preset--spacing--40)">
		<!-- wp:video {"id":103,"align":"wide"} -->
		<figure class="wp-block-video alignwide"></figure>
		<!-- /wp:video -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"},"padding":{"right":"var:preset|spacing|40","left":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignwide" style="margin-top:var(--wp--preset--spacing--50);margin-bottom:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">
		<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"layout":{"type":"constrained","justifyContent":"center"}} -->
		<div class="wp-block-group alignwide" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
			<!-- wp:heading {"align":"wide","fontSize":"xx-large"} -->
			<h2 class="wp-block-heading alignwide has-xx-large-font-size"><?php esc_html_e( 'About our charity', 'goodbids' ); ?></h2>
			<!-- /wp:heading -->
		</div>
		<!-- /wp:group -->

		<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40"}}}} -->
		<div class="wp-block-columns">
			<!-- wp:column {"width":"100%"} -->
			<div class="wp-block-column" style="flex-basis:100%">
				<!-- wp:group {"layout":{"type":"constrained"}} -->
				<div class="wp-block-group">
					<!-- wp:spacer {"height":"60px"} -->
					<div style="height:60px" aria-hidden="true" class="wp-block-spacer"></div>
					<!-- /wp:spacer -->

					<!-- wp:group {"layout":{"type":"constrained"}} -->
					<div class="wp-block-group">
						<!-- wp:paragraph -->
						<p>
							<?php esc_html_e( 'Do you understand this feeling? This breeze, which has travelled from the regions towards which I am advancing, gives me a foretaste of those icy climes. Inspirited by this wind of promise, my daydreams become more fervent and vivid. I try in vain to be persuaded that the pole is the seat of frost and desolation; it ever presents itself to my imagination as the region of beauty and delight.', 'goodbids' ); ?>
						</p>
						<!-- /wp:paragraph -->

						<!-- wp:paragraph -->
						<p>
							<?php
							esc_html_e(
								'I am already far north of London, and as I walk in the streets of Petersburgh, I feel a cold northern breeze play upon my cheeks, which braces my nerves and fills me with delight. Do you understand this feeling? This breeze, which has travelled from the regions towards which I am advancing, gives me a foretaste of those icy climes. Inspirited by this wind of promise, my daydreams become
								more fervent and vivid. I try in vain to be persuaded that the pole is the seat of frost and desolation; it ever presents itself to my imagination as the region of beauty and delight.',
								'goodbids'
							);
							?>
						</p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->

					<!-- wp:spacer {"height":"60px"} -->
					<div style="height:60px" aria-hidden="true" class="wp-block-spacer"></div>
					<!-- /wp:spacer -->

					<!-- wp:group {"layout":{"type":"constrained"}} -->
					<div class="wp-block-group">
						<!-- wp:heading {"style":{"typography":{"textTransform":"none"}}} -->
						<h2 class="wp-block-heading" style="text-transform:none"><?php esc_html_e( 'Every bid is a donation', 'goodbids' ); ?></h2>
						<!-- /wp:heading -->

						<!-- wp:paragraph -->
						<p><?php esc_html_e( 'We’re turning charity auctions and fundraising upside down and inside out.', 'goodbids' ); ?></p>
						<!-- /wp:paragraph -->

						<!-- wp:paragraph -->
						<p><?php esc_html_e( 'A GOODBIDS positive auction has a great prize, and you can submit bids, but the big difference is that every bid is a non-refundable donation to a charity you care about.', 'goodbids' ); ?></p>
						<!-- /wp:paragraph -->

						<!-- wp:paragraph -->
						<p><?php esc_html_e( 'That means that great prizes end up going for really low bids. And charities end up raising more money at the same time.', 'goodbids' ); ?></p>
						<!-- /wp:paragraph -->

						<!-- wp:paragraph -->
						<p><?php esc_html_e( 'All you need to do is find an auction you like and place a bid. The money goes directly to the charity and you’ll get a receipt for your tax deductible donation.', 'goodbids' ); ?></p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:column -->
		</div>
		<!-- /wp:columns -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
