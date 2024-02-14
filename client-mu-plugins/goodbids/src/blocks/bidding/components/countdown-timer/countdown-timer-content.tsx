import { motion } from 'framer-motion';
import { useBiddingState } from '../../store';
import { formatTimeRemaining } from './format-time-remaining';
import { AuctionStatus } from '../../store/types';
import { fadeAnimation } from '../../utils/animations';

const upcomingStatuses: AuctionStatus[] = ['upcoming', 'starting'];

const closingStatuses: AuctionStatus[] = ['closing', 'preclosing'];

export function CountdownTimerContent() {
	const { auctionStatus, isLastBidder, userTotalBids } = useBiddingState();

	return (
		<>
			{upcomingStatuses.includes(auctionStatus) && (
				<UpcomingAndStarting />
			)}

			{auctionStatus === 'prelive' && <Prelive />}

			{auctionStatus === 'live' && isLastBidder && <LiveAndLastBidder />}

			{auctionStatus === 'live' && !isLastBidder && <Live />}

			{closingStatuses.includes(auctionStatus) && (
				<ClosingAndPreclosing />
			)}

			{auctionStatus === 'closed' && isLastBidder && (
				<ClosedAndLastBidder />
			)}

			{auctionStatus === 'closed' && userTotalBids > 0 && (
				<ClosedAndUserPlacedBids />
			)}

			{auctionStatus === 'closed' && <Closed />}
		</>
	);
}

type ContentWrapperProps = {
	children: React.ReactNode;
};

function ContentWrapper({ children }: ContentWrapperProps) {
	return (
		<motion.span {...fadeAnimation} role="timer" aria-live="polite">
			{children}
		</motion.span>
	);
}

function UpcomingAndStarting() {
	const { timeRemainingMs } = useBiddingState();

	return (
		<ContentWrapper>
			<b>Bidding starts in {formatTimeRemaining(timeRemainingMs)}</b>
		</ContentWrapper>
	);
}

function Prelive() {
	return (
		<ContentWrapper>
			<b>Auction starting.</b> Please stand by!
		</ContentWrapper>
	);
}

function LiveAndLastBidder() {
	const { timeRemainingMs } = useBiddingState();

	return (
		<ContentWrapper>
			<b>You will win in {formatTimeRemaining(timeRemainingMs)}</b> if
			nobody else bids.
		</ContentWrapper>
	);
}

function Live() {
	const { timeRemainingMs } = useBiddingState();

	return (
		<ContentWrapper>
			<b>Ending in {formatTimeRemaining(timeRemainingMs)}</b> if nobody
			else bids.
		</ContentWrapper>
	);
}

function ClosingAndPreclosing() {
	return (
		<ContentWrapper>
			<b>Auction closing.</b> Please stand by while we check for any last
			second bids!
		</ContentWrapper>
	);
}

function ClosedAndLastBidder() {
	return (
		<ContentWrapper>
			<b>Auction has closed.</b> Congratulations, you won!
		</ContentWrapper>
	);
}

function ClosedAndUserPlacedBids() {
	return (
		<ContentWrapper>
			<b>Auction has closed.</b> Sorry, you were out-bid.
		</ContentWrapper>
	);
}

function Closed() {
	return (
		<ContentWrapper>
			<b>Auction has closed.</b>
		</ContentWrapper>
	);
}
