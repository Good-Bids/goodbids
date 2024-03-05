import { AnimatePresence, motion } from 'framer-motion';
import { Skeleton } from '../skeleton';
import { useBiddingState } from '../../store';
import { liveStatuses, upcomingStatuses } from '../../utils/statuses';
import { fadeAnimation } from '../../utils/animations';

export function FreeBidsContent() {
	const { auctionStatus, userId, freeBidsAvailable, isLastBidder } =
		useBiddingState();

	return (
		<div className="relative">
			<Skeleton visible={auctionStatus === 'initializing'} size="lg" />
			<AnimatePresence>
				{upcomingStatuses.includes(auctionStatus) && userId && (
					<UpcomingAndUser />
				)}

				{liveStatuses.includes(auctionStatus) &&
					userId &&
					freeBidsAvailable &&
					!isLastBidder && <LiveAndUserAndFreeBids />}

				{liveStatuses.includes(auctionStatus) &&
					userId &&
					(!freeBidsAvailable || isLastBidder) && <LiveAndUser />}

				{auctionStatus !== 'initializing' && !userId && <NoUser />}
			</AnimatePresence>
		</div>
	);
}

type ContentWrapperProps = {
	children: React.ReactNode;
};

function ContentWrapper({ children }: ContentWrapperProps) {
	return (
		<motion.p {...fadeAnimation} className="m-0">
			{children}
		</motion.p>
	);
}

function UpcomingAndUser() {
	return (
		<ContentWrapper>
			Place one of the first five <b>paid bids</b> in this auction or{' '}
			<span className="font-bold underline">
				share GOODBIDS with a friend
			</span>{' '}
			to <b>earn a free bid</b>!
		</ContentWrapper>
	);
}

// Displayed if:
// - the auction is live
// - the user is logged in
// - the auction has free bids available
// - the user is not the last bidder
function LiveAndUserAndFreeBids() {
	const { currentBid } = useBiddingState();

	return (
		<ContentWrapper>
			GOODBID <b>${currentBid}</b> now or{' '}
			<span className="font-bold underline">
				share GOODBIDS with a friend
			</span>{' '}
			to <b>earn a free bid</b>!
		</ContentWrapper>
	);
}

// Displayed if:
// - the auction is live
// - the user is logged in
// and if:
// - the auction has no free bids available
// or
// - the user is the last bidder
function LiveAndUser() {
	return (
		<ContentWrapper>
			<span className="font-bold underline">
				Share GOODBIDS with a friend
			</span>{' '}
			to <b>earn a free bid</b>!
		</ContentWrapper>
	);
}

function NoUser() {
	return (
		<ContentWrapper>
			GOODBIDS users earn <b>free bids</b> when they place one of the{' '}
			<b>first five paid bids</b> in an auction or{' '}
			<span className="font-bold underline">
				share GOODBIDS with a friend{' '}
			</span>{' '}
			to <b>earn a free bid</b>!
		</ContentWrapper>
	);
}
