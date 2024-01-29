import clsx from 'clsx';
import { useBiddingState } from '../store';

export function BidButton() {
	const { isLastBidder, auctionStatus, currentBid, rewardUrl, bidUrl } =
		useBiddingState();

	const disabled =
		isLastBidder ||
		auctionStatus === 'closed' ||
		auctionStatus === 'closing';

	const classes = clsx('btn-fill text-center', {
		'pointer-events-none cursor-not-allowed': disabled,
		'pointer-events-auto': !disabled,
	});

	if (auctionStatus === 'upcoming' || auctionStatus === 'starting') {
		return null;
	}

	if (auctionStatus === 'closed') {
		if (isLastBidder) {
			return (
				<a href={rewardUrl} className={classes}>
					Claim Your Reward
				</a>
			);
		}

		return null;
	}

	return (
		<a href={bidUrl} className={classes}>
			GOODBID ${currentBid} Now
		</a>
	);
}
