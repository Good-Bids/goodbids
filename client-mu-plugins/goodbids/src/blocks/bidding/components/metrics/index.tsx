import { AnimatePresence, motion } from 'framer-motion';
import { useBiddingState } from '../../store';
import { closedStatuses, liveStatuses } from '../../utils/statuses';
import { MetricBlock } from './metric-block';
import { fadeAnimation } from '../../utils/animations';

const renderStatuses = [...liveStatuses, ...closedStatuses];

export function Metrics() {
	const { totalBids, lastBid, totalRaised, auctionStatus } =
		useBiddingState();

	return (
		<AnimatePresence mode="popLayout">
			{renderStatuses.includes(auctionStatus) && (
				<motion.div
					{...fadeAnimation}
					className="grid grid-cols-3 gap-2 -mt-[0.75rem]"
				>
					<MetricBlock type="raised" value={totalRaised} />
					<MetricBlock
						type={
							auctionStatus === 'closed'
								? 'winning-bid'
								: 'last-bid'
						}
						value={lastBid}
					/>
					<MetricBlock type="bids" value={totalBids} />
				</motion.div>
			)}
		</AnimatePresence>
	);
}
