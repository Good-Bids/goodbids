import { AnimatePresence } from 'framer-motion';
import { useBiddingState } from '../../store';
import { Skeleton } from '../skeleton';
import { ParticipationNoticeContent } from './participation-notice-content';

export function ParticipationNotice() {
	const { auctionStatus } = useBiddingState();

	return (
		<div className="relative">
			<Skeleton visible={auctionStatus === 'initializing'} />
			<AnimatePresence>
				{auctionStatus !== 'initializing' && (
					<ParticipationNoticeContent />
				)}
			</AnimatePresence>
		</div>
	);
}
