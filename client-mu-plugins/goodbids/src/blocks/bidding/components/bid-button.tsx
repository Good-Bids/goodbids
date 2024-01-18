import clsx from 'clsx';
import { useAuction } from '../utils/auction-store';
import { DEMO_DATA } from '../utils/demo-data';

export function BidButton() {
	const { lastBidder, auctionStatus, currentBid } = useAuction();

	const disabled =
		lastBidder !== DEMO_DATA.userId && auctionStatus === 'closed';

	const classes = clsx(
		'bg-base rounded text-white py-2 w-full block text-center no-underline text-lg',
		{
			'pointer-events-none cursor-not-allowed': disabled,
			'pointer-events-auto': !disabled,
		},
	);

	if (auctionStatus === 'upcoming') {
		return null;
	}

	if (auctionStatus === 'closed') {
		if (lastBidder === DEMO_DATA.userId) {
			return (
				<a href={DEMO_DATA.rewardUrl} className={classes}>
					Claim Your Reward
				</a>
			);
		}

		return null;
	}

	return (
		<a href={DEMO_DATA.bidUrl} className={classes}>
			GOODBID ${currentBid} Now
		</a>
	);
}
