import { AuctionStatus } from '../../store/types';
import { formatTimeRemaining } from './format-time-remaing';

export type TimeRemainingMessageProps = {
	auctionStatus: AuctionStatus;
	timeRemaining: number;
	userTotalBids: number;
	isLastBidder: boolean;
};

export function TimeRemainingMessage({
	auctionStatus,
	timeRemaining,
	userTotalBids,
	isLastBidder,
}: TimeRemainingMessageProps) {
	const formattedTimeRemaining = formatTimeRemaining(timeRemaining);

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
			<b>Discovering relativity</b>
		</span>
	);
}
