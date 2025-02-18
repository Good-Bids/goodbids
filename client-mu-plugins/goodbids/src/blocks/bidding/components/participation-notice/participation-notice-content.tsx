import { motion } from 'framer-motion';
import { useBiddingState } from '../../store';
import { upcomingStatuses } from '../../utils/statuses';
import { fadeAnimation } from '../../utils/animations';

export function ParticipationNoticeContent() {
	const { auctionStatus, userTotalBids, userId } = useBiddingState();

	return (
		<motion.div
			{...fadeAnimation}
			className="flex flex-col items-center gap-2"
			role="region"
			aria-live="polite"
			aria-atomic="true"
		>
			{upcomingStatuses.includes(auctionStatus) && userId && (
				<UpcomingToPreliveWithUser />
			)}

			{upcomingStatuses.includes(auctionStatus) && !userId && (
				<UpcomingToPreliveWithoutUser />
			)}

			{auctionStatus === 'live' && userTotalBids > 0 && (
				<LiveToClosingWithBids />
			)}

			{auctionStatus === 'live' && userTotalBids === 0 && (
				<LiveToClosingWithoutBids />
			)}

			{auctionStatus === 'live' && !userId && (
				<LiveToClosingWithoutUser />
			)}

			{auctionStatus === 'closed' && userTotalBids > 0 && (
				<ClosedWithBids />
			)}

			{auctionStatus === 'closed' && userTotalBids === 0 && (
				<ClosedWithoutBids />
			)}

			<p className="m-0 text-center">Every GOODBID is a donation.</p>
		</motion.div>
	);
}

type ContentWrapperProps = {
	children: React.ReactNode;
};

function ContentWrapper({ children }: ContentWrapperProps) {
	return (
		<motion.p {...fadeAnimation} className="m-0 text-center">
			{children}
		</motion.p>
	);
}

function UpcomingToPreliveWithUser() {
	return (
		<ContentWrapper>
			Watch this auction to be notified when bidding starts.
		</ContentWrapper>
	);
}

function UpcomingToPreliveWithoutUser() {
	const { accountUrl } = useBiddingState();

	return (
		<ContentWrapper>
			<a href={accountUrl}>Join GoodBids</a> to bid when the auction
			starts.
		</ContentWrapper>
	);
}

function LiveToClosingWithBids() {
	const { userTotalBids, userTotalDonated } = useBiddingState();

	return (
		<ContentWrapper>
			You've placed <b>{userTotalBids} bids</b> for a total donation of{' '}
			<b>${userTotalDonated}</b>.
		</ContentWrapper>
	);
}

function LiveToClosingWithoutBids() {
	return (
		<ContentWrapper>You haven't bid in this auction yet.</ContentWrapper>
	);
}

function LiveToClosingWithoutUser() {
	const { accountUrl } = useBiddingState();

	return (
		<ContentWrapper>
			<a href={accountUrl}>Join GoodBids</a> to bid in this auction.
		</ContentWrapper>
	);
}

function ClosedWithBids() {
	const { userTotalBids, userTotalDonated } = useBiddingState();

	return (
		<ContentWrapper>
			You placed <b>{userTotalBids} bids</b> for a total donation of{' '}
			<b>${userTotalDonated}</b>.
		</ContentWrapper>
	);
}

function ClosedWithoutBids() {
	return <ContentWrapper>You did not bid in this auction.</ContentWrapper>;
}
