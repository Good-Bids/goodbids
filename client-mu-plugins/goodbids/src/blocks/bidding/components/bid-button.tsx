import React from 'react';
import clsx from 'clsx';
import { initialState } from '../utils/get-initial-state';

export function BidButton() {
	const disabled =
		initialState.lastBidder === 'fake-user' &&
		new Date(initialState.endTime) > new Date();

	const classes = clsx(
		'bg-base rounded text-white py-2 w-full block text-center no-underline text-lg',
		{
			'pointer-events-none cursor-not-allowed': disabled,
			'pointer-events-auto': !disabled,
		},
	);

	if (new Date(initialState.startTime) > new Date()) {
		return null;
	}

	if (new Date(initialState.endTime) < new Date()) {
		if (initialState.lastBidder === 'fake-user') {
			return (
				<a href={initialState.prizeUrl} className={classes}>
					Claim Your Reward
				</a>
			);
		}

		return null;
	}

	return (
		<a href={initialState.bidUrl} className={classes}>
			GOODBID ${initialState.nextBid} Now
		</a>
	);
}
