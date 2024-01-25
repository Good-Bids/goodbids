import { useEffect, useState } from 'react';
import { ClockIcon } from './clock-icon';
import { AuctionStatus } from '../store/types';
import { useBiddingState } from '../store';
import { client } from '../utils/query-client';
import clsx from 'clsx';

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
	{ status, timeRemaining }: TimeRemainingType,
	userTotalBids: number,
	isLastBidder: boolean,
) {
	if (status === 'upcoming' || status === 'starting') {
		return (
			<span>
				<b>Bidding starts in {formatTimeRemaining(timeRemaining)}</b>
			</span>
		);
	}

	if (status === 'live') {
		if (isLastBidder) {
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

	if (status === 'closing' || status === 'closed') {
		if (isLastBidder) {
			return (
				<span>
					<b>Auction has closed.</b> Congratulations, you won!
				</span>
			);
		}

		if (userTotalBids > 0) {
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

	return (
		<span>
			<b>Discovering relativity</b>
		</span>
	);
}

const startTimeBuffer = 1000 * 60;

function getCountdownTime(
	startTimeDate: Date,
	endTimeDate: Date,
	status: AuctionStatus,
): TimeRemainingType {
	if (status === 'initializing') {
		return { status: 'initializing', timeRemaining: 0 };
	}

	const startTime = startTimeDate.getTime();
	const bufferedStartTime = startTime - startTimeBuffer;
	const endTime = endTimeDate.getTime();
	const now = new Date().getTime();

	if (now >= bufferedStartTime && now < startTime) {
		return { status: 'starting', timeRemaining: startTime - now };
	}

	if (now < startTime) {
		return { status: 'upcoming', timeRemaining: startTime - now };
	}

	if (now < endTime) {
		return { status: 'live', timeRemaining: endTime - now };
	}

	return { status: 'closing', timeRemaining: 0 };
}

export function CountdownTimer() {
	const {
		startTime,
		endTime,
		isLastBidder,
		auctionStatus,
		userTotalBids,
		setAuctionStatus,
		setSocketMode,
	} = useBiddingState();

	const [timeRemaining, setTimeRemaining] = useState<TimeRemainingType>(
		getCountdownTime(startTime, endTime, auctionStatus),
	);

	useEffect(() => {
		const interval = setInterval(() => {
			const newRemainingTime = getCountdownTime(
				startTime,
				endTime,
				auctionStatus,
			);

			if (auctionStatus !== newRemainingTime.status) {
				if (newRemainingTime.status === 'starting') {
					setAuctionStatus(newRemainingTime.status);
					client.invalidateQueries({
						queryKey: ['auction'],
					});
				} else if (newRemainingTime.status === 'live') {
					setSocketMode();
				} else if (newRemainingTime.status === 'closing') {
					// TODO: https://github.com/Good-Bids/goodbids/issues/241
					// setAuctionStatus(newRemainingTime.status);
				}
			}

			setTimeRemaining(newRemainingTime);
		}, 1000);

		return () => clearInterval(interval);
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [startTime, endTime, auctionStatus]);

	const placeholderClasses = clsx(
		'absolute top-1/2 -translate-y-1/2 transition-all ',
		{
			'opacity-0': timeRemaining.status !== 'initializing',
			'opacity-100': timeRemaining.status === 'initializing',
		},
	);

	const timeRemainingClasses = clsx(
		'absolute top-1/2 -translate-y-1/2 transition-all ',
		{
			'opacity-100': timeRemaining.status !== 'initializing',
			'opacity-0': timeRemaining.status === 'initializing',
		},
	);

	return (
		<div className="flex items-center gap-3 px-4 h-12">
			<ClockIcon />

			<div className="relative w-full">
				<span className={placeholderClasses}>
					<b>Discovering relativity</b>
				</span>

				<div className={timeRemainingClasses}>
					{getTimeRemaining(
						timeRemaining,
						userTotalBids,
						isLastBidder,
					)}
				</div>
			</div>
		</div>
	);
}
