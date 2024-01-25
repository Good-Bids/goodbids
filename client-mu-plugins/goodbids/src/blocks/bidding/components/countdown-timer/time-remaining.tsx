import clsx from 'clsx';
import { ClockIcon } from '../clock-icon';
import { LoadingIcon } from '../loading-icon';
import {
	TimeRemainingMessage,
	TimeRemainingMessageProps,
} from './time-remaining-message';

type TimeRemainingProps = TimeRemainingMessageProps;

export function TimeRemaining({
	auctionStatus,
	timeRemaining,
	userTotalBids,
	isLastBidder,
}: TimeRemainingProps) {
	const placeholderClasses = clsx(
		'absolute top-1/2 -translate-y-1/2 transition-all',
		{
			'opacity-0': auctionStatus !== 'initializing',
			'opacity-100': auctionStatus === 'initializing',
		},
	);

	const timeRemainingClasses = clsx(
		'absolute top-1/2 -translate-y-1/2 transition-all',
		{
			'opacity-100': auctionStatus !== 'initializing',
			'opacity-0': auctionStatus === 'initializing',
		},
	);

	return (
		<div className="flex items-center gap-3 px-4 h-12">
			{auctionStatus === 'closing' ? <LoadingIcon spin /> : <ClockIcon />}

			<div className="relative w-full">
				<span className={placeholderClasses}>
					<b>Discovering relativity</b>
				</span>

				<div className={timeRemainingClasses}>
					<TimeRemainingMessage
						auctionStatus={auctionStatus}
						timeRemaining={timeRemaining}
						userTotalBids={userTotalBids}
						isLastBidder={isLastBidder}
					/>
				</div>
			</div>
		</div>
	);
}
