import { useBiddingStore } from './store';

export const useBiddingState = () => {
	return useBiddingStore((state) => state);
};
