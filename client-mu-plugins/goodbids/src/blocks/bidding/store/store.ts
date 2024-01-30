import { mountStoreDevtool } from 'simple-zustand-devtools';
import { create } from 'zustand';
import { AuctionStatus, BiddingState } from './types';
import { UserResponse } from '../utils/get-user';
import {
	handelSetFetchAuction,
	handleSetUser,
	handleSetSocketAuction,
	handleSetSocketMode,
	handleSetAuctionStatus,
	handleSetSocketError,
} from './functions';
import { AuctionResponse } from '../utils/get-auction';
import { SocketMessage } from '../utils/types';

type BiddingActions = {
	setAuctionStatus: (status: AuctionStatus) => void;
	setFetchAuction: (data: AuctionResponse) => void;
	setSocketError: () => void;
	setSocketAuction: (message: SocketMessage) => void;
	setSocketMode: (override?: boolean) => void;
	setUser: (data: UserResponse) => void;
};

export const useBiddingStore = create<BiddingState & BiddingActions>((set) => ({
	socketUrl: '',
	accountUrl: '',
	bidUrl: '',

	auctionStatus: 'initializing',
	startTime: new Date(),
	endTime: new Date(),

	totalBids: 0,
	totalRaised: 0,
	currentBid: 0,
	lastBid: 0,
	freeBidsAvailable: 0,
	freeBidsAllowed: false,

	isUserLoggedIn: false,
	isLastBidder: false,
	rewardUrl: undefined,
	userFreeBids: 0,
	userTotalBids: 0,
	userTotalDonated: 0,

	initialFetchComplete: false,
	fetchMode: 'no-socket',
	hasSocketError: false,

	setAuctionStatus: (status) => {
		set((state) =>
			handleSetAuctionStatus(
				status,
				state.auctionStatus,
				state.fetchMode,
			),
		);
	},

	setFetchAuction: (data) => {
		set(handelSetFetchAuction(data));
	},

	setSocketError: () => {
		set(handleSetSocketError());
	},

	setSocketAuction: (message) => {
		set(handleSetSocketAuction(message));
	},

	setSocketMode: (override) => {
		set((state) => handleSetSocketMode(state.fetchMode, override));
	},

	setUser: (data) => {
		set(handleSetUser(data));
	},
}));

if (process.env.NODE_ENV === 'development') {
	mountStoreDevtool('Bidding Store', useBiddingStore);
}
