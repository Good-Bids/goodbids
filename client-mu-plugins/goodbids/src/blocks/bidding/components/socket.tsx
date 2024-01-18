import { useEffect } from 'react';
import useWebSocket, { ReadyState } from 'react-use-websocket';
import { attributes } from '../utils/get-data-attributes';
import { useAuction } from '../utils/auction-store';
import { SocketMessage } from '../utils/types';

// TODO: Remove once WP sends appropriate url in dev mode
const socketUrlOverride = 'ws://localhost:3000/_ws/connect';

export function Socket() {
	const { auctionId } = attributes;
	const { handleSocketUpdate, setUsePolling, socketUrl } = useAuction();

	const { readyState, lastJsonMessage } = useWebSocket<SocketMessage>(
		`${
			process.env.NODE_ENV === 'development'
				? socketUrlOverride
				: socketUrl
		}/${auctionId}`,
		{
			onError: () => {
				setUsePolling(true);
			},
		},
	);

	useEffect(() => {
		if (readyState === ReadyState.OPEN) {
			if (lastJsonMessage) {
				handleSocketUpdate(lastJsonMessage);
			}
		}

		if (readyState === ReadyState.CLOSED) {
			setUsePolling(true);
		}
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [lastJsonMessage, readyState, handleSocketUpdate]);

	return null;
}
