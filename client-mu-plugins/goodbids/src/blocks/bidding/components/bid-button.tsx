import React from 'react';
import clsx from 'clsx';
import { DEMO_DATA } from '../utils/demo-data';
import { useAuction } from '../utils/auction-store';
import { attributes } from '../utils/get-data-attributes';

export function BidButton() {
	const { lastBidder, auctionStatus, currentBid } = useAuction();

	const disabled =
		lastBidder !== DEMO_DATA.userId && auctionStatus === 'ended';

	const classes = clsx(
		'bg-base rounded text-white py-2 w-full block text-center no-underline text-lg',
		{
			'pointer-events-none cursor-not-allowed': disabled,
			'pointer-events-auto': !disabled,
		},
	);

	if (auctionStatus === 'not-started') {
		return null;
	}

	if (auctionStatus === 'ended') {
		if (lastBidder === DEMO_DATA.userId) {
			return (
				<a href={attributes.rewardUrl} className={classes}>
					Claim Your Reward
				</a>
			);
		}

		return null;
	}

	return (
		<a href={attributes.bidUrl} className={classes}>
			GOODBID ${currentBid} Now
		</a>
	);
}
