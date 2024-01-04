import { getInitialState } from '../utils/get-initial-state';
import { Metrics } from './metrics';

export function Driver() {
	const initialState = getInitialState();

	return (
		<div className="w-full">
			<Metrics
				blocks={[
					{ type: 'bids', value: initialState.bids },
					{ type: 'raised', value: initialState.raised },
					{ type: 'last-bid', value: initialState.lastBid },
				]}
			/>
		</div>
	);
}
