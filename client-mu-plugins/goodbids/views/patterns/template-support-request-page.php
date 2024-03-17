<?php
/**
 * Pattern: Support Request Page Template
 *
 * @since 1.0.0
 * @package GoodBids
 */

?>

<!-- wp:group {"layout":{"type":"constrained","contentSize":"1280px"}} -->
<div class="wp-block-group">
	<!-- wp:acf/support-request-form {"name":"acf/support-request-form","mode":"preview"} -->
	<!-- wp:post-title {"textColor":"base"} /-->

	<!-- wp:paragraph {"textColor":"base"} -->
	<p class="has-base-color has-text-color"><?php esc_html_e( 'Use the form below to submit a support request to this Nonprofit. Your submission will be visible to administrators for this Nonprofit site as well as the GOODBIDS support team. We will respond as soon as we can.', 'goodbids' ); ?></p>
	<!-- /wp:paragraph -->
	<!-- /wp:acf/support-request-form -->
</div>
<!-- /wp:group -->
