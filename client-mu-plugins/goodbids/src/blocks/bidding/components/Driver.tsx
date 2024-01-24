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
import { useUser } from '../utils/user-store';

type DriverProps = {
	auctionId: number;
};

export function Driver({ auctionId }: DriverProps) {
	const [queryClient] = useState(() => client);
	const { auctionFetchStatus, usePolling } = useAuction();
	const { userFetchStatus } = useUser();

	const loading =
		auctionFetchStatus === 'loading' || userFetchStatus === 'loading';
	const error = auctionFetchStatus === 'error' || userFetchStatus === 'error';

	return (
		<QueryClientProvider client={queryClient}>
			<div className="w-full text-lg flex flex-col gap-4">
				{error ? (
					<div>Error loading auction</div>
				) : (
					<>
						<Fetcher auctionId={auctionId} />

						{loading ? (
							<div>Loading...</div>
						) : (
							<>
								{!usePolling && (
									<Socket auctionId={auctionId} />
								)}
								<Metrics />
								<CountdownTimer />
								<BidButton />
								<FreeBidButton />
								<Participation />
								<EarnFreeBids />
							</>
						)}
					</>
				)}
			</div>
		</QueryClientProvider>
	);
}
