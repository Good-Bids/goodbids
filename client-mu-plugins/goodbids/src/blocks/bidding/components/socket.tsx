import { useEffect } from 'react';
import useWebSocket, { ReadyState } from 'react-use-websocket';
import { SocketMessage } from '../utils/types';
import { useBiddingState } from '../store';

// TODO: Remove once WP sends appropriate url in dev mode
const socketUrlOverride = 'ws://localhost:3000/_ws/connect';

type SocketProps = {
	auctionId: number;
};

export function Socket({ auctionId }: SocketProps) {
	const { socketUrl, setSocketError, setSocketAuction } = useBiddingState();

	const { readyState, lastJsonMessage } = useWebSocket<SocketMessage>(
		`${
			process.env.NODE_ENV === 'development'
				? socketUrlOverride
				: socketUrl
		}/${auctionId}`,
		{
			onError: () => {
				setSocketError();
			},
		},
	);

	useEffect(() => {
		if (readyState === ReadyState.OPEN) {
			if (lastJsonMessage) {
				setSocketAuction(lastJsonMessage);
			}
		}

		if (readyState === ReadyState.CLOSED) {
			setSocketError();
		}
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [lastJsonMessage, readyState, setSocketError]);

	return null;
}
