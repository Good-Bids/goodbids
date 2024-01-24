import { useBiddingState } from '../store';

function ParticipationContent() {
	const { auctionStatus, isUserLoggedIn, userTotalBids, userTotalDonated } =
		useBiddingState();

	if (auctionStatus === 'upcoming' || auctionStatus === 'starting') {
		if (isUserLoggedIn) {
			return (
				<p className="m-0 text-center">
					Watch this auction to be notified when bidding starts.
				</p>
			);
		}

		return (
			<p className="m-0 text-center">
				<a href="">Join GoodBids</a> to bid when the auction starts.
			</p>
		);
	}

	if (auctionStatus === 'closed' || auctionStatus === 'closing') {
		if (userTotalBids > 0) {
			return (
				<p className="m-0 text-center">
					You placed <b>{userTotalBids}</b> for a total donation of{' '}
					<b>${userTotalDonated}</b>.
				</p>
			);
		}

		return <p>You did not bid in this auction.</p>;
	}

	if (isUserLoggedIn) {
		if (userTotalBids > 0) {
			return (
				<p className="m-0 text-center">
					You placed <b>{userTotalBids}</b> for a total donation of{' '}
					<b>${userTotalDonated}</b>.
				</p>
			);
		}

		return (
			<p className="m-0 text-center">
				You haven't bid in this auction yet.
			</p>
		);
	}

	return (
		<p className="m-0 text-center">
			<a href="">Join GoodBids</a> to bid in this auction.
		</p>
	);
}

export function Participation() {
	return (
		<div className="flex flex-col items-center gap-2">
			<ParticipationContent />
			<p className="m-0 text-center">Every GOODBID is a donation.</p>
		</div>
	);
}
