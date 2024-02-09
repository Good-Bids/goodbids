import { LIVE_AND_CLOSING_DELAY, START_TIME_BUFFER } from '../utils/constants';
import {
	AuctionStatus,
	BiddingActions,
	FetchingType,
	TimingType,
} from './types';

export type StatusAndTimeRemainingType = {
	auctionStatus: AuctionStatus;
	timeRemainingMs: number | undefined;
	fetchMode: FetchingType['fetchMode'];
};

function getStatusAndTimeRemaining(
	startTimeDate: Date | undefined,
	endTimeDate: Date | undefined,
	auctionStatus: AuctionStatus,
): StatusAndTimeRemainingType {
	if (!startTimeDate || !endTimeDate) {
		return {
			auctionStatus: 'initializing',
			timeRemainingMs: undefined,
			fetchMode: 'no-socket',
		};
	}

	const startTime = startTimeDate.getTime();
	const bufferedStartTime = startTime - START_TIME_BUFFER;
	const endTime = endTimeDate.getTime();
	const now = new Date().getTime();

	// If the auction is starting in the next minute, we want to
	// re-fetch auction data to ensure startTime hasn't been updated
	if (now >= bufferedStartTime && now < startTime) {
		return {
			auctionStatus: 'starting',
			timeRemainingMs: startTime - now,
			fetchMode: 'no-socket',
		};
	}

	if (now < startTime) {
		return {
			auctionStatus: 'upcoming',
			timeRemainingMs: startTime - now,
			fetchMode: 'no-socket',
		};
	}

	if (now < startTime + LIVE_AND_CLOSING_DELAY) {
		return {
			auctionStatus: 'prelive',
			timeRemainingMs: 0,
			fetchMode: 'socket',
		};
	}

	if (now < endTime) {
		return {
			auctionStatus: 'live',
			timeRemainingMs: endTime - now,
			fetchMode: 'socket',
		};
	}

	if (now < endTime + LIVE_AND_CLOSING_DELAY) {
		return {
			auctionStatus: 'preclosing',
			timeRemainingMs: 0,
			fetchMode: 'no-socket',
		};
	}

	if (auctionStatus === 'closed') {
		return {
			auctionStatus: 'closed',
			timeRemainingMs: 0,
			fetchMode: 'no-socket',
		};
	}

	return {
		auctionStatus: 'closing',
		timeRemainingMs: 0,
		fetchMode: 'no-socket',
	};
}

type HandleSetIntervalParams = Omit<TimingType, 'timeRemainingMs'> &
	Pick<BiddingActions, 'setAuctionStatus'>;

export function handleSetInterval({
	startTime,
	endTime,
	auctionStatus,
	interval,
	setAuctionStatus,
}: HandleSetIntervalParams) {
	const newInterval = setInterval(() => {
		const newStatus = getStatusAndTimeRemaining(
			startTime,
			endTime,
			auctionStatus,
		);

		setAuctionStatus(newStatus);
	}, 1000);

	clearInterval(interval);

	return { interval: newInterval };
}
