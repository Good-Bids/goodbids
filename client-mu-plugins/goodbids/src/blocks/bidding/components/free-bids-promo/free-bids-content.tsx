import { AnimatePresence, motion } from 'framer-motion';
import { Skeleton } from '../skeleton';
import { useBiddingState } from '../../store';
import { liveStatuses, upcomingStatuses } from '../../utils/statuses';
import { fadeAnimation } from '../../utils/animations';
import { useCopyToClipboard } from './use-copy-to-clipboard';
import { useEffect, useState } from 'react';

export function FreeBidsContent() {
	const {
		auctionStatus,
		userId,
		freeBidsAvailable,
		isLastBidder,
		userReferralUrl,
	} = useBiddingState();
	const [copied, clearCopyState, copyToClipboard] = useCopyToClipboard();
	const [swapContent, setSwapContent] = useState(false);

	useEffect(() => {
		if (copied) {
			setSwapContent(true);

			const timeout = setTimeout(() => {
				setSwapContent(false);
				clearCopyState();
			}, 2500);

			return () => clearTimeout(timeout);
		}
	}, [copied, clearCopyState]);

	const copyReferralUrl = () => {
		copyToClipboard(userReferralUrl);
	};

	return (
		<div className="relative">
			<Skeleton visible={auctionStatus === 'initializing'} size="lg" />
			<AnimatePresence>
				{upcomingStatuses.includes(auctionStatus) && userId && (
					<UpcomingAndUser
						swapContent={swapContent}
						copyToClipboard={copyReferralUrl}
					/>
				)}

				{liveStatuses.includes(auctionStatus) &&
					userId &&
					freeBidsAvailable &&
					!isLastBidder && (
						<LiveAndUserAndFreeBids
							swapContent={swapContent}
							copyToClipboard={copyReferralUrl}
						/>
					)}

				{liveStatuses.includes(auctionStatus) &&
					userId &&
					(!freeBidsAvailable || isLastBidder) && (
						<LiveAndUser
							swapContent={swapContent}
							copyToClipboard={copyReferralUrl}
						/>
					)}

				{auctionStatus !== 'initializing' && !userId && (
					<NoUser
						swapContent={swapContent}
						copyToClipboard={copyReferralUrl}
					/>
				)}
			</AnimatePresence>
		</div>
	);
}

type ContentWrapperProps = {
	children: React.ReactNode;
	swapContent: boolean;
};

function ContentWrapper({ children, swapContent }: ContentWrapperProps) {
	return (
		<motion.p {...fadeAnimation} className="m-0" aria-live="polite">
			{swapContent ? 'Share Link Copied!' : children}
		</motion.p>
	);
}

type ShareLinkProps = {
	children: React.ReactNode;
	copyToClipboard: () => void;
};

function ShareLink({ children, copyToClipboard }: ShareLinkProps) {
	return (
		<a
			href="#"
			role="button"
			onClick={copyToClipboard}
			className="font-bold underline"
		>
			{children}
		</a>
	);
}

type ContentProps = {
	swapContent: boolean;
	copyToClipboard: () => void;
};

function UpcomingAndUser({ swapContent, copyToClipboard }: ContentProps) {
	return (
		<ContentWrapper swapContent={swapContent}>
			Place one of the first five <b>paid bids</b> in this auction or{' '}
			<ShareLink copyToClipboard={copyToClipboard}>
				share GOODBIDS with a friend
			</ShareLink>{' '}
			to <b>earn a free bid</b>!
		</ContentWrapper>
	);
}

// Displayed if:
// - the auction is live
// - the user is logged in
// - the auction has free bids available
// - the user is not the last bidder
function LiveAndUserAndFreeBids({
	swapContent,
	copyToClipboard,
}: ContentProps) {
	const { currentBid } = useBiddingState();

	return (
		<ContentWrapper swapContent={swapContent}>
			GOODBID <b>${currentBid}</b> now or{' '}
			<ShareLink copyToClipboard={copyToClipboard}>
				share GOODBIDS with a friend
			</ShareLink>{' '}
			to <b>earn a free bid</b>!
		</ContentWrapper>
	);
}

// Displayed if:
// - the auction is live
// - the user is logged in
// and if:
// - the auction has no free bids available
// or
// - the user is the last bidder
function LiveAndUser({ swapContent, copyToClipboard }: ContentProps) {
	return (
		<ContentWrapper swapContent={swapContent}>
			<ShareLink copyToClipboard={copyToClipboard}>
				Share GOODBIDS with a friend
			</ShareLink>{' '}
			to <b>earn a free bid</b>!
		</ContentWrapper>
	);
}

function NoUser({ swapContent, copyToClipboard }: ContentProps) {
	return (
		<ContentWrapper swapContent={swapContent}>
			GOODBIDS users earn <b>free bids</b> when they place one of the{' '}
			<b>first five paid bids</b> in an auction or{' '}
			<ShareLink copyToClipboard={copyToClipboard}>
				share GOODBIDS with a friend{' '}
			</ShareLink>{' '}
			to <b>earn a free bid</b>!
		</ContentWrapper>
	);
}
