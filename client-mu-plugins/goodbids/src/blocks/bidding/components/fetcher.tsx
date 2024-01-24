import { useEffect } from 'react';
import { useGetAuction } from '../utils/get-auction';
import { useAuction } from '../utils/auction-store';
import { useCookies } from 'react-cookie';
import { useGetUser } from '../utils/get-user';
import { useUser } from '../utils/user-store';

const SESSION_COOKIE = 'goodbids_auctioneer_session';

type FetcherProps = {
	auctionId: number;
};

export function Fetcher({ auctionId }: FetcherProps) {
	const [cookies] = useCookies([SESSION_COOKIE]);
	const cookie = cookies[SESSION_COOKIE] as string | undefined;

	const {
		setUpcomingAuction,
		setLiveAuction,
		setClosedAuction,
		setAuctionFetchError,
	} = useAuction();
	const { setUserDetails, setUserIdle, setUserFetchError } = useUser();

	// TODO: If auctioneer fails, we should swap this to retry every minute or so.
	const refetchInterval: number | undefined = undefined;

	const {
		isSuccess: auctionSuccess,
		isError: auctionError,
		data: auctionData,
		error: auctionErrorData,
	} = useGetAuction(auctionId, refetchInterval);

	const {
		isSuccess: userSuccess,
		isError: userError,
		data: userData,
		error: userErrorData,
	} = useGetUser(auctionId, cookie);

	useEffect(() => {
		if (!cookie) {
			setUserIdle();
		}
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [cookie]);

	useEffect(() => {
		if (auctionSuccess) {
			if (auctionData.auctionStatus === 'upcoming') {
				setUpcomingAuction(auctionData);
			}

			if (auctionData.auctionStatus === 'live') {
				setLiveAuction(auctionData);
			}

			if (auctionData.auctionStatus === 'closed') {
				setClosedAuction(auctionData);
			}
		}

		if (auctionError) {
			setAuctionFetchError();
			// TODO: Hook into Sentry or disable in production
			console.error(auctionErrorData);
		}
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [auctionSuccess, auctionData, auctionError, auctionErrorData]);

	useEffect(() => {
		if (userSuccess) {
			setUserDetails(userData);
		}

		if (userError) {
			setUserFetchError();
			// TODO: Hook into Sentry or disable in production
			console.error(userErrorData);
		}
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [userSuccess, userData, userError, userErrorData]);

	return null;
}
