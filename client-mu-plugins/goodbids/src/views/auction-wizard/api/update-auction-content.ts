import { UseMutationOptions, useMutation } from '@tanstack/react-query';
import apiFetch from '@wordpress/api-fetch';

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
				body: JSON.stringify({
					content: getContent(id),
				}),
			}),
	});
}

// TODO: Determine a better way to get this content
function getContent(id: number) {
	return `<!-- wp:group {"tagName":"main","align":"wide","layout":{"type":"constrained"}} -->
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

					<!-- wp:group {"lock":{"move":true,"remove":true},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
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
		`;
}
