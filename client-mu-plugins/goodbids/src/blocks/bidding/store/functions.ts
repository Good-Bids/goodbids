import { AuctionResponse } from '../utils/get-auction';
import { getIsLastBidder } from '../utils/get-is-last-bidder';
import { UserResponse } from '../utils/get-user';
import { client } from '../utils/query-client';
import { SocketMessage } from '../utils/types';
import { StatusAndTimeRemainingType } from './timing';
import {
	TimingType,
	UserType,
	UrlsType,
	BidsType,
	FetchingType,
	AuctionStatus,
	BiddingActions,
} from './types';

export function handleSetUser(data: UserResponse): UserType {
	return data;
}

export const handleSetAuctionStatus = (
	newStatus: StatusAndTimeRemainingType,
	currentStatus: AuctionStatus,
): Partial<TimingType & FetchingType> => {
	// If the auction status has changed, invalidate queries and re-fetch
	// unless the change is from initializing so that we don't immediately
	// re-fetch the fresh auction data
	if (
		newStatus.auctionStatus !== currentStatus &&
		currentStatus !== 'initializing'
	) {
		if (newStatus.auctionStatus === 'closing') {
			client.invalidateQueries({
				queryKey: ['auction'],
			});

			client.invalidateQueries({
				queryKey: ['user'],
			});
		}

		// If the auction is starting, invalidate the auction query
		// and re-fetch it to ensure startTime hasn't changed
		if (
			newStatus.auctionStatus === 'starting' ||
			newStatus.auctionStatus === 'live'
		) {
			client.invalidateQueries({
				queryKey: ['auction'],
			});
		}
	}

	return newStatus;
};

export function handelSetFetchAuction(
	data: AuctionResponse,
	userId: UserType['userId'],
	setInterval: BiddingActions['setInterval'],
): Partial<UrlsType & TimingType & BidsType & FetchingType & UserType> {
	const startTime = new Date(data.startTime);
	const endTime = new Date(data.endTime);

	const isLastBidder = getIsLastBidder(userId, data.lastBidder);

	setInterval(startTime, endTime);

	return {
		...data,
		startTime,
		endTime,
		isLastBidder,
		initialFetchComplete: true,
	};
}

export function handleSetSocketAuction(
	message: SocketMessage,
	userId: UserType['userId'],
	setInterval: BiddingActions['setInterval'],
): Partial<TimingType & BidsType & FetchingType & UserType> {
	if (message.type === 'not-found') {
		return {
			fetchMode: 'polling',
			hasSocketError: true,
		};
	}

	if (message.type === 'update') {
		const isLastBidder = getIsLastBidder(
			userId,
			message.payload.lastBidder,
		);

		const startTime = new Date(message.payload.startTime);
		const endTime = new Date(message.payload.endTime);

		setInterval(startTime, endTime);

		return {
			...message.payload,
			startTime,
			endTime,
			isLastBidder,
			hasSocketError: false,
		};
	}

	return {
		...message.payload,
		startTime: new Date(message.payload.startTime),
		endTime: new Date(message.payload.endTime),
		fetchMode: 'no-socket',
		hasSocketError: false,
	};
}

export function handleSetSocketError(): Partial<FetchingType> {
	return {
		fetchMode: 'polling',
		hasSocketError: true,
	};
}
