import React from 'react';
import clsx from 'clsx';
import { DEMO_DATA } from '../utils/demo-data';
import { useAuction } from '../utils/auction-store';
import { attributes } from '../utils/get-data-attributes';

export function FreeBidButton() {
	const { lastBidder, freeBidsAvailable, auctionStatus } = useAuction();

	const disabled = lastBidder === DEMO_DATA.userId && !DEMO_DATA.freeBids;

	const classes = clsx(
		'bg-base rounded py-2 w-full block text-center no-underline text-lg',
		{
			'pointer-events-none cursor-not-allowed': disabled,
			'pointer-events-auto': !disabled,
		},
	);

	if (freeBidsAvailable && auctionStatus === 'in-progress') {
		return (
			<a href={attributes.freeBidUrl} className={classes}>
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
