import { mountStoreDevtool } from 'simple-zustand-devtools';
import { create } from 'zustand';
import { BiddingState } from './types';
import { UserResponse } from '../utils/get-user';
import {
	handleSetInitialAuction,
	handleSetPollingMode,
	handleSetUser,
	handleSetSocketAuction,
} from './functions';
import { AuctionResponse } from '../utils/get-auction';
import { SocketMessage } from '../utils/types';

type BiddingActions = {
	setInitialAuction: (data: AuctionResponse) => void;
	setPollingMode: () => void;
	setSocketAuction: (message: SocketMessage) => void;
	setUser: (data: UserResponse) => void;
};

export const useBiddingStore = create<BiddingState & BiddingActions>((set) => ({
	socketUrl: '',
	accountUrl: '',
	bidUrl: '',

	auctionStatus: 'upcoming',
	startTime: new Date(),
	endTime: new Date(),

	totalBids: 0,
	totalRaised: 0,
	currentBid: 0,
	lastBid: 0,
	freeBidsAvailable: 0,
	freeBidsAllowed: false,

	isLastBidder: false,
	rewardUrl: undefined,
	userFreeBids: 0,
	userTotalBids: 0,
	userTotalDonated: 0,

	isUserLoggedIn: false,
	initialFetchComplete: false,
	fetchMode: 'no-socket',
	refetchInterval: undefined,

	setInitialAuction: (data) => {
		set(handleSetInitialAuction(data));
	},
	setPollingMode: () => {
		set(handleSetPollingMode());
	},
	setSocketAuction: (message) => {
		set(handleSetSocketAuction(message));
	},
	setUser: (data) => {
		set(handleSetUser(data));
	},
}));

if (process.env.NODE_ENV === 'development') {
	mountStoreDevtool('Bidding Store', useBiddingStore);
}
