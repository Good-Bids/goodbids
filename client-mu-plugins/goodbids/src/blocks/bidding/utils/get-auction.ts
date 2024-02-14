import { queryOptions, useQuery } from '@tanstack/react-query';
import { apiHandler } from './api-handler';
import { z } from 'zod';
import { FetchingType, TimingType } from '../store/types';
import { POLLING_INTERVAL } from './constants';

const auctionSchema = z.object({
	socketUrl: z.string(),
	accountUrl: z.string(),
	bidUrl: z.string(),
	startTime: z.string(),
	endTime: z.string(),
	totalBids: z.number(),
	totalRaised: z.number(),
	currentBid: z.number(),
	lastBid: z.number().default(0),
	lastBidder: z.number().nullable(),
	freeBidsAvailable: z.number(),
	freeBidsAllowed: z.boolean(),
});

export type AuctionResponse = z.infer<typeof auctionSchema>;

async function getAuction(auctionId: number) {
	const path = `/wp-json/wp/v2/auction/${auctionId}/details`;

	const response = await apiHandler({
		path,
	});

	return auctionSchema.parse(response);
}

function auctionOptions(
	auctionId: number,
	auctionStatus: TimingType['auctionStatus'],
	hasSocketError: FetchingType['hasSocketError'],
) {
	return queryOptions({
		queryKey: ['auction', auctionId],
		queryFn: async () => getAuction(auctionId),
		refetchInterval:
			hasSocketError && auctionStatus === 'live'
				? POLLING_INTERVAL
				: undefined,
	});
}

export function useGetAuction(
	auctionId: number,
	auctionStatus: TimingType['auctionStatus'],
	hasSocketError: FetchingType['hasSocketError'],
) {
	return useQuery(auctionOptions(auctionId, auctionStatus, hasSocketError));
}
