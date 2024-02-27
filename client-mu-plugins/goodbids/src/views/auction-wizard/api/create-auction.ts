import { UseMutationOptions, useMutation } from '@tanstack/react-query';
import apiFetch from '@wordpress/api-fetch';

type CreateAuctionResponse = {
	id: number;
};

export function useCreateAuction(
	options?: UseMutationOptions<
		CreateAuctionResponse,
		unknown,
		CreateAuctionBody,
		unknown
	>,
) {
	return useMutation({
		...options,
		mutationFn: async (body: CreateAuctionBody) =>
			apiFetch<CreateAuctionResponse>({
				path: '/wp/v2/auction',
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
				},

				body: JSON.stringify({
					status: 'draft',
					...body,
				}),
			}),
	});
}

type CreateAuctionBody = {
	title: string;
	acf: {
		auction_start: string;
		auction_end: string;
		bid_extension: {
			minutes: number;
			seconds: number;
		};
		auction_product: number;
		estimated_value: number | null;
		bid_increment: number;
		starting_bid: number;
		auction_goal: number | null;
		expected_high_bid: number | null;
	};
};
