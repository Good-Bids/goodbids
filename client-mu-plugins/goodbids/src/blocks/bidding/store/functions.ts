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
	fetchMode: FetchingType['fetchMode'],
) => {
	if (newStatus !== currentStatus) {
		// If the auction is starting, invalidate the auction query
		// and re-fetch it to ensure startTime hasn't changed
		if (newStatus === 'starting') {
			client.invalidateQueries({
				queryKey: ['auction'],
			});
		}

		if (newStatus === 'closing') {
			if (fetchMode === 'polling') {
				client.invalidateQueries({
					queryKey: ['auction'],
				});
			}

			client.invalidateQueries({
				queryKey: ['user'],
			});
		}
	}

	return {
		auctionStatus: newStatus,
	};
};

export function handleSetInitialAuction(
	data: AuctionResponse,
	fetchMode: FetchingType['fetchMode'],
): Partial<UrlsType & TimingType & BidsType & FetchingType> {
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
		return {
			...data,
			startTime: new Date(data.startTime),
			endTime: new Date(data.endTime),
			initialFetchComplete: true,
			fetchMode: fetchMode === 'polling' ? 'polling' : 'socket',
		};
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
): Partial<TimingType & BidsType & FetchingType> {
	if (message.type === 'not-found') {
		return {
			fetchMode: 'polling',
		};
	}

	if (message.type === 'update') {
		return {
			...message.payload,
			startTime: new Date(message.payload.startTime),
			endTime: new Date(message.payload.endTime),
		};
	}

	return {
		...message.payload,
		startTime: new Date(message.payload.startTime),
		endTime: new Date(message.payload.endTime),
		fetchMode: 'no-socket',
	};
}

export function handleSetPollingMode(): Partial<FetchingType> {
	return {
		fetchMode: 'polling',
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
