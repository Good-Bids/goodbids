import { create } from 'zustand';
import { UserResponse } from './get-user';
import { mountStoreDevtool } from 'simple-zustand-devtools';

type UserState = {
	userFetchStatus: 'loading' | 'complete' | 'error' | 'idle';
	isLastBidder: boolean;
	rewardUrl?: string;
	userFreeBids: number;
	userTotalBids: number;
	userTotalDonated: number;
};

type UserActions = {
	setUserDetails: (response: UserResponse) => void;
	setUserIdle: () => void;
	setUserFetchError: () => void;
	clearLastBidder: () => void;
};

const useUserStore = create<UserState & UserActions>((set) => ({
	userFetchStatus: 'loading',
	isLastBidder: false,
	rewardUrl: undefined,
	userFreeBids: 0,
	userTotalBids: 0,
	userTotalDonated: 0,
	setUserDetails: (response) => {
		set({
			...response,
			userFetchStatus: 'complete',
		});
	},
	setUserIdle: () => {
		set({ userFetchStatus: 'idle' });
	},
	setUserFetchError: () => {
		set({ userFetchStatus: 'error' });
	},
	clearLastBidder: () => {
		set({ isLastBidder: false });
	},
}));

if (process.env.NODE_ENV === 'development') {
	mountStoreDevtool('User', useUserStore);
}

export const useUser = () => {
	return useUserStore((state) => state);
};
