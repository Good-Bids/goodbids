import clsx from 'clsx';
import { useBiddingState } from '../store';

export function FreeBidButton() {
	const {
		isLastBidder,
		userFreeBids,
		auctionStatus,
		bidUrl,
		freeBidsAllowed,
		isUserLoggedIn,
	} = useBiddingState();

	const disabled = isLastBidder || userFreeBids < 1 || !isUserLoggedIn;

	const classes = clsx('btn-fill-secondary text-center', {
		'pointer-events-none cursor-not-allowed bg-base-3': disabled,
		'pointer-events-auto': !disabled,
	});

	if (freeBidsAllowed && auctionStatus === 'live') {
		return (
			<a href={bidUrl} className={classes}>
				{`Place free bid ${
					isUserLoggedIn ? `(${userFreeBids} available)` : ''
				}`}
			</a>
		);
	}

	return null;
}
