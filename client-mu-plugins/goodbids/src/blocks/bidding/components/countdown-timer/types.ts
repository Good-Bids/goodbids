import { AuctionStatus } from '../../store/types';

export type TimeRemainingProps = {
	auctionStatus: AuctionStatus;
	timeRemaining: number;
	userTotalBids: number;
	isLastBidder: boolean;
};
