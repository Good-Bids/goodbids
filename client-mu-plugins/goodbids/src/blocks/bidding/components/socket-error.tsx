import { AnimatePresence, motion } from 'framer-motion';
import { useBiddingState } from '../store';
import { WarningIcon } from './icons/warning-icon';

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
					className="flex items-start gap-4 bg-warning-bg text-warning-text rounded p-4"
				>
					<div className="h-6 w-6">
						<WarningIcon />
					</div>

					<div className="flex flex-col gap-3">
						<p className="m-0">
							<b>Live auction updates suspended</b>
						</p>
						<p>
							We're having trouble fetching live updates for this
							auctions. Updates will load every 30 seconds until
							the issue is resolved.
						</p>
					</div>
				</motion.div>
			)}
		</AnimatePresence>
	);
}
