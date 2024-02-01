export type AuctionStatus = 'upcoming' | 'live' | 'closed';

type MessageType = 'update' | 'end';

type AuctionType = {
	startTime: string;
	endTime: string;
	totalBids: number;
	totalRaised: number;
	currentBid: number;
	lastBid: number;
	lastBidder?: number;
	auctionStatus: AuctionStatus;
};

type SocketSuccessMessage = {
	type: MessageType;
	payload: AuctionType;
};

type SocketErrorMessage = {
	type: 'not-found';
	payload: string;
};

export type SocketMessage = SocketSuccessMessage | SocketErrorMessage;
