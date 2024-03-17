import { UseMutationOptions, useMutation } from '@tanstack/react-query';
import apiFetch from '@wordpress/api-fetch';

type UpdateAuctionContentParams = {
	id: number;
	content: string;
};

type UpdateAuctionResponse = {
	id: number;
};

export function useUpdateAuctionContent(
	options?: UseMutationOptions<
		UpdateAuctionResponse,
		unknown,
		UpdateAuctionContentParams,
		unknown
	>,
) {
	return useMutation({
		...options,
		mutationFn: async ({ id, content }) =>
			apiFetch<UpdateAuctionResponse>({
				path: `/wp/v2/auction/${id}`,
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
				},
				body: JSON.stringify({ content }),
			}),
	});
}
