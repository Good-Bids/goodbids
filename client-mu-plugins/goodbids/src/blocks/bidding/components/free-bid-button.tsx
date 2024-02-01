import clsx from 'clsx';
import { useBiddingState } from '../store';
import { getIsLastBidder } from '../utils/get-is-last-bidder';

export function FreeBidButton() {
	const {
		userFreeBids,
		auctionStatus,
		bidUrl,
		freeBidsAllowed,
		userId,
		lastBidder,
	} = useBiddingState();

	const disabled = getIsLastBidder(userId, lastBidder) || userFreeBids < 1;

	const classes = clsx('btn-fill-secondary text-center', {
		'pointer-events-none cursor-not-allowed !text-contrast-4': disabled,
		'pointer-events-auto': !disabled,
	});

	if (freeBidsAllowed && auctionStatus === 'live') {
		return (
			<a
				href={`${bidUrl}&use-free-bid=1`}
				className={classes}
				aria-disabled={disabled}
			>
				{`Place free bid ${
					userId ? `(${userFreeBids} available)` : ''
				}`}
			</a>
		);
	}

	return null;
}
