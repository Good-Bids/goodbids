import { useState } from 'react';
import { BidButton } from './bid-button';
import { CountdownTimer } from './countdown-timer';
import { FreeBidButton } from './free-bid-button';
import { Metrics } from './metrics';
import { Participation } from './participation';
import { Socket } from './socket';
import { client } from '../utils/query-client';
import { QueryClientProvider } from '@tanstack/react-query';
import { Fetcher } from './fetcher';
import { useBiddingState } from '../store';
import { SocketError } from './socket-error';
import { FreeBidsPromo } from './free-bids-promo';
import { motion } from 'framer-motion';

type DriverProps = {
	auctionId: number;
};

export function Driver({ auctionId }: DriverProps) {
	const [queryClient] = useState(() => client);
	const { fetchMode } = useBiddingState();

	return (
		<QueryClientProvider client={queryClient}>
			<motion.div
				layout
				transition={{ duration: 0.2 }}
				className="flex flex-col w-full gap-6 text-md"
			>
				<Metrics />
				<FreeBidsPromo />
				<Fetcher auctionId={auctionId}>
					{fetchMode === 'socket' && <Socket auctionId={auctionId} />}
					<CountdownTimer />
					<BidButton />
					<FreeBidButton />
					<Participation />
					<SocketError />
					<div />
				</Fetcher>
			</motion.div>
		</QueryClientProvider>
	);
}
