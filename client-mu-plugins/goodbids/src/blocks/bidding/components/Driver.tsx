import { useState } from 'react';
import { BidButton } from './bid-button';
import { CountdownTimer } from './countdown-timer';
import { FreeBidButton } from './free-bid-button';
import { Metrics } from './metrics';
import { client } from '../utils/query-client';
import { QueryClientProvider } from '@tanstack/react-query';
import { FreeBidsPromo } from './free-bids-promo';
import { LayoutGroup, motion } from 'framer-motion';
import { ParticipationNotice } from './participation-notice';
import { Fetcher } from './fetcher';
import { Socket } from './socket';
import { SocketError } from './socket-error';
import { FirstTimeDialog } from './first-time-dialog';

type DriverProps = {
	auctionId: number;
};

export function Driver({ auctionId }: DriverProps) {
	const [queryClient] = useState(() => client);

	return (
		<QueryClientProvider client={queryClient}>
			<Fetcher auctionId={auctionId}>
				<Socket auctionId={auctionId} />
				<motion.div
					layout
					transition={{ duration: 0.2 }}
					className="flex w-full flex-col gap-6 text-md"
				>
					<LayoutGroup>
						<Metrics />
						<CountdownTimer />
						<BidButton />
						<FreeBidButton />
						<ParticipationNotice />
						<FreeBidsPromo />
						<SocketError />
						<FirstTimeDialog />
					</LayoutGroup>
				</motion.div>
			</Fetcher>
		</QueryClientProvider>
	);
}
