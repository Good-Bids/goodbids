import { queryOptions, useQuery } from '@tanstack/react-query';
import { apiHandler } from './api-handler';
import { z } from 'zod';

const userSchema = z.object({
	rewardUrl: z.string().optional(),
	userId: z.number(),
	userFreeBids: z.number().default(0),
	userTotalBids: z.number().default(0),
	userTotalDonated: z.number().default(0),
});

export type UserResponse = z.infer<typeof userSchema>;

async function getUser(auctionId: number, cookie: string) {
	const path = `/wp-json/wp/v2/auction/${auctionId}/user`;

	const response = await apiHandler({
		path,
		method: 'POST',
		body: { cookie },
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
