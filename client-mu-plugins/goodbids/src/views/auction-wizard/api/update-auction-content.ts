import { UseMutationOptions, useMutation } from '@tanstack/react-query';
import apiFetch from '@wordpress/api-fetch';
import { __ } from '@wordpress/i18n';

type UpdateAuctionResponse = {
	id: number;
};

export function useUpdateAuctionContent(
	options?: UseMutationOptions<
		UpdateAuctionResponse,
		unknown,
		number,
		unknown
	>,
) {
	return useMutation({
		...options,
		mutationFn: async (id: number) =>
			apiFetch<UpdateAuctionResponse>({
				path: `/wp/v2/auction/${id}`,
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
				},
				body: JSON.stringify({ content: getContent(id) }),
			}),
	});
}

// TODO: Determine a better way to get this content
function getContent(id: number) {
	return `<!-- wp:group {"tagName":"main","align":"wide","layout":{"type":"constrained"},"metadata":{"name":"Auction Details"}} -->
		<main class="wp-block-group alignwide">
			<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|30"}}}} -->
			<div class="wp-block-columns alignwide">
				<!-- wp:column -->
				<div class="wp-block-column">
					<!-- wp:acf/reward-product-gallery {"name":"acf/reward-product-gallery","mode":"preview"} /-->
				</div>
				<!-- /wp:column -->

				<!-- wp:column -->
				<div class="wp-block-column">
					<!-- wp:acf/auction-watchers {"name":"acf/auction-watchers","mode":"preview","lock":{"move":true,"remove":true}} /-->

					<!-- wp:post-title /-->

					<!-- wp:group {"lock":{"move":true,"remove":true},"layout":{"type":"flex","flexWrap":"wrap"}} -->
					<div class="wp-block-group">
						<!-- wp:acf/watch-auction {"name":"acf/watch-auction","mode":"preview"} /-->

						<!-- wp:acf/auction-share {"name":"acf/auction-share","data":{},"mode":"preview"} /-->
					</div>
					<!-- /wp:group -->

					<!-- wp:post-excerpt /-->

					<!-- wp:acf/auction-metrics-general {"name":"acf/auction-metrics-general","mode":"preview"} /-->

					<!-- wp:goodbids/bidding {"auctionId":${id},"lock":{"move":false,"remove":true}} -->
					<div id="goodbids-bidding" data-auction-id="${id}" class="wp-block-goodbids-bidding"></div>
					<!-- /wp:goodbids/bidding -->

				</div>
				<!-- /wp:column -->
			</div>
			<!-- /wp:columns -->
		</main>
		<!-- /wp:group -->


		<!-- wp:group {"layout":{"type":"constrained","contentSize":"800px","wideSize":"1300px"},"metadata":{"name":"Auction Page Content"}} -->
		<div class="wp-block-group">
			<!-- wp:spacer {"height":"50px"} -->
			<div style="height:50px" aria-hidden="true" class="wp-block-spacer"></div>
			<!-- /wp:spacer -->

			<!-- wp:heading {"style":{"typography":{"textTransform":"none"}},"fontSize":"xx-large"} -->
			<h2 class="wp-block-heading has-xx-large-font-size" style="text-transform:none">${__(
				'About the cause',
				'goodbids',
			)}</h2>
			<!-- /wp:heading -->

			<!-- wp:spacer {"height":"15px"} -->
			<div style="height:15px" aria-hidden="true" class="wp-block-spacer"></div>
			<!-- /wp:spacer -->

			<!-- wp:paragraph -->
			<p>${__(
				'Describe the mission and goals of your organization. How does this auction support your cause? Share any specific challenges or opportunities your organization is addressing. Highlight past achievements and how contributions from this auction will make a difference. If you have any videos or images that visually represent your mission, add them here to create a more compelling story.',
				'goodbids',
			)}</p>
			<!-- /wp:paragraph -->

			<!-- wp:video -->
			<figure class="wp-block-video"></figure>
			<!-- /wp:video -->

			<!-- wp:spacer {"height":"25px"} -->
			<div style="height:25px" aria-hidden="true" class="wp-block-spacer"></div>
			<!-- /wp:spacer -->

			<!-- wp:heading {"style":{"typography":{"textTransform":"none"}},"fontSize":"xx-large"} -->
			<h2 class="wp-block-heading has-xx-large-font-size" style="text-transform:none">${__(
				'About the reward',
				'goodbids',
			)}</h2>
			<!-- /wp:heading -->

			<!-- wp:spacer {"height":"15px"} -->
			<div style="height:15px" aria-hidden="true" class="wp-block-spacer"></div>
			<!-- /wp:spacer -->

			<!-- wp:image -->
			<figure class="wp-block-image"><img alt=""/></figure>
			<!-- /wp:image -->

			<!-- wp:spacer {"height":"15px"} -->
			<div style="height:15px" aria-hidden="true" class="wp-block-spacer"></div>
			<!-- /wp:spacer -->

			<!-- wp:paragraph -->
			<p>${__(
				'Provide a detailed description of the auction prize as well as any additional images that might be relevant. Include any unique features, history, or value that make it desirable. If there are multiple items, consider listing each with a brief description. Mention any sponsors or donors if applicable. Help potential bidders understand why they should be excited about winning this reward.',
				'goodbids',
			)}</p>
			<!-- /wp:paragraph -->

			<!-- wp:spacer {"height":"25px"} -->
			<div style="height:25px" aria-hidden="true" class="wp-block-spacer"></div>
			<!-- /wp:spacer -->

			<!-- wp:heading {"style":{"typography":{"textTransform":"none"}},"fontSize":"xx-large"} -->
			<h2 class="wp-block-heading has-xx-large-font-size" style="text-transform:none">About the sponsor</h2>
			<!-- /wp:heading -->

			<!-- wp:spacer {"height":"15px"} -->
			<div style="height:15px" aria-hidden="true" class="wp-block-spacer"></div>
			<!-- /wp:spacer -->

			<!-- wp:image -->
			<figure class="wp-block-image"><img alt=""/></figure>
			<!-- /wp:image -->

			<!-- wp:spacer {"height":"15px"} -->
			<div style="height:15px" aria-hidden="true" class="wp-block-spacer"></div>
			<!-- /wp:spacer -->

			<!-- wp:paragraph -->
			<p>${__(
				'Introduce the sponsors behind this auction. Describe their role and contributions toward making this auction possible. How do their values align with the mission of your organization? Feel free to share any relevant background information about the sponsors, including their industry, commitment to social causes, and why they chose to support your organization. If applicable, include logos or images that represent the sponsors, ensuring their contributions are visually acknowledged.',
				'goodbids',
			)}</p>
			<!-- /wp:paragraph -->

			<!-- wp:spacer {"height":"25px"} -->
			<div style="height:25px" aria-hidden="true" class="wp-block-spacer"></div>
			<!-- /wp:spacer -->

			<!-- wp:heading {"style":{"typography":{"textTransform":"none"}},"fontSize":"xx-large"} -->
			<h2 class="wp-block-heading has-xx-large-font-size" style="text-transform:none">${__(
				'Shipping and other important information',
				'goodbids',
			)}</h2>
			<!-- /wp:heading -->

			<!-- wp:spacer {"height":"15px"} -->
			<div style="height:15px" aria-hidden="true" class="wp-block-spacer"></div>
			<!-- /wp:spacer -->

			<!-- wp:paragraph -->
			<p>${__(
				'Outline the shipping process, including any geographical restrictions, estimated delivery times, and handling fees. Specify if local pickup is an option. Include any return policies, warranty information, or terms and conditions related to the auction item.',
				'goodbids',
			)}</p>
			<!-- /wp:paragraph -->

			<!-- wp:spacer {"height":"30px"} -->
			<div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
			<!-- /wp:spacer -->

			<!-- wp:group {"className":"gb-comments","layout":{"type":"constrained"}} -->
			<div class="wp-block-group gb-comments">
				<!-- wp:separator {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"className":"is-style-wide"} -->
				<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide" style="margin-top:var(--wp--preset--spacing--40);margin-bottom:var(--wp--preset--spacing--40)" />
				<!-- /wp:separator -->

				<!-- wp:comments -->
				<div class="wp-block-comments">
					<!-- wp:heading {"level":4,"style":{"typography":{"textTransform":"capitalize","fontStyle":"normal","fontWeight":"400"}}} -->
					<h4 class="wp-block-heading" style="font-style:normal;font-weight:400;text-transform:capitalize">${__('Comments', 'goodbids')}</h4>
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


		</div>
		<!-- /wp:group -->
		`;
}
