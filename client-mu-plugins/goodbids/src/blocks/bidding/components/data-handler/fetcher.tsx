import { useEffect } from 'react';
import { useGetAuction } from '../../utils/get-auction';
import { useCookies } from 'react-cookie';
import { useGetUser } from '../../utils/get-user';
import { useBiddingState } from '../../store';

const SESSION_COOKIE = 'goodbids_auctioneer_session';

type FetcherProps = {
	auctionId: number;
};

export function Fetcher({ auctionId }: FetcherProps) {
	const { setUser, setFetchAuction, fetchMode, setFetchingError } =
		useBiddingState();

	const [cookies] = useCookies([SESSION_COOKIE]);
	const cookie = cookies[SESSION_COOKIE] as string | undefined;

	const {
		data: auctionData,
		error: auctionError,
		status: auctionStatus,
	} = useGetAuction(auctionId, fetchMode);

	const {
		data: userData,
		error: userError,
		status: userStatus,
	} = useGetUser(auctionId, cookie);

	useEffect(() => {
		if (auctionStatus === 'success') {
			setFetchAuction(auctionData);
		}

		if (auctionStatus === 'error') {
			setFetchingError();
		}
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [auctionData, auctionError, auctionStatus]);

	useEffect(() => {
		if (userStatus === 'success') {
			setUser(userData);
		}

		if (userError) {
			setFetchingError();
		}
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [userData, userError, userStatus]);

	return null;
}
