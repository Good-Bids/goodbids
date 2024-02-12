import { useBiddingState } from '../../store';
import { ErrorWrapper } from './error-wrapper';
import { Fetcher } from './fetcher';
import { Socket } from './socket';

type DataHandlerProps = {
	auctionId: number;
	children: React.ReactNode;
};

export function DataHandler({ auctionId, children }: DataHandlerProps) {
	const { fetchMode } = useBiddingState();

	return (
		<ErrorWrapper>
			<Fetcher auctionId={auctionId} />
			{fetchMode === 'socket' && <Socket auctionId={auctionId} />}
			{children}
		</ErrorWrapper>
	);
}
