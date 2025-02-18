<?php
/**
 * Pattern: Nonprofit Interest Form
 *
 * @since 1.0.0
 * @package GoodBids
 */

?>

<!-- wp:group {"style":{"border":{"radius":"16px"},"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|20","right":"var:preset|spacing|20"},"margin":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}},"elements":{"link":{"color":{"text":"var:preset|color|base-2"}}}},"backgroundColor":"contrast","textColor":"base-2","className":"nonprofit-interest-form","layout":{"type":"constrained","wideSize":"","contentSize":""}} -->
<div class="wp-block-group nonprofit-interest-form has-base-2-color has-contrast-background-color has-text-color has-background has-link-color"
	style="border-radius:16px;margin-top:var(--wp--preset--spacing--50);margin-bottom:var(--wp--preset--spacing--50);padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--20)">
	<!-- wp:group {"style":{"spacing":{"padding":{"right":"var:preset|spacing|10","left":"var:preset|spacing|10","top":"0","bottom":"0"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group" style="padding-top:0;padding-right:var(--wp--preset--spacing--10);padding-bottom:0;padding-left:var(--wp--preset--spacing--10)">
		<!-- wp:heading {"style":{"typography":{"textTransform":"none"},"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base"} -->
		<h2 class="wp-block-heading has-base-color has-text-color has-link-color" style="text-transform:none"><?php esc_html_e( 'Nice to meet you!', 'goodbids' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|base-2"}}}},"textColor":"base-2"} -->
		<p class="has-base-2-color has-text-color has-link-color"><?php esc_html_e( 'Share your email below so we can contact you. We usually follow up within two workdays.', 'goodbids' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:jetpack/contact-form {"subject":"[goodbids.go-vip.net] GOODBIDS for Nonprofit","to":"claire.eisinger@viget.com","className":"is-style-default","textColor":"base","style":{"spacing":{"padding":{"top":"16px","right":"16px","bottom":"16px","left":"16px"}},"elements":{"link":{"color":{"text":"var:preset|color|base"}}}}} -->
	<div class="wp-block-jetpack-contact-form is-style-default has-base-color has-text-color has-link-color" style="padding-top:16px;padding-right:16px;padding-bottom:16px;padding-left:16px">
		<!-- wp:jetpack/field-name {"label":"Primary Contact Name","required":true,"requiredText":"(required)","lineHeight":1} /-->

		<!-- wp:jetpack/field-email {"label":"Primary Contact Email","required":true,"requiredText":"(required)","placeholder":"E.g. email@email.xyz","lineHeight":1} /-->

		<!-- wp:jetpack/field-text {"label":"Nonprofit Legal Name","required":true,"requiredText":"(required)","placeholder":"E.g. My Nonprofit, Inc.","lineHeight":1} /-->

		<!-- wp:jetpack/field-text {"label":"Nonprofit EIN","required":true,"requiredText":"(required)","placeholder":"XX-XXXXXXX","lineHeight":1} /-->

		<!-- wp:jetpack/field-url {"label":"Nonprofit Website","required":true,"requiredText":"(required)","placeholder":"https://","lineHeight":1} /-->

		<!-- wp:jetpack/button {"element":"button","text":"Submit","lock":{"remove":true}} /-->
	</div>
	<!-- /wp:jetpack/contact-form -->
</div>
<!-- /wp:group -->
