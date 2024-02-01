import clsx from 'clsx';
import { useBiddingState } from '../store';
import { getIsLastBidder } from '../utils/get-is-last-bidder';

export function BidButton() {
	const { userId, lastBidder, auctionStatus, currentBid, rewardUrl, bidUrl } =
		useBiddingState();

	const isLastBidder = getIsLastBidder(userId, lastBidder);

	const disabled =
		isLastBidder ||
		auctionStatus === 'preclosing' ||
		auctionStatus === 'closed' ||
		auctionStatus === 'closing';

	const biddingClasses = clsx('btn-fill text-center', {
		'pointer-events-none cursor-not-allowed !bg-base-3 !text-contrast-4':
			disabled,
		'pointer-events-auto': !disabled,
	});

	const rewardClasses = clsx('btn-fill text-center');

	if (
		auctionStatus === 'upcoming' ||
		auctionStatus === 'starting' ||
		auctionStatus === 'prelive'
	) {
		return null;
	}

	if (auctionStatus === 'closed') {
		if (isLastBidder) {
			return (
				<a href={rewardUrl} className={rewardClasses}>
					Claim Your Reward
				</a>
			);
		}

		return null;
	}

	return (
		<a href={bidUrl} className={biddingClasses}>
			GOODBID ${currentBid} Now
		</a>
	);
}
