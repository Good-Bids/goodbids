import { useEffect } from 'react';
import useWebSocket, { ReadyState } from 'react-use-websocket';
import { attributes } from '../utils/get-data-attributes';
import { useAuction } from '../utils/auction-store';

type MessageType = 'start' | 'update' | 'end';

type AuctionType = {
	startTime: string;
	endTime: string;
	totalBids: number;
	totalRaised: number;
	currentBid: number;
	lastBid: number;
	lastBidder: string;
	freeBidsAvailable: boolean;
};

export type Message = {
	type: MessageType;
	payload: AuctionType;
};

export function Socket() {
	const { auctionId, socketUrl } = attributes;
	const { setAuctionState } = useAuction();

	const { readyState, lastJsonMessage } = useWebSocket<Message>(
		`${socketUrl}/${auctionId}`,
		{
			onError: (event) => {
				// TODO: On error, swap to polling
				console.error(event);
			},
		},
	);

	useEffect(() => {
		if (readyState === ReadyState.OPEN) {
			if (lastJsonMessage) {
				setAuctionState(lastJsonMessage);
			}
		}
	}, [lastJsonMessage, readyState, setAuctionState]);

	return null;
}
