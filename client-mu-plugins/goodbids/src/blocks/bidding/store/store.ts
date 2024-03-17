import { mountStoreDevtool } from 'simple-zustand-devtools';
import { create } from 'zustand';
import { BiddingActions, BiddingState } from './types';
import {
	handelSetFetchAuction,
	handleSetUser,
	handleSetSocketAuction,
	handleSetAuctionStatus,
	handleSetSocketError,
} from './functions';
import { handleSetCountdownInterval } from './timing';

export const useBiddingStore = create<BiddingState & BiddingActions>((set) => ({
	socketUrl: '',
	accountUrl: '',
	bidUrl: '',

	auctionStatus: 'initializing',
	timeRemainingMs: undefined,
	interval: undefined,
	startTime: new Date(),
	endTime: new Date(),

	totalBids: 0,
	totalRaised: 0,
	currentBid: 0,
	lastBid: 0,
	lastBidder: undefined,
	useFreeBidParam: 'use-free-bid',
	freeBidsAvailable: 0,
	freeBidsAllowed: false,

	rewardUrl: undefined,
	userId: undefined,
	userFreeBids: 0,
	userTotalBids: 0,
	userTotalDonated: 0,
	rewardClaimed: false,
	isLastBidder: false,

	initialFetchComplete: false,
	hasSocketError: false,

	setAuctionStatus: (status) => {
		set((state) => handleSetAuctionStatus(status, state.auctionStatus));
	},

	setFetchAuction: (data) => {
		set((state) =>
			handelSetFetchAuction(
				data,
				state.userId,
				state.setCountdownInterval,
			),
		);
	},

	setSocketError: () => {
		set(handleSetSocketError());
	},

	setSocketAuction: (message) => {
		set((state) =>
			handleSetSocketAuction(
				message,
				state.userId,
				state.setCountdownInterval,
				state.startTime,
				state.endTime,
			),
		);
	},

	setUser: (data) => {
		set(handleSetUser(data));
	},

	setTimeRemaining: (timeRemainingMs) => {
		set({ timeRemainingMs });
	},

	setCountdownInterval: (startTime, endTime) => {
		set((state) =>
			handleSetCountdownInterval({ ...state, startTime, endTime }),
		);
	},
}));

if (process.env.NODE_ENV === 'development') {
	mountStoreDevtool('Bidding Store', useBiddingStore);
}
