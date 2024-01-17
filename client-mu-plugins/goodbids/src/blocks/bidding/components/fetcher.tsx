import { useEffect } from 'react';
import { useGetAuction } from '../utils/get-auction';
import { attributes } from '../utils/get-data-attributes';
import { useAuction } from '../utils/auction-store';

export function Fetcher() {
	const { auctionId } = attributes;
	const { setUpcomingAuction } = useAuction();

	// TODO: If auctioneer fails, we should swap this to retry every minute or so.
	const refetchInterval: number | undefined = undefined;

	const { isSuccess: auctionSuccess, data: auctionData } = useGetAuction(
		auctionId,
		refetchInterval,
	);

	useEffect(() => {
		if (auctionSuccess) {
			if (auctionData.auctionStatus === 'upcoming') {
				setUpcomingAuction(auctionData);
			}
		}
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [auctionSuccess]);

	return null;
}
