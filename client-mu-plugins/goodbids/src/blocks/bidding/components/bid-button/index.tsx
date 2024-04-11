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
import { useEffect, useState } from 'react';
import { fadeAnimation } from '../../utils/animations';
import { __ } from '@wordpress/i18n';
import { FirstTimeDialog } from '../first-time-dialog';

const closingStatuses: AuctionStatus[] = ['closing', 'preclosing'];
const liveAndClosingStatuses: AuctionStatus[] = ['live', ...closingStatuses];

export function BidButton() {
	const { auctionStatus, isLastBidder, rewardClaimed } = useBiddingState();

	return (
		<AnimatePresence>
			{liveAndClosingStatuses.includes(auctionStatus) && (
				<>
					<LiveAndClosing />
				</>
			)}

			{auctionStatus === 'closed' && isLastBidder && !rewardClaimed && (
				<ClosedLastBidderAndRewardUnclaimed />
			)}

			{auctionStatus === 'closed' && isLastBidder && rewardClaimed && (
				<ClosedAndLastBidderAndRewardClaimed />
			)}
		</AnimatePresence>
	);
}

function LiveAndClosing() {
	const { isLastBidder, currentBid, bidUrl, auctionStatus } =
		useBiddingState();

	const [showDialog, setShowDialog] = useState(false);

	const motionValue = useMotionValue(currentBid);
	const calculatedValue = useTransform(
		motionValue,
		(value) => `$${Math.round(value).toLocaleString()}`,
	);

	const disabled = isLastBidder || closingStatuses.includes(auctionStatus);

	const classes = clsx('sample btn-fill text-center transition-colors', {
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
		<>
			<div className="flex flex-col items-center justify-center">
				<p>
					<p className="inline font-bold">
						{`LAST MINUTE BIDS automatically extend this auction's close date - `}
					</p>
					Smart bidders bid early.
				</p>
			</div>
			<motion.a
				layout
				{...fadeAnimation}
				className={classes}
				href={isLastBidder ? '#' : bidUrl}
				aria-live="polite"
				onFocus={() => setShowDialog(true)}
				onMouseOver={() => setShowDialog(true)}
			>
				{__('GOODBID', 'goodbids')}{' '}
				<motion.span>{calculatedValue}</motion.span>{' '}
				{__('Now', 'goodbids')}
			</motion.a>
			<FirstTimeDialog showDialog={showDialog} />
		</>
	);
}

function ClosedLastBidderAndRewardUnclaimed() {
	const { rewardUrl } = useBiddingState();

	return (
		<motion.a
			layout
			{...fadeAnimation}
			href={rewardUrl}
			className="btn-fill text-center"
			aria-live="polite"
		>
			{__('Claim Your Reward', 'goodbids')}
		</motion.a>
	);
}

function ClosedAndLastBidderAndRewardClaimed() {
	return (
		<motion.span
			layout
			{...fadeAnimation}
			className="rounded border border-solid border-transparent bg-contrast px-6 py-2 text-center leading-normal text-base-2 no-underline"
			aria-live="polite"
		>
			{__('Congratulations! Your reward has been claimed.', 'goodbids')}
		</motion.span>
	);
}
