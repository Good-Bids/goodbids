<?php
/**
 * Pattern: Charity Interest Form
 *
 * @since 1.0.0
 * @package GoodBids
 */

?>

<!-- wp:group {"style":{"border":{"radius":"16px"},"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|20","right":"var:preset|spacing|20"},"margin":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}},"elements":{"link":{"color":{"text":"var:preset|color|base-2"}}}},"backgroundColor":"contrast","textColor":"base-2","className":"charity-interest-form","layout":{"type":"constrained","wideSize":"","contentSize":""}} -->
<div class="wp-block-group charity-interest-form has-base-2-color has-contrast-background-color has-text-color has-background has-link-color"
	style="border-radius:16px;margin-top:var(--wp--preset--spacing--50);margin-bottom:var(--wp--preset--spacing--50);padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--20)">
	<!-- wp:group {"style":{"spacing":{"padding":{"right":"var:preset|spacing|10","left":"var:preset|spacing|10","top":"0","bottom":"0"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group" style="padding-top:0;padding-right:var(--wp--preset--spacing--10);padding-bottom:0;padding-left:var(--wp--preset--spacing--10)">
		<!-- wp:heading {"style":{"typography":{"textTransform":"none"},"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base"} -->
		<h2 class="wp-block-heading has-base-color has-text-color has-link-color" style="text-transform:none">Nice to meet you!</h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|base-2"}}}},"textColor":"base-2"} -->
		<p class="has-base-2-color has-text-color has-link-color">Share your email below so we can contact you. We usually follow up within two workdays.</p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:jetpack/contact-form {"subject":"[goodbids.go-vip.net] GOODBIDS for Charities","to":"claire.eisinger@viget.com","className":"is-style-default","textColor":"base","style":{"spacing":{"padding":{"top":"16px","right":"16px","bottom":"16px","left":"16px"}},"elements":{"link":{"color":{"text":"var:preset|color|base"}}}}} -->
	<div class="wp-block-jetpack-contact-form is-style-default has-base-color has-text-color has-link-color has-base-color has-text-color has-link-color" style="padding-top:16px;padding-right:16px;padding-bottom:16px;padding-left:16px">
		<!-- wp:jetpack/field-name {"label":"Primary Contact Name","required":true,"requiredText":"(required)","borderRadius":30,"borderWidth":2,"lineHeight":1,"inputColor":"#FFFFFF","fieldBackgroundColor":"#0A3624","borderColor":"#70FF8F"} /-->

		<!-- wp:jetpack/field-email {"label":"Primary Contact Email","required":true,"requiredText":"(required)","placeholder":"E.g. email@email.xyz","borderRadius":30,"borderWidth":2,"lineHeight":1,"inputColor":"#FFFFFF","fieldBackgroundColor":"#0A3624","borderColor":"#70FF8F"} /-->

		<!-- wp:jetpack/field-text {"label":"Charity Legal Name","required":true,"requiredText":"(required)","placeholder":"E.g. My Charity, Inc.","borderRadius":30,"borderWidth":2,"lineHeight":1,"inputColor":"#FFFFFF","fieldBackgroundColor":"#0A3624","borderColor":"#70FF8F"} /-->

		<!-- wp:jetpack/field-text {"label":"Charity EIN","required":true,"requiredText":"(required)","placeholder":"XX-XXXXXXX","borderRadius":30,"borderWidth":2,"lineHeight":1,"inputColor":"#FFFFFF","fieldBackgroundColor":"#0A3624","borderColor":"#70FF8F"} /-->

		<!-- wp:jetpack/field-url {"label":"Charity Website","required":true,"requiredText":"(required)","placeholder":"https://","borderRadius":30,"borderWidth":2,"lineHeight":1,"inputColor":"#FFFFFF","fieldBackgroundColor":"#0A3624","borderColor":"#70FF8F"} /-->

		<!-- wp:jetpack/button {"element":"button","text":"Submit","textColor":"contrast","backgroundColor":"contrast-4","lock":{"remove":true}} /-->
	</div>
	<!-- /wp:jetpack/contact-form -->
</div>
<!-- /wp:group -->
