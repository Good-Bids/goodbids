import { create } from 'zustand';
import { AuctionStatus, SocketMessage } from './types';
import {
	AuctionClosedResponse,
	AuctionLiveResponse,
	AuctionUpcomingResponse,
} from './get-auction';
import { mountStoreDevtool } from 'simple-zustand-devtools';

type AuctionState = {
	auctionFetchStatus: 'loading' | 'success' | 'error';

	socketUrl: string;
	totalBids: number;
	totalRaised: number;
	lastBid: number;
	lastBidder?: string;
	startTime: Date;
	endTime: Date;
	freeBidsAvailable: boolean;
	currentBid: number;
	auctionStatus: AuctionStatus;
	useSocket: boolean;
	usePolling: boolean;
};

interface AuctionActions {
	handleSocketUpdate: (state: SocketMessage) => void;
	setAuctionStatus: (status: AuctionStatus) => void;
	setUpcomingAuction: (data: AuctionUpcomingResponse) => void;
	setLiveAuction: (data: AuctionLiveResponse) => void;
	setClosedAuction: (data: AuctionClosedResponse) => void;
	setUsePolling: (usePolling: boolean) => void;
	setAuctionFetchError: () => void;
}

const useAuctionStore = create<AuctionState & AuctionActions>()((set) => ({
	auctionFetchStatus: 'loading',
	socketUrl: '',
	totalBids: 0,
	totalRaised: 0,
	lastBid: 0,
	lastBidder: undefined,
	startTime: new Date(),
	endTime: new Date(new Date().getTime() + 1000 * 60 * 60 * 24),
	freeBidsAvailable: false,
	currentBid: 0,
	auctionStatus: 'upcoming',
	useSocket: false,
	usePolling: false,
	handleSocketUpdate: (state: SocketMessage) => {
		if (state.type === 'not-found') {
			set({ usePolling: true });
			return;
		}

		set({
			totalBids: state.payload.totalBids,
			totalRaised: state.payload.totalRaised,
			lastBid: state.payload.lastBid,
			startTime: new Date(state.payload.startTime),
			endTime: new Date(state.payload.endTime),
			currentBid: state.payload.currentBid,
			auctionStatus: state.payload.auctionStatus,
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
			auctionFetchStatus: 'success',
			useSocket: false,
		});
	},
	setLiveAuction: (data: AuctionLiveResponse) => {
		set({
			...data,
			startTime: new Date(data.startTime),
			endTime: new Date(data.endTime),
			auctionFetchStatus: 'success',
			useSocket: true,
		});
	},
	setClosedAuction: (data: AuctionClosedResponse) => {
		set({
			...data,
			endTime: new Date(data.endTime),
			auctionFetchStatus: 'success',
			useSocket: false,
		});
	},
	setUsePolling: (usePolling: boolean) => {
		set({ usePolling });
	},
	setAuctionFetchError: () => {
		set({ auctionFetchStatus: 'error' });
	},
}));

if (process.env.NODE_ENV === 'development') {
	mountStoreDevtool('Auction', useAuctionStore);
}

export const useAuction = () => {
	return useAuctionStore((state) => state);
};
