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
	startTime: Date;
	endTime: Date;
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
};

export type FetchingType = {
	initialFetchComplete: boolean;
	fetchMode: 'no-socket' | 'socket' | 'polling';
	hasSocketError: boolean;
};

export type BiddingState = UrlsType &
	TimingType &
	BidsType &
	UserType &
	FetchingType;
