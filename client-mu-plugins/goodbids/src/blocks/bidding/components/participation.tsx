import React from 'react';
import { DEMO_DATA } from '../utils/demo-data';
import { useAuction } from '../utils/auction-store';

function ParticipationContent() {
	const { auctionStatus } = useAuction();

	if (auctionStatus === 'upcoming') {
		if (DEMO_DATA.userId) {
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

	if (auctionStatus === 'closed') {
		if (DEMO_DATA.bids > 0 && DEMO_DATA.amountBid > 0) {
			return (
				<p className="m-0 text-center">
					You placed <b>{DEMO_DATA.bids}</b> for a total donation of{' '}
					<b>${DEMO_DATA.amountBid}</b>.
				</p>
			);
		}

		return <p>You did not bid in this auction.</p>;
	}

	if (DEMO_DATA.userId) {
		if (DEMO_DATA.bids > 0 && DEMO_DATA.amountBid > 0) {
			return (
				<p className="m-0 text-center">
					You placed <b>{DEMO_DATA.bids}</b> for a total donation of{' '}
					<b>${DEMO_DATA.amountBid}</b>.
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
