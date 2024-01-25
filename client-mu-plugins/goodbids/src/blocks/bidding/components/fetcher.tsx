import React, { useEffect } from 'react';
import { useGetAuction } from '../utils/get-auction';
import { useCookies } from 'react-cookie';
import { useGetUser } from '../utils/get-user';
import { useBiddingState } from '../store';

const SESSION_COOKIE = 'goodbids_auctioneer_session';

type FetcherProps = {
	auctionId: number;
	children: React.ReactNode;
};

export function Fetcher({ auctionId, children }: FetcherProps) {
	const { setUser, setInitialAuction } = useBiddingState();

	const [cookies] = useCookies([SESSION_COOKIE]);
	const cookie = cookies[SESSION_COOKIE] as string | undefined;

	const {
		data: auctionData,
		error: auctionError,
		status: auctionStatus,
	} = useGetAuction(auctionId);

	const {
		data: userData,
		error: userError,
		status: userStatus,
	} = useGetUser(auctionId, cookie);

	useEffect(() => {
		if (auctionStatus === 'success') {
			setInitialAuction(auctionData);
		}

		if (auctionStatus === 'error') {
			console.error(auctionError);
		}
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [auctionData, auctionError, auctionStatus]);

	useEffect(() => {
		if (userStatus === 'success') {
			setUser(userData);
		}

		if (userError) {
			console.error(userError);
		}
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [userData, userError, userStatus]);

	if (auctionStatus === 'pending' || (userStatus === 'pending' && cookie)) {
		return <p>Loading...</p>;
	}

	if (auctionStatus === 'error' || userStatus === 'error') {
		return <p>Something went wrong</p>;
	}

	return children;
}
