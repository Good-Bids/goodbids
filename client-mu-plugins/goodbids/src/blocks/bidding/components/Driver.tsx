import { useState } from 'react';
import { BidButton } from './bid-button';
import { CountdownTimer } from './countdown-timer';
import { EarnFreeBids } from './earn-free-bids';
import { FreeBidButton } from './free-bid-button';
import { Metrics } from './metrics';
import { Participation } from './participation';
import { Socket } from './socket';
import { client } from '../utils/query-client';
import { QueryClientProvider } from '@tanstack/react-query';
import { Fetcher } from './fetcher';
import { useAuction } from '../utils/auction-store';

export function Driver() {
	const [queryClient] = useState(() => client);
	const { initialFetchCompleted, usePolling } = useAuction();

	return (
		<QueryClientProvider client={queryClient}>
			<div className="w-full text-lg flex flex-col gap-4">
				<Fetcher />

				{initialFetchCompleted && (
					<>
						{!usePolling && <Socket />}
						<Metrics />
						<CountdownTimer />
						<BidButton />
						<FreeBidButton />
						<Participation />
						<EarnFreeBids />
					</>
				)}
			</div>
		</QueryClientProvider>
	);
}
