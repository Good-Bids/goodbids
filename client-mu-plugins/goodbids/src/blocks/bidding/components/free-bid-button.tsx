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

	const classes = clsx(
		'bg-base rounded py-2 w-full block text-center no-underline text-lg',
		{
			'pointer-events-none cursor-not-allowed': disabled,
			'pointer-events-auto': !disabled,
		},
	);

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
