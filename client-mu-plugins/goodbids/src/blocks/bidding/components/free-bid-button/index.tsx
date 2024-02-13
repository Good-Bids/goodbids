import clsx from 'clsx';
import { useBiddingState } from '../../store';
import { AnimatePresence, motion } from 'framer-motion';
import { fadeAnimation } from '../../utils/animations';

export function FreeBidButton() {
	const {
		userFreeBids,
		auctionStatus,
		bidUrl,
		freeBidsAllowed,
		isLastBidder,
		userId,
	} = useBiddingState();

	const disabled = isLastBidder || userFreeBids < 1;

	const freeBidUrl = `${bidUrl}&use-free-bid=1`;

	const classes = clsx('btn-fill-secondary text-center', {
		'pointer-events-none cursor-not-allowed !text-contrast-4': disabled,
	});

	return (
		<AnimatePresence>
			{freeBidsAllowed && auctionStatus === 'live' && (
				<motion.a
					layout
					{...fadeAnimation}
					href={disabled ? '' : freeBidUrl}
					className={classes}
					aria-disabled={disabled}
					aria-live="polite"
				>
					{`Place free bid ${
						userId ? `(${userFreeBids} available)` : ''
					}`}
				</motion.a>
			)}
		</AnimatePresence>
	);
}
