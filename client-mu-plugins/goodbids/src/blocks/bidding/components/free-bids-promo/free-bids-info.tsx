import { AnimatePresence, motion } from 'framer-motion';
import { useBiddingState } from '../../store';
import { InfoIcon } from '../info-icon';
import { Skeleton } from '../skeleton';

export function FreeBidsInfo() {
	const { auctionStatus } = useBiddingState();

	return (
		<div className="relative">
			<Skeleton visible={auctionStatus === 'initializing'} />
			<AnimatePresence>
				{auctionStatus !== 'initializing' && (
					<motion.div
						initial={{ opacity: 0 }}
						animate={{ opacity: 1 }}
						exit={{ opacity: 0 }}
						transition={{ duration: 0.2 }}
						className="flex items-center gap-2"
					>
						<InfoIcon width={16} />
						<a href="/earn-free-bids" className="text-sm block">
							Learn more
						</a>
					</motion.div>
				)}
			</AnimatePresence>
		</div>
	);
}
