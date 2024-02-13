import { motion } from 'framer-motion';
import { useBiddingState } from '../../store';
import { LoadingIcon } from '../icons/loading-icon';
import { ClockIcon } from '../icons/clock-icon';
import { AuctionStatus } from '../../store/types';
import { fadeAnimation } from '../../utils/animations';

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

	if (closingStatuses.includes(auctionStatus)) {
		return <ClosingAndPreclosing />;
	}

	if (nonInitializingStatus.includes(auctionStatus)) {
		return <AllOther />;
	}

	return null;
}

type IconWrapperProps = {
	children: React.ReactNode;
};

function IconWrapper({ children }: IconWrapperProps) {
	return (
		<motion.div {...fadeAnimation} className="flex items-center">
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
