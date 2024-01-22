import { useEffect, useState } from 'react';
import { ClockIcon } from './clock-icon';
import { DEMO_DATA } from '../utils/demo-data';
import { useAuction } from '../utils/auction-store';
import { AuctionStatus } from '../utils/types';

type TimeRemainingType = {
	status: AuctionStatus;
	timeRemaining: number;
};

const SECONDS_IN_MINUTE = 60;
const SECONDS_IN_HOUR = 60 * SECONDS_IN_MINUTE;
const SECONDS_IN_DAY = 24 * SECONDS_IN_HOUR;

function formatTimeRemaining(timeRemaining: number) {
	const seconds = Math.floor(timeRemaining / 1000);

	if (seconds > SECONDS_IN_DAY) {
		if (seconds > 2 * SECONDS_IN_DAY) {
			return `${Math.floor(seconds / SECONDS_IN_DAY)} days`;
		}

		return `${Math.floor(seconds / SECONDS_IN_DAY)} day`;
	}

	if (seconds > SECONDS_IN_HOUR) {
		const minutes = Math.floor(
			(seconds % SECONDS_IN_HOUR) / SECONDS_IN_MINUTE,
		);

		if (minutes > 0) {
			return `${Math.floor(
				seconds / SECONDS_IN_HOUR,
			)} hours, ${minutes} minutes`;
		}

		return `${Math.floor(seconds / SECONDS_IN_HOUR)} hours`;
	}

	const remainingSeconds = (seconds % SECONDS_IN_MINUTE)
		.toString()
		.padStart(2, '0');

	return `${Math.floor(seconds / SECONDS_IN_MINUTE)}:${remainingSeconds}`;
}

function getTimeRemaining(
	user: string,
	{ status, timeRemaining }: TimeRemainingType,
	userBids: number,
	lastBidder?: string,
) {
	if (status === 'upcoming') {
		return (
			<span>
				<b>Bidding starts in {formatTimeRemaining(timeRemaining)}</b>
			</span>
		);
	}

	if (status === 'live') {
		if (user === lastBidder) {
			return (
				<span>
					<b>You will win in {formatTimeRemaining(timeRemaining)}</b>{' '}
					if nobody else bids.
				</span>
			);
		}

		return (
			<span>
				<b>Ending in {formatTimeRemaining(timeRemaining)}</b> if nobody
				else bids.
			</span>
		);
	}

	if (user === lastBidder) {
		return (
			<span>
				<b>Auction has closed.</b> Congratulations, you won!
			</span>
		);
	}

	if (userBids > 0) {
		return (
			<span>
				<b>Auction has closed.</b> Sorry, you were out-bid.
			</span>
		);
	}

	return (
		<span>
			<b>Auction has closed.</b>
		</span>
	);
}

function getCountdownTime(
	startTimeString: Date,
	endTimeString: Date,
): TimeRemainingType {
	const startTime = startTimeString.getTime();
	const endTime = endTimeString.getTime();
	const now = new Date().getTime();

	if (now < startTime) {
		return { status: 'upcoming', timeRemaining: startTime - now };
	}

	if (now < endTime) {
		return { status: 'live', timeRemaining: endTime - now };
	}

	return { status: 'closed', timeRemaining: 0 };
}

export function CountdownTimer() {
	const { startTime, endTime, lastBidder, setAuctionStatus, auctionStatus } =
		useAuction();

	const [timeRemaining, setTimeRemaining] = useState<TimeRemainingType>(
		getCountdownTime(startTime, endTime),
	);

	useEffect(() => {
		const interval = setInterval(() => {
			const newRemainingTime = getCountdownTime(startTime, endTime);

			if (auctionStatus !== newRemainingTime.status) {
				setAuctionStatus(newRemainingTime.status);
			}

			setTimeRemaining(newRemainingTime);
		}, 1000);

		return () => clearInterval(interval);
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [startTime, endTime]);

	return (
		<div className="flex items-center gap-3 px-4">
			<ClockIcon />

			{getTimeRemaining(
				DEMO_DATA.userId,
				timeRemaining,
				DEMO_DATA.bids,
				lastBidder,
			)}
		</div>
	);
}
