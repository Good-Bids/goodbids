import { queryOptions, useQuery } from '@tanstack/react-query';
import { z } from 'zod';
import apiFetch from '@wordpress/api-fetch';

const userSchema = z.object({
	rewardUrl: z.string().optional(),
	userId: z.number(),
	userFreeBids: z.number().default(0),
	userReferralUrl: z.string(),
	userTotalBids: z.number().default(0),
	userTotalDonated: z.number().default(0),
	rewardClaimed: z.boolean().optional().default(false),
});

export type UserResponse = z.infer<typeof userSchema>;

async function getUser(auctionId: number, cookie: string) {
	const path = `/wp/v2/auction/${auctionId}/user`;

	const response = await apiFetch({
		path,
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
		},
		body: JSON.stringify({ cookie }),
	});

	return userSchema.parse(response);
}

function userOptions(auctionId: number, cookie?: string) {
	return queryOptions({
		queryKey: ['user', auctionId, cookie],
		queryFn: async () => getUser(auctionId, cookie as string),
		refetchOnWindowFocus: true,
		enabled: !!cookie,
	});
}

export function useGetUser(auctionId: number, cookie?: string) {
	return useQuery(userOptions(auctionId, cookie));
}
