import { AnimatePresence, motion } from 'framer-motion';
import { useBiddingState } from '../store';
import { ClockIcon } from './icons/clock-icon';

export function SocketError() {
	const { hasSocketError, auctionStatus } = useBiddingState();

	return (
		<AnimatePresence>
			{hasSocketError && auctionStatus === 'live' && (
				<motion.div
					initial={{ opacity: 0 }}
					animate={{ opacity: 1 }}
					exit={{ opacity: 0 }}
					transition={{ duration: 0.2 }}
					className="flex items-start gap-4 rounded bg-gb-green-100 p-4 text-warning-text"
				>
					<div className="h-6 w-6">
						<ClockIcon />
					</div>

					<div className="flex flex-col gap-3" role="alert">
						<p className="m-0">
							<b>This auction is updating every 30 seconds</b>
						</p>
					</div>
				</motion.div>
			)}
		</AnimatePresence>
	);
}
