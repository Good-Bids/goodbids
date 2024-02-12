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
import { DataHandler } from './data-handler';

type DriverProps = {
	auctionId: number;
};

export function Driver({ auctionId }: DriverProps) {
	const [queryClient] = useState(() => client);

	return (
		<QueryClientProvider client={queryClient}>
			<motion.div
				layout
				transition={{ duration: 0.2 }}
				className="flex flex-col w-full gap-6 text-md"
			>
				<DataHandler auctionId={auctionId}>
					<LayoutGroup>
						<Metrics />
						<CountdownTimer />
						<BidButton />
						<FreeBidButton />
						<ParticipationNotice />
						<FreeBidsPromo />
					</LayoutGroup>
				</DataHandler>
			</motion.div>
		</QueryClientProvider>
	);
}
