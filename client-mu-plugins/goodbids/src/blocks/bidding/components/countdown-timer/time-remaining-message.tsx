import { AuctionStatus } from '../../store/types';
import { formatTimeRemaining } from './format-time-remaining';

export type TimeRemainingMessageProps = {
	auctionStatus: AuctionStatus;
	timeRemainingMs: number;
	userTotalBids: number;
	isLastBidder: boolean;
};

export function TimeRemainingMessage({
	auctionStatus,
	timeRemainingMs,
	userTotalBids,
	isLastBidder,
}: TimeRemainingMessageProps) {
	const formattedTimeRemaining = formatTimeRemaining(timeRemainingMs);

	if (auctionStatus === 'upcoming' || auctionStatus === 'starting') {
		return (
			<span>
				<b>Bidding starts in {formattedTimeRemaining}</b>
			</span>
		);
	}

	if (auctionStatus === 'live') {
		if (isLastBidder) {
			return (
				<span>
					<b>You will win in {formattedTimeRemaining}</b> if nobody
					else bids.
				</span>
			);
		}

		return (
			<span>
				<b>Ending in {formattedTimeRemaining}</b> if nobody else bids.
			</span>
		);
	}

	if (auctionStatus === 'closing') {
		return (
			<span>
				<b>Auction closing.</b> Please stand by while we check for any
				last second bids!
			</span>
		);
	}

	if (auctionStatus === 'closed') {
		if (isLastBidder) {
			return (
				<span>
					<b>Auction has closed.</b> Congratulations, you won!
				</span>
			);
		}

		if (userTotalBids > 0) {
			return (
				<span>
					<b>Auction has closed.</b> Sorry, you were out-bid.
				</span>
			);
		}

		return (
			<span>
				<b>Auction has closed.</b>
			</span>
		);
	}

	return (
		<span>
			<b>Calculating duration</b>
		</span>
	);
}
