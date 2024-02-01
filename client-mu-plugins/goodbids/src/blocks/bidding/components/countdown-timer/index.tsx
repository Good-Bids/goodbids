import { TimeRemaining } from './time-remaining';
import { useEffect, useState } from 'react';
import { useBiddingState } from '../../store';
import { AuctionStatus } from '../../store/types';
import {
	LIVE_AND_CLOSING_DELAY,
	START_TIME_BUFFER,
} from '../../utils/constants';

type TimeRemainingType = {
	status: AuctionStatus;
	timeRemainingMs: number;
};

function getCountdownTime(
	startTimeDate: Date,
	endTimeDate: Date,
	auctionStatus: AuctionStatus,
): TimeRemainingType {
	if (auctionStatus === 'initializing') {
		return { status: 'initializing', timeRemainingMs: 0 };
	}

	const startTime = startTimeDate.getTime();
	const bufferedStartTime = startTime - START_TIME_BUFFER;
	const endTime = endTimeDate.getTime();
	const now = new Date().getTime();

	// If the auction is starting in the next minute, we want to
	// re-fetch auction data to ensure startTime hasn't been updated
	if (now >= bufferedStartTime && now < startTime) {
		return { status: 'starting', timeRemainingMs: startTime - now };
	}

	if (now < startTime) {
		return { status: 'upcoming', timeRemainingMs: startTime - now };
	}

	if (now < startTime + LIVE_AND_CLOSING_DELAY) {
		return { status: 'prelive', timeRemainingMs: 0 };
	}

	if (now < endTime) {
		return { status: 'live', timeRemainingMs: endTime - now };
	}

	if (now < endTime + LIVE_AND_CLOSING_DELAY) {
		return { status: 'preclosing', timeRemainingMs: 0 };
	}

	if (auctionStatus === 'closed') {
		return { status: 'closed', timeRemainingMs: 0 };
	}

	return { status: 'closing', timeRemainingMs: 0 };
}

export function CountdownTimer() {
	const {
		startTime,
		endTime,
		isLastBidder,
		auctionStatus,
		userTotalBids,
		setAuctionStatus,
	} = useBiddingState();

	const [countdownStatus, setCountdownStatus] = useState<TimeRemainingType>(
		getCountdownTime(startTime, endTime, auctionStatus),
	);

	useEffect(() => {
		const interval = setInterval(() => {
			const newCountdownStatus = getCountdownTime(
				startTime,
				endTime,
				auctionStatus,
			);

			if (auctionStatus !== newCountdownStatus.status) {
				setAuctionStatus(newCountdownStatus.status);
			}

			setCountdownStatus(newCountdownStatus);
		}, 1000);

		return () => clearInterval(interval);
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [startTime, endTime, auctionStatus]);

	return (
		<TimeRemaining
			auctionStatus={countdownStatus.status}
			timeRemainingMs={countdownStatus.timeRemainingMs}
			userTotalBids={userTotalBids}
			isLastBidder={isLastBidder}
		/>
	);
}
