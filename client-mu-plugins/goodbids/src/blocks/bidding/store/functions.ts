import { START_TIME_BUFFER } from '../utils/constants';
import { AuctionResponse } from '../utils/get-auction';
import { UserResponse } from '../utils/get-user';
import { client } from '../utils/query-client';
import { SocketMessage } from '../utils/types';
import {
	TimingType,
	UserType,
	UrlsType,
	BidsType,
	FetchingType,
	AuctionStatus,
} from './types';

export function handleSetUser(data: UserResponse): UserType {
	return {
		...data,
		isUserLoggedIn: true,
	};
}

export const handleSetAuctionStatus = (
	newStatus: AuctionStatus,
	currentStatus: AuctionStatus,
): Partial<TimingType & FetchingType> => {
	if (newStatus !== currentStatus) {
		if (newStatus === 'closing') {
			client.invalidateQueries({
				queryKey: ['auction'],
			});

			client.invalidateQueries({
				queryKey: ['user'],
			});

			return {
				auctionStatus: newStatus,
				fetchMode: 'polling',
			};
		}

		// If the auction is starting, invalidate the auction query
		// and re-fetch it to ensure startTime hasn't changed
		if (newStatus === 'starting' || newStatus === 'live') {
			client.invalidateQueries({
				queryKey: ['auction'],
			});
		}
	}

	return {
		auctionStatus: newStatus,
	};
};

export function handelSetFetchAuction(
	data: AuctionResponse,
	lastBid: BidsType['lastBid'],
	isLastBidder: UserType['isLastBidder'],
): Partial<UrlsType & TimingType & BidsType & FetchingType & UserType> {
	if (data.auctionStatus === 'upcoming') {
		const startTime = new Date(data.startTime);
		const bufferedStartTime = startTime.getTime() - START_TIME_BUFFER;
		const now = new Date().getTime();

		// If the auction is starting in the next minute set the auction
		// status to starting to prevent an unnecessary extra request
		if (now >= bufferedStartTime) {
			return {
				...data,
				auctionStatus: 'starting',
				startTime: new Date(data.startTime),
				endTime: new Date(data.endTime),
				initialFetchComplete: true,
				fetchMode: 'no-socket',
			};
		}

		return {
			...data,
			startTime: new Date(data.startTime),
			endTime: new Date(data.endTime),
			initialFetchComplete: true,
			fetchMode: 'no-socket',
		};
	}

	if (data.auctionStatus === 'live') {
		const response = {
			...data,
			startTime: new Date(data.startTime),
			endTime: new Date(data.endTime),
			initialFetchComplete: true,
			fetchMode: 'socket' as FetchingType['fetchMode'],
		};

		if (lastBid !== data.lastBid && isLastBidder) {
			client.invalidateQueries({
				queryKey: ['user'],
			});

			return {
				...response,
				isLastBidder: false,
			};
		}

		return response;
	}

	return {
		...data,
		endTime: new Date(data.endTime),
		initialFetchComplete: true,
		fetchMode: 'no-socket',
	};
}

export function handleSetSocketAuction(
	message: SocketMessage,
	lastBid: BidsType['lastBid'],
	isLastBidder: UserType['isLastBidder'],
): Partial<TimingType & BidsType & FetchingType & UserType> {
	if (message.type === 'not-found') {
		return {
			fetchMode: 'polling',
			hasSocketError: true,
		};
	}

	if (message.type === 'update') {
		const response = {
			...message.payload,
			startTime: new Date(message.payload.startTime),
			endTime: new Date(message.payload.endTime),
			hasSocketError: false,
		};

		if (lastBid !== message.payload.lastBid && isLastBidder) {
			client.invalidateQueries({
				queryKey: ['user'],
			});

			return {
				...response,
				isLastBidder: false,
			};
		}
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

export function handleSetSocketMode(
	fetchMode: FetchingType['fetchMode'],
	override?: boolean,
): Partial<FetchingType> {
	if (fetchMode === 'polling' && !override) {
		return {
			fetchMode: 'polling',
		};
	}

	return {
		fetchMode: 'socket',
	};
}
