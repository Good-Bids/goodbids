import { useBiddingState } from '../../store';
import { Skeleton } from '../skeleton';
import { WaveIcon } from '../wave-icon';
import { AnimatePresence, motion } from 'framer-motion';

export function FreeBidsHeading() {
	const { auctionStatus } = useBiddingState();

	return (
		<div className="relative">
			<Skeleton visible={auctionStatus === 'initializing'} />
			<AnimatePresence mode="popLayout">
				{auctionStatus !== 'initializing' && (
					<motion.div
						className="flex items-center gap-3"
						initial={{ opacity: 0 }}
						animate={{ opacity: 1 }}
						exit={{ opacity: 0 }}
						transition={{ duration: 0.2 }}
					>
						<div className="h-6 w-6">
							<WaveIcon />
						</div>
						<p className="m-0">
							<b>Earn free bids:</b>
						</p>
					</motion.div>
				)}
			</AnimatePresence>
		</div>
	);
}
