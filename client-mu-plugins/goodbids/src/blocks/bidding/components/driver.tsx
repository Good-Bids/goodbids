import React from 'react';
import { getInitialState } from '../utils/get-initial-state';
import { BidButton } from './bid-button';
import { CountdownTimer } from './countdown-timer';
import { Metrics } from './metrics';

export function Driver() {
	const initialState = getInitialState();

	return (
		<div className="w-full text-lg flex flex-col gap-4">
			<Metrics
				blocks={[
					{ type: 'bids', value: initialState.bids },
					{ type: 'raised', value: initialState.raised },
					{ type: 'last-bid', value: initialState.lastBid },
				]}
			/>
			<CountdownTimer />
			<BidButton />
		</div>
	);
}
