export type UrlsType = {
	socketUrl: string;
	accountUrl: string;
	bidUrl: string;
};

export type TimingType = {
	auctionStatus: 'upcoming' | 'starting' | 'live' | 'closing' | 'closed';
	startTime: Date;
	endTime: Date;
};

export type BidsType = {
	totalBids: number;
	totalRaised: number;
	currentBid: number;
	lastBid: number;
	freeBidsAvailable: number;
	freeBidsAllowed: boolean;
};

export type UserType = {
	isUserLoggedIn: boolean;
	isLastBidder: boolean;
	rewardUrl?: string;
	userFreeBids: number;
	userTotalBids: number;
	userTotalDonated: number;
};

export type FetchingType = {
	initialFetchComplete: boolean;
	fetchMode: 'no-socket' | 'socket' | 'polling';
	refetchInterval?: number;
};

export type BiddingState = UrlsType &
	TimingType &
	BidsType &
	UserType &
	FetchingType;
