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
	const { socketUrl, setSocketError, setSocketAuction } = useBiddingState();

	const { lastJsonMessage } = useWebSocket<SocketMessage>(
		`${
			process.env.NODE_ENV === 'development'
				? socketUrlOverride
				: socketUrl
		}/${auctionId}`,
		{
			onError: () => {
				setSocketError();
			},
			share: true,
		},
	);

	useEffect(() => {
		if (lastJsonMessage) {
			setSocketAuction(lastJsonMessage);
		}
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [lastJsonMessage, setSocketError]);

	return null;
}
