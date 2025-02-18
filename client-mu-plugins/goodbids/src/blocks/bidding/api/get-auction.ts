import { queryOptions, useQuery } from '@tanstack/react-query';
import { z } from 'zod';
import { FetchingType, TimingType } from '../store/types';
import { POLLING_INTERVAL } from '../utils/constants';
import apiFetch from '@wordpress/api-fetch';

const auctionSchema = z.object({
	socketUrl: z.string(),
	accountUrl: z.string(),
	bidUrl: z.string(),
	siteId: z.number(),
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
	const path = `/wp/v2/auction/${auctionId}/details`;

	const response = await apiFetch({
		path,
		headers: {
			'Content-Type': 'application/json',
		},
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
