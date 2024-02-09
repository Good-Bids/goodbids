import { AnimatePresence, LayoutGroup, motion } from 'framer-motion';
import { useBiddingState } from '../../store';
import { Skeleton } from '../skeleton';
import { CountdownTimerContent } from './countdown-timer-content';
import { CountdownTimerIcons } from './countdown-timer-icons';

export function CountdownTimer() {
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
						className="flex items-center gap-3 px-4"
					>
						<LayoutGroup>
							<CountdownTimerIcons />
							<CountdownTimerContent />
						</LayoutGroup>
					</motion.div>
				)}
			</AnimatePresence>
		</div>
	);
}
