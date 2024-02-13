import { createTrackedSelector } from 'react-tracked';
import { useBiddingStore } from './store';

export const useBiddingState = createTrackedSelector(useBiddingStore);
