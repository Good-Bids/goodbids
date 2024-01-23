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

	const { setUpcomingAuction, setLiveAuction, setClosedAuction } =
		useAuction();
	const { setUserDetails, setUserIdle } = useUser();

	// TODO: If auctioneer fails, we should swap this to retry every minute or so.
	const refetchInterval: number | undefined = undefined;

	const { isSuccess: auctionSuccess, data: auctionData } = useGetAuction(
		auctionId,
		refetchInterval,
	);

	const { isSuccess: userSuccess, data: userData } = useGetUser(
		auctionId,
		cookie,
	);

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
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [auctionSuccess, auctionData]);

	useEffect(() => {
		if (userSuccess) {
			setUserDetails(userData);
		}
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [userSuccess, userData]);

	return null;
}
