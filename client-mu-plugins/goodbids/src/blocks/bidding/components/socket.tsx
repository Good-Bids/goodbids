import { useEffect } from 'react';
import useWebSocket from 'react-use-websocket';
import { SocketMessage } from '../utils/types';
import { useBiddingState } from '../store';

// TODO: Remove once WP sends appropriate url in dev mode
const socketUrlOverride = 'ws://localhost:3000/_ws/connect';

type SocketProps = {
	auctionId: number;
};

export function Socket({ auctionId }: SocketProps) {
	const { auctionStatus } = useBiddingState();

	if (auctionStatus === 'live') {
		return <SocketHandler auctionId={auctionId} />;
	}

	return null;
}

function SocketHandler({ auctionId }: SocketProps) {
	const {
		siteId,
		socketUrl,
		setSocketError,
		setSocketAuction,
		auctionStatus,
	} = useBiddingState();

	const { lastJsonMessage } = useWebSocket<SocketMessage>(
		`${
			process.env.NODE_ENV === 'development'
				? socketUrlOverride
				: socketUrl
		}/${siteId}-${auctionId}`,
		{
			onError: () => {
				setSocketError();
			},
			reconnectInterval: 30000,
			shouldReconnect: () => auctionStatus === 'live',
		},
	);

	useEffect(() => {
		if (lastJsonMessage) {
			setSocketAuction(lastJsonMessage);
		}
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [lastJsonMessage]);

	return null;
}
