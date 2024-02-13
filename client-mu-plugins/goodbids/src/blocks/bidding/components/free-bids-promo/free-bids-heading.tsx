import { useBiddingState } from '../../store';
import { Skeleton } from '../skeleton';
import { WaveIcon } from '../icons/wave-icon';
import { AnimatePresence, motion } from 'framer-motion';
import { fadeAnimation } from '../../utils/animations';

export function FreeBidsHeading() {
	const { auctionStatus } = useBiddingState();

	return (
		<div className="relative">
			<Skeleton visible={auctionStatus === 'initializing'} />
			<AnimatePresence mode="popLayout">
				{auctionStatus !== 'initializing' && (
					<motion.div
						{...fadeAnimation}
						className="flex items-center gap-3"
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
