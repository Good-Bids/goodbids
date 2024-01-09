import { create } from 'zustand';
import { AuctionStatus, Message } from './types';

type AuctionState = {
	totalBids: number;
	totalRaised: number;
	lastBid: number;
	lastBidder?: string;
	startTime: Date;
	endTime: Date;
	freeBidsAvailable: boolean;
	currentBid: number;
	auctionStatus: 'not-started' | 'in-progress' | 'ended';
};

interface AuctionActions {
	setAuctionState: (state: Message) => void;
	setAuctionStatus: (status: AuctionStatus) => void;
}

const useAuctionStore = create<AuctionState & AuctionActions>()((set) => ({
	totalBids: 0,
	totalRaised: 0,
	lastBid: 0,
	lastBidder: undefined,
	startTime: new Date(),
	endTime: new Date(),
	freeBidsAvailable: false,
	currentBid: 0,
	auctionStatus: 'not-started',
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
}));

export const useAuction = () => {
	return useAuctionStore((state) => state);
};
