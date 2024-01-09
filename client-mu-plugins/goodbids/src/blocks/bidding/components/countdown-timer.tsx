import React, { useEffect, useState } from 'react';
import { ClockIcon } from './clock-icon';
import { DEMO_DATA } from '../utils/demo-data';
import { useAuction } from '../utils/auction-store';

type TimeStatus = 'not-started' | 'in-progress' | 'ended';

type TimeRemainingType = {
	status: TimeStatus;
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
	if (status === 'not-started') {
		return (
			<span>
				<b>Bidding starts in {formatTimeRemaining(timeRemaining)}</b>
			</span>
		);
	}

	if (status === 'in-progress') {
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
		return { status: 'not-started', timeRemaining: startTime - now };
	}

	if (now < endTime) {
		return { status: 'in-progress', timeRemaining: endTime - now };
	}

	return { status: 'ended', timeRemaining: 0 };
}

export function CountdownTimer() {
	const { startTime, endTime, lastBidder, setAuctionStatus } = useAuction();
	const startingTimeRemaining = getCountdownTime(startTime, endTime);

	const [timeRemaining, setTimeRemaining] = useState<TimeRemainingType>(
		startingTimeRemaining,
	);

	useEffect(() => {
		const interval = setInterval(() => {
			const newRemainingTime = getCountdownTime(startTime, endTime);

			if (timeRemaining.status !== newRemainingTime.status) {
				setAuctionStatus(newRemainingTime.status);
			}

			setTimeRemaining(newRemainingTime);
		}, 1000);

		return () => clearInterval(interval);
	}, [endTime, setAuctionStatus, startTime, timeRemaining.status]);

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
