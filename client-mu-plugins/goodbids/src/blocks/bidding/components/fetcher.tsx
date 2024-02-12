import React, { useEffect } from 'react';
import { useGetAuction } from '../utils/get-auction';
import { useCookies } from 'react-cookie';
import { useGetUser } from '../utils/get-user';
import { useBiddingState } from '../store';
import { WarningIcon } from './icons/warning-icon';
import { motion } from 'framer-motion';

const SESSION_COOKIE = 'goodbids_auctioneer_session';

type FetcherProps = {
	auctionId: number;
	children: React.ReactNode;
};

export function Fetcher({ auctionId, children }: FetcherProps) {
	const { setUser, setFetchAuction, hasSocketError, auctionStatus } =
		useBiddingState();

	const [cookies] = useCookies([SESSION_COOKIE]);
	const cookie = cookies[SESSION_COOKIE] as string | undefined;

	const {
		data: auctionData,
		error: auctionError,
		status: auctionFetchStatus,
	} = useGetAuction(auctionId, auctionStatus, hasSocketError);

	const {
		data: userData,
		error: userError,
		status: userStatus,
	} = useGetUser(auctionId, cookie);

	useEffect(() => {
		if (auctionFetchStatus === 'success') {
			setFetchAuction(auctionData);
		}
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [auctionData, auctionError, auctionStatus]);

	useEffect(() => {
		if (userStatus === 'success') {
			setUser(userData);
		}
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [userData, userError, userStatus]);

	if (auctionFetchStatus === 'error' || userStatus === 'error') {
		return (
			<motion.div
				initial={{ opacity: 0 }}
				animate={{ opacity: 1 }}
				exit={{ opacity: 0 }}
				transition={{ duration: 0.2 }}
				className="flex items-start gap-4 bg-error-bg text-error-text rounded p-4"
			>
				<div className="h-6 w-6">
					<WarningIcon />
				</div>

				<div className="flex flex-col gap-3">
					<p className="m-0">
						<b>Failed to fetch auction data</b>
					</p>
					<p>
						We're having trouble fetching auction data. Please try
						refreshing the page.
					</p>
				</div>
			</motion.div>
		);
	}

	return children;
}
