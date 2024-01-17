import { create } from 'zustand';
import { AuctionStatus, Message } from './types';
import { AuctionUpcomingResponse } from './get-auction';

type AuctionState = {
	totalBids: number;
	totalRaised: number;
	lastBid: number;
	lastBidder?: string;
	startTime: Date;
	endTime: Date;
	freeBidsAvailable: boolean;
	currentBid: number;
	auctionStatus: AuctionStatus;
	initialFetchCompleted: boolean;
	useSocket: boolean;
};

interface AuctionActions {
	setAuctionState: (state: Message) => void;
	setAuctionStatus: (status: AuctionStatus) => void;
	setUpcomingAuction: (data: AuctionUpcomingResponse) => void;
}

const useAuctionStore = create<AuctionState & AuctionActions>()((set) => ({
	totalBids: 0,
	totalRaised: 0,
	lastBid: 0,
	lastBidder: undefined,
	startTime: new Date(),
	endTime: new Date(new Date().getTime() + 1000 * 60 * 60 * 24),
	freeBidsAvailable: false,
	currentBid: 0,
	auctionStatus: 'upcoming',
	initialFetchCompleted: false,
	useSocket: false,
	setAuctionState: (state: Message) => {
		set({
			totalBids: state.payload.totalBids,
			totalRaised: state.payload.totalRaised,
			lastBid: state.payload.lastBid,
			lastBidder: state.payload.lastBidder,
			startTime: new Date(state.payload.startTime),
			endTime: new Date(state.payload.endTime),
			freeBidsAvailable: state.payload.freeBidsAvailable,
			currentBid: state.payload.currentBid,
		});
	},
	setAuctionStatus: (status: AuctionStatus) => {
		set({ auctionStatus: status });
	},
	setUpcomingAuction: (data: AuctionUpcomingResponse) => {
		set({
			...data,
			startTime: new Date(data.startTime),
			endTime: new Date(data.endTime),
			initialFetchCompleted: true,
		});
	},
}));

export const useAuction = () => {
	return useAuctionStore((state) => state);
};
