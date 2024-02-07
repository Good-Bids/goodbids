import { AuctionStatus } from '../store/types';

export const initializingStatuses: AuctionStatus[] = ['initializing'];

export const upcomingStatuses: AuctionStatus[] = [
	'upcoming',
	'starting',
	'prelive',
];

export const liveStatuses: AuctionStatus[] = ['live'];

export const closedStatuses: AuctionStatus[] = [
	'preclosing',
	'closing',
	'closed',
];

export const nonUpcomingStatuses: AuctionStatus[] = [
	...initializingStatuses,
	...liveStatuses,
	...closedStatuses,
];

export const nonLiveStatuses: AuctionStatus[] = [
	...initializingStatuses,
	...upcomingStatuses,
	...closedStatuses,
];

export const nonClosedStatuses: AuctionStatus[] = [
	...initializingStatuses,
	...upcomingStatuses,
	...liveStatuses,
];
