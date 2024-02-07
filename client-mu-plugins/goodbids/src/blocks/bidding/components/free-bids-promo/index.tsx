import { motion, AnimatePresence } from 'framer-motion';
import { useBiddingState } from '../../store';
import { nonClosedStatuses } from '../../utils/statuses';
import { FreeBidsHeading } from './free-bids-heading';
import { FreeBidsInfo } from './free-bids-info';
import { FreeBidsContent } from './free-bids-content';

export function FreeBidsPromo() {
	const { auctionStatus } = useBiddingState();

	return (
		<AnimatePresence mode="popLayout">
			{nonClosedStatuses.includes(auctionStatus) && (
				<motion.div
					initial={{ opacity: 1 }}
					exit={{ opacity: 0 }}
					className="flex justify-start flex-col gap-4 bg-contrast-5 rounded p-4"
				>
					<FreeBidsHeading />
					<div className="pl-6 flex flex-col gap-4">
						<FreeBidsContent />
						<FreeBidsInfo />
					</div>
				</motion.div>
			)}
		</AnimatePresence>
	);
}
