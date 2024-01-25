import { AuctionResponse } from '../utils/get-auction';
import { UserResponse } from '../utils/get-user';
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

export const handleSetAuctionStatus = (status: AuctionStatus) => {
	return {
		auctionStatus: status,
	};
};

const startTimeBuffer = 1000 * 60;

export function handleSetInitialAuction(
	data: AuctionResponse,
): Partial<UrlsType & TimingType & BidsType & FetchingType> {
	if (data.auctionStatus === 'upcoming') {
		const startTime = new Date(data.startTime);
		const bufferedStartTime = startTime.getTime() - startTimeBuffer;
		const now = new Date().getTime();

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
			fetchMode: 'socket',
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
