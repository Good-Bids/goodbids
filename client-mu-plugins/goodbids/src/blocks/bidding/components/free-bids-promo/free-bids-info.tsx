import { AnimatePresence, motion } from 'framer-motion';
import { useBiddingState } from '../../store';
import { InfoIcon } from '../icons/info-icon';
import { Skeleton } from '../skeleton';
import { fadeAnimation } from '../../utils/animations';

export function FreeBidsInfo() {
	const { auctionStatus } = useBiddingState();

	return (
		<div className="relative">
			<Skeleton visible={auctionStatus === 'initializing'} />
			<AnimatePresence>
				{auctionStatus !== 'initializing' && (
					<motion.div
						{...fadeAnimation}
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
