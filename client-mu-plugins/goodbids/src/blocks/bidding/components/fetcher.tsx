import { useEffect } from 'react';
import { useGetAuction } from '../utils/get-auction';
import { useAuction } from '../utils/auction-store';

type FetcherProps = {
	auctionId: number;
};

export function Fetcher({ auctionId }: FetcherProps) {
	const { setUpcomingAuction, setLiveAuction, setClosedAuction } =
		useAuction();

	// TODO: If auctioneer fails, we should swap this to retry every minute or so.
	const refetchInterval: number | undefined = undefined;

	const {
		isSuccess: auctionSuccess,
		data: auctionData,
		isFetching: auctionLoading,
		isError: auctionError,
	} = useGetAuction(auctionId, refetchInterval);

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

	// TODO: Add reasonable loading and error states.
	if (auctionLoading) {
		return <div>Loading...</div>;
	}

	if (auctionError) {
		return <div>Error loading auction.</div>;
	}

	return null;
}
