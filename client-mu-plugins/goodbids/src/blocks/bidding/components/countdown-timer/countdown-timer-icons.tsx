import { AnimatePresence, motion } from 'framer-motion';
import { useBiddingState } from '../../store';
import { LoadingIcon } from '../loading-icon';
import { ClockIcon } from '../clock-icon';
import { AuctionStatus } from '../../store/types';

const closingStatuses: AuctionStatus[] = ['closing', 'preclosing'];

const nonInitializingStatus: AuctionStatus[] = [
	'upcoming',
	'starting',
	'prelive',
	'live',
	'closed',
];

export function CountdownTimerIcons() {
	const { auctionStatus } = useBiddingState();

	return (
		<AnimatePresence>
			{closingStatuses.includes(auctionStatus) && (
				<ClosingAndPreclosing />
			)}

			{nonInitializingStatus.includes(auctionStatus) && <AllOther />}
		</AnimatePresence>
	);
}

type IconWrapperProps = {
	children: React.ReactNode;
};

function IconWrapper({ children }: IconWrapperProps) {
	return (
		<motion.div
			initial={{ opacity: 0 }}
			animate={{ opacity: 1 }}
			exit={{ opacity: 0 }}
			transition={{ duration: 0.2 }}
			className="flex items-center"
		>
			{children}
		</motion.div>
	);
}

function ClosingAndPreclosing() {
	return (
		<IconWrapper>
			<LoadingIcon spin />
		</IconWrapper>
	);
}

function AllOther() {
	return (
		<IconWrapper>
			<ClockIcon />
		</IconWrapper>
	);
}
