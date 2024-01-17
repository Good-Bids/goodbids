export type AuctionStatus = 'upcoming' | 'live' | 'closed';

type MessageType = 'start' | 'update' | 'end';

type AuctionType = {
	startTime: string;
	endTime: string;
	totalBids: number;
	totalRaised: number;
	currentBid: number;
	lastBid: number;
	lastBidder: string;
	freeBidsAvailable: boolean;
};

export type Message = {
	type: MessageType;
	payload: AuctionType;
};
