import { queryOptions, useQuery } from '@tanstack/react-query';
import { apiHandler } from './api-handler';

type ProtoAuctionResponse = {
	socketUrl: string;
	accountUrl: string;
	bidUrl: string;
	startTime: string;
	endTime: string;
	totalBids: number;
	totalRaised: number;
	currentBid: number;
	lastBid: number;
};

export type AuctionUpcomingResponse = Omit<
	ProtoAuctionResponse,
	'totalBids' | 'totalRaised' | 'currentBid' | 'lastBid'
> & {
	auctionStatus: 'upcoming';
};

export type AuctionLiveResponse = ProtoAuctionResponse & {
	auctionStatus: 'live';
};

export type AuctionClosedResponse = Omit<
	ProtoAuctionResponse,
	'bidUrl' | 'startTime' | 'currentBid'
> & {
	auctionStatus: 'closed';
};

export type AuctionResponse =
	| AuctionUpcomingResponse
	| AuctionLiveResponse
	| AuctionClosedResponse;

async function getAuction(auctionId: number) {
	const path = `/wp-json/wp/v2/auction/${auctionId}/details`;

	return await apiHandler<AuctionResponse>({
		path,
	});
}

function auctionOptions(auctionId: number, refetchInterval?: number) {
	return queryOptions({
		queryKey: ['auction', auctionId],
		queryFn: async () => getAuction(auctionId),
		refetchInterval: refetchInterval,
		refetchOnWindowFocus: false,
	});
}

export function useGetAuction(auctionId: number, refetchInterval?: number) {
	return useQuery(auctionOptions(auctionId, refetchInterval));
}
