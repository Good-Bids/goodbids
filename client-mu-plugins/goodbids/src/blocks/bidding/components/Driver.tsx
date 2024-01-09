import React from 'react';
import { BidButton } from './bid-button';
import { CountdownTimer } from './countdown-timer';
import { EarnFreeBids } from './earn-free-bids';
import { FreeBidButton } from './free-bid-button';
import { Metrics } from './metrics';
import { Participation } from './participation';
import { Socket } from './socket';

export function Driver() {
	return (
		<div className="w-full text-lg flex flex-col gap-4">
			<Socket />
			<Metrics />
			<CountdownTimer />
			<BidButton />
			<FreeBidButton />
			<Participation />
			<EarnFreeBids />
		</div>
	);
}
