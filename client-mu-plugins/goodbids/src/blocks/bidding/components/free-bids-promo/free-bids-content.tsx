import { AnimatePresence, motion } from 'framer-motion';
import { Skeleton } from '../skeleton';
import { useBiddingState } from '../../store';
import { liveStatuses, upcomingStatuses } from '../../utils/statuses';

export function FreeBidsContent() {
	const { auctionStatus, userId, freeBidsAvailable } = useBiddingState();

	return (
		<div className="relative">
			<Skeleton visible={auctionStatus === 'initializing'} size="lg" />
			<AnimatePresence>
				{upcomingStatuses.includes(auctionStatus) && userId && (
					<UpcomingAndUser />
				)}

				{liveStatuses.includes(auctionStatus) &&
					userId &&
					freeBidsAvailable && <LiveAndUserAndFreeBids />}

				{liveStatuses.includes(auctionStatus) &&
					userId &&
					!freeBidsAvailable && <LiveAndUser />}

				{liveStatuses.includes(auctionStatus) && !userId && <Live />}
			</AnimatePresence>
		</div>
	);
}

function UpcomingAndUser() {
	return (
		<motion.p
			key="fbp-upcoming-user"
			initial={{ opacity: 0 }}
			animate={{ opacity: 1 }}
			exit={{ opacity: 0 }}
			transition={{ duration: 0.2 }}
			className="m-0"
		>
			Place one of the first five <b>paid bids</b> in this auction or{' '}
			<span className="font-bold underline">
				share GOODBIDS with a friend
			</span>{' '}
			to <b>earn a free bid</b>!
		</motion.p>
	);
}

function LiveAndUserAndFreeBids() {
	const { currentBid } = useBiddingState();

	return (
		<motion.p
			key="fbp-live-user-free-bids"
			initial={{ opacity: 0 }}
			animate={{ opacity: 1 }}
			exit={{ opacity: 0 }}
			transition={{ duration: 0.2 }}
			className="m-0"
		>
			GOODBID <b>${currentBid}</b> now or{' '}
			<span className="font-bold underline">
				share GOODBIDS with a friend
			</span>{' '}
			to <b>earn a free bid</b>!
		</motion.p>
	);
}

function LiveAndUser() {
	return (
		<motion.p
			key="fbp-live-user"
			initial={{ opacity: 0 }}
			animate={{ opacity: 1 }}
			exit={{ opacity: 0 }}
			transition={{ duration: 0.2 }}
			className="m-0"
		>
			<span className="font-bold underline">
				Share GOODBIDS with a friend
			</span>{' '}
			to <b>earn a free bid</b>!
		</motion.p>
	);
}

function Live() {
	return (
		<motion.p
			key="fbp-live-user"
			initial={{ opacity: 0 }}
			animate={{ opacity: 1 }}
			exit={{ opacity: 0 }}
			transition={{ duration: 0.2 }}
			className="m-0"
		>
			GOODBIDS users earn <b>free bids</b> when they place one of the{' '}
			<b>first five paid bids</b> in an auction or{' '}
			<span className="font-bold underline">
				share GOODBIDS with a friend{' '}
			</span>{' '}
			to <b>earn a free bid</b>!
		</motion.p>
	);
}
