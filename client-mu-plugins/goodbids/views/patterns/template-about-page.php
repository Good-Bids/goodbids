<?php
/**
 * Pattern: About Page Template
 *
 * @since 1.0.0
 * @package GoodBids
 */

?>

<!-- wp:group {"layout":{"type":"constrained","contentSize":"1280px"}} -->
<div class="wp-block-group">
	<!-- wp:video -->
	<figure class="wp-block-video"></figure>
	<!-- /wp:video -->
</div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"margin":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained","wideSize":"1280px"}} -->
<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--50);margin-bottom:var(--wp--preset--spacing--50)">
	<!-- wp:group {"layout":{"type":"constrained"}} -->
	<div class="wp-block-group">
		<!-- wp:post-title {"level":1} /-->
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
					<p><?php esc_html_e( 'GOODBIDS is a new project founded by a worldwide team of ruckusmakers. We’re building a tool that connects worthy causes with people who want to have fun supporting them.', 'goodbids' ); ?></p>
					<!-- /wp:paragraph -->

					<!-- wp:paragraph -->
					<p><?php esc_html_e( 'GOODBIDS is free for you to use–the charities pay a fee for the auctions that work, and every penny you bid is transferred directly to the cause you care about.', 'goodbids' ); ?>
					</p>
					<!-- /wp:paragraph -->

					<!-- wp:paragraph -->
					<p><?php esc_html_e( 'Each of us has spent our careers working with important causes, often as volunteers. When Seth Godin, our founder, invented the idea of the positive auction, we knew it was time to offer this tool to non-profits that could benefit from it. The founding team includes our head of partnerships, Anne Marie Cruz, our technical lead, Jasper Croome, and our guru of interaction, Felice Della Gatta. Avaleen Morris leads our non-profit outreach and Jennifer Myers Chua is the guru of prizes. We’re grateful for the support of Automattic, the lead developers of the WordPress universe as well as the warlocks and magicians at Viget.', 'goodbids' ); ?>
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
					<p><?php esc_html_e( 'A GOODBIDS positive auction features a fabulous reward for the winning bidder. The big difference is that EVERY bid is a non-refundable, valued donation to a charity you care about.', 'goodbids' ); ?></p>
					<!-- /wp:paragraph -->

					<!-- wp:paragraph -->
					<p><?php esc_html_e( 'The highest bidder gets a reward, every bidder gets the satisfaction of supporting a good cause, and charities get more contributions and more donors which equals more opportunity to do more good.', 'goodbids' ); ?></p>
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

<!-- wp:group {"style":{"spacing":{"padding":{"right":"0","left":"0","top":"0","bottom":"0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
	<!-- wp:columns {"verticalAlignment":"top","style":{"spacing":{"blockGap":{"top":"0","left":"0"},"margin":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}},"border":{"radius":"16px"},"elements":{"link":{"color":{"text":"var:preset|color|contrast"}}}},"backgroundColor":"contrast-3","textColor":"contrast","className":"has-background"} -->
	<div class="wp-block-columns are-vertically-aligned-top has-background has-contrast-color has-contrast-3-background-color has-text-color has-link-color" style="border-radius:16px;margin-top:var(--wp--preset--spacing--40);margin-bottom:var(--wp--preset--spacing--40)">
		<!-- wp:column {"verticalAlignment":"top","width":"100%","style":{"spacing":{"padding":{"right":"0","left":"0","top":"var:preset|spacing|10","bottom":"var:preset|spacing|10"}}},"layout":{"type":"default"}} -->
		<div class="wp-block-column is-vertically-aligned-top" style="padding-top:var(--wp--preset--spacing--10);padding-right:0;padding-bottom:var(--wp--preset--spacing--10);padding-left:0;flex-basis:100%">
			<!-- wp:heading {"textAlign":"left","style":{"typography":{"textTransform":"none"}},"fontSize":"x-large"} -->
			<h2 class="wp-block-heading has-text-align-left has-x-large-font-size" style="text-transform:none"><?php esc_html_e( 'Have questions or want to learn more about GOODBIDS?', 'goodbids' ); ?></h2>
			<!-- /wp:heading -->

			<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"left"}} -->
			<div class="wp-block-buttons">
				<!-- wp:button {"className":"is-style-outline"} -->
				<div class="wp-block-button is-style-outline"><a href="<?php echo esc_url( network_site_url() ); ?>" class="wp-block-button__link wp-element-button"><?php esc_html_e( 'Visit goodbids.org', 'goodbids' ); ?></a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
