import { AuctionResponse } from '../utils/get-auction';
import { UserResponse } from '../utils/get-user';
import { SocketMessage } from '../utils/types';
import { StatusAndTimeRemainingType } from './timing';

export type AuctionStatus =
	| 'initializing'
	| 'upcoming'
	| 'starting'
	| 'prelive'
	| 'live'
	| 'preclosing'
	| 'closing'
	| 'closed';

export type UrlsType = {
	socketUrl: string;
	accountUrl: string;
	bidUrl: string;
};

export type TimingType = {
	auctionStatus: AuctionStatus;
	interval: NodeJS.Timeout | undefined;
	timeRemainingMs: number | undefined;
	startTime: Date | undefined;
	endTime: Date | undefined;
};

export type BidsType = {
	totalBids: number;
	totalRaised: number;
	currentBid: number;
	lastBid: number;
	lastBidder?: number | null;
	freeBidsAvailable: number;
	freeBidsAllowed: boolean;
};

export type UserType = {
	rewardUrl?: string;
	userId?: number;
	userFreeBids: number;
	userTotalBids: number;
	userTotalDonated: number;
	isLastBidder?: boolean;
};

export type FetchingType = {
	initialFetchComplete: boolean;
	hasSocketError: boolean;
};

export type BiddingActions = {
	setAuctionStatus: (status: StatusAndTimeRemainingType) => void;
	setFetchAuction: (data: AuctionResponse) => void;
	setSocketError: () => void;
	setSocketAuction: (message: SocketMessage) => void;
	setUser: (data: UserResponse) => void;
	setTimeRemaining: (timeRemainingMs: number | undefined) => void;
	setCountdownInterval: (startTime: Date, endTime: Date) => void;
};

export type BiddingState = UrlsType &
	TimingType &
	BidsType &
	UserType &
	FetchingType;
