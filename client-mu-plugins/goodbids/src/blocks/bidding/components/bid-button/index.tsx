import {
	AnimatePresence,
	animate,
	motion,
	useMotionValue,
	useTransform,
} from 'framer-motion';
import { useBiddingState } from '../../store';
import { AuctionStatus } from '../../store/types';
import clsx from 'clsx';
import { useEffect } from 'react';
import { fadeAnimation } from '../../utils/animations';

const closingStatuses: AuctionStatus[] = ['closing', 'preclosing'];
const liveAndClosingStatuses: AuctionStatus[] = ['live', ...closingStatuses];

export function BidButton() {
	const { auctionStatus, isLastBidder } = useBiddingState();

	return (
		<AnimatePresence>
			{liveAndClosingStatuses.includes(auctionStatus) && (
				<LiveAndClosing />
			)}

			{auctionStatus === 'closed' && isLastBidder && (
				<ClosedAndLastBidder />
			)}
		</AnimatePresence>
	);
}

function LiveAndClosing() {
	const { isLastBidder, currentBid, bidUrl, auctionStatus } =
		useBiddingState();

	const motionValue = useMotionValue(currentBid);
	const calculatedValue = useTransform(
		motionValue,
		(value) => `$${Math.round(value).toLocaleString()}`,
	);

	const disabled = isLastBidder || closingStatuses.includes(auctionStatus);

	const classes = clsx('btn-fill text-center transition-colors', {
		'pointer-events-none cursor-not-allowed !bg-base-3 !text-contrast-4':
			disabled,
	});

	useEffect(() => {
		const animation = animate(motionValue, currentBid, {
			duration: 0.5,
			ease: 'easeInOut',
		});

		return animation.stop;
	}, [currentBid, motionValue]);

	return (
		<motion.a
			layout
			{...fadeAnimation}
			className={classes}
			href={isLastBidder ? '#' : bidUrl}
			aria-live="polite"
		>
			GOODBID <motion.span>{calculatedValue}</motion.span> Now
		</motion.a>
	);
}

function ClosedAndLastBidder() {
	const { rewardUrl } = useBiddingState();

	return (
		<motion.a
			layout
			{...fadeAnimation}
			href={rewardUrl}
			className="btn-fill text-center"
			aria-live="polite"
		>
			Claim Your Reward
		</motion.a>
	);
}
