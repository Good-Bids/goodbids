import React from 'react';
import clsx from 'clsx';
import { initialState } from '../utils/get-initial-state';
import { DEMO_DATA } from '../utils/demo-data';

export function FreeBidButton() {
	const disabled =
		initialState.lastBidder === DEMO_DATA.userId && !DEMO_DATA.freeBids;

	const classes = clsx(
		'bg-base rounded py-2 w-full block text-center no-underline text-lg',
		{
			'pointer-events-none cursor-not-allowed': disabled,
			'pointer-events-auto': !disabled,
		},
	);

	if (
		new Date(initialState.startTime) > new Date() ||
		new Date(initialState.endTime) < new Date()
	) {
		return null;
	}

	if (initialState.freeBids) {
		return (
			<a href={initialState.freeBidUrl} className={classes}>
				{`Place free bid ${
					DEMO_DATA.freeBids
						? `(${DEMO_DATA.freeBids} available)`
						: ''
				}`}
			</a>
		);
	}

	return null;
}
