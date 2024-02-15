<?php
/**
 * Pattern: Logo Grid
 *
 * @since 1.0.0
 * @package GoodBids
 */

?>
<!-- wp:group {"className":"gb-comments","layout":{"type":"constrained"}} -->
<div class="wp-block-group gb-comments">
	<!-- wp:comments {"align":"wide"} -->
	<div class="wp-block-comments alignwide">
		<!-- wp:heading {"level":4,"style":{"typography":{"textTransform":"capitalize","fontStyle":"normal","fontWeight":"400"}}} -->
		<h4 class="wp-block-heading" style="font-style:normal;font-weight:400;text-transform:capitalize">Comments</h4>
		<!-- /wp:heading -->

		<!-- wp:comment-template -->
		<!-- wp:columns -->
		<div class="wp-block-columns">
			<!-- wp:column {"width":"40px"} -->
			<div class="wp-block-column" style="flex-basis:40px">
				<!-- wp:avatar {"size":40,"style":{"border":{"radius":"20px"}}} /-->
			</div>
			<!-- /wp:column -->

			<!-- wp:column -->
			<div class="wp-block-column">
				<!-- wp:comment-author-name {"fontSize":"small"} /-->

				<!-- wp:group {"style":{"spacing":{"margin":{"top":"0px","bottom":"0px"}}},"className":"text-xs","layout":{"type":"flex"}} -->
				<div class="text-xs wp-block-group" style="margin-top:0px;margin-bottom:0px">
					<!-- wp:comment-date {"fontSize":"x-small"} /-->

					<!-- wp:comment-edit-link {"fontSize":"x-small"} /-->
				</div>
				<!-- /wp:group -->

				<!-- wp:comment-content /-->

				<!-- wp:comment-reply-link {"fontSize":"small"} /-->
			</div>
			<!-- /wp:column -->
		</div>
		<!-- /wp:columns -->
		<!-- /wp:comment-template -->

		<!-- wp:comments-pagination -->
		<!-- wp:comments-pagination-previous /-->

		<!-- wp:comments-pagination-numbers /-->

		<!-- wp:comments-pagination-next /-->
		<!-- /wp:comments-pagination -->

		<!-- wp:post-comments-form /-->
	</div>
	<!-- /wp:comments -->
</div>
<!-- /wp:group -->
