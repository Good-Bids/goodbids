import { useQuery } from '@tanstack/react-query';
import apiFetch from '@wordpress/api-fetch';
import { z } from 'zod';

export function useGetAuction(id: number) {
	return useQuery({
		queryKey: ['auction', id],
		queryFn: async () => {
			const response = await apiFetch({
				path: `/wp/v2/auction/${id}?context=edit`,
			});
			return auctionSchema.parse(response);
		},
	});
}

const auctionSchema = z.object({
	title: z.object({
		raw: z.string().catch(''),
	}),
	content: z.object({
		raw: z.string().catch(''),
	}),
	excerpt: z.object({
		raw: z.string().catch(''),
	}),
	acf: z.object({
		auction_start: z
			.string()
			.catch('')
			.transform((value) => {
				if (value.length > 0) {
					const now = new Date().getTime();
					const start = new Date(value).getTime();

					if (now > start) {
						return '';
					}
				}
				return value;
			}),
		auction_end: z
			.string()
			.catch('')
			.transform((value) => {
				if (value.length > 0) {
					const now = new Date().getTime();
					const end = new Date(value).getTime();

					if (now > end) {
						return '';
					}
				}
				return value;
			}),
		bid_extension: z.object({
			minutes: z
				.number()
				.catch(60)
				.transform((value) => value.toString()),
		}),
		estimated_value: z
			.number()
			.catch(NaN)
			.transform((value) => (value ? value.toString() : '')),
		bid_increment: z
			.number()
			.catch(NaN)
			.transform((value) => (value ? value.toString() : '')),
		starting_bid: z
			.number()
			.catch(NaN)
			.transform((value) => (value ? value.toString() : '')),
		auction_goal: z
			.number()
			.catch(NaN)
			.transform((value) => (value ? value.toString() : '')),
		expected_high_bid: z
			.number()
			.catch(NaN)
			.transform((value) => (value ? value.toString() : '')),
	}),
});
