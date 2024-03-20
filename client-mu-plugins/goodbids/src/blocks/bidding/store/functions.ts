import { AuctionResponse } from '../api/get-auction';
import { getIsLastBidder } from '../utils/get-is-last-bidder';
import { UserResponse } from '../api/get-user';
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
	// If the auction status has changed, invalidate queries and re-fetch,
	// unless the change is from initializing so that we don't immediately
	// re-fetch the fresh auction data
	if (
		newStatus.auctionStatus !== currentStatus &&
		currentStatus !== 'initializing'
	) {
		// If the auction is starting or closing, invalidate the auction query
		// and re-fetch it to ensure data has not changed
		if (
			newStatus.auctionStatus === 'starting' ||
			newStatus.auctionStatus === 'live' ||
			newStatus.auctionStatus === 'closing'
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
	setCountdownInterval: BiddingActions['setCountdownInterval'],
): Partial<UrlsType & TimingType & BidsType & FetchingType & UserType> {
	const startTime = new Date(data.startTime);
	const endTime = new Date(data.endTime);

	const isLastBidder = getIsLastBidder(userId, data.lastBidder);

	setCountdownInterval(startTime, endTime);

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
	setCountdownInterval: BiddingActions['setCountdownInterval'],
	savedStartTime: TimingType['startTime'],
	savedEndTime: TimingType['endTime'],
): Partial<TimingType & BidsType & FetchingType & UserType> {
	if (message.type === 'not-found') {
		return {
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

		if (
			(savedStartTime &&
				startTime.getTime() < savedStartTime?.getTime()) ||
			(savedEndTime && endTime.getTime() < savedEndTime?.getTime())
		) {
			return {
				hasSocketError: true,
			};
		}

		setCountdownInterval(startTime, endTime);

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
		hasSocketError: false,
	};
}

export function handleSetSocketError(): Partial<FetchingType> {
	return {
		hasSocketError: true,
	};
}
