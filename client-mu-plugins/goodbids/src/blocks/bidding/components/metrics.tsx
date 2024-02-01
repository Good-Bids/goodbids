import { useBiddingState } from '../store';

type MetricType = 'bids' | 'raised' | 'last-bid' | 'winning-bid';

const metricTypes: Record<MetricType, string> = {
	bids: 'BIDS',
	raised: 'RAISED',
	'last-bid': 'LAST BID',
	'winning-bid': 'WINNING BID',
};

type MetricBlockProps = {
	type: MetricType;
	value: number;
};

function formatValue(type: MetricType, value: number) {
	if (type === 'bids') {
		return value;
	}

	if (value < 1) {
		return '--';
	}

	return `$${value.toLocaleString()}`;
}

function MetricBlock({ type, value }: MetricBlockProps) {
	return (
		<div className="flex flex-col text-center">
			<p className="m-0 font-thin uppercase has-x-small-font-size">
				{metricTypes[type]}
			</p>
			<p className="m-1 font-extrabold has-large-font-size">
				{formatValue(type, value)}
			</p>
		</div>
	);
}

export function Metrics() {
	const { totalBids, lastBid, totalRaised, auctionStatus } =
		useBiddingState();

	if (
		auctionStatus === 'initializing' ||
		auctionStatus === 'upcoming' ||
		auctionStatus === 'prelive' ||
		auctionStatus === 'starting'
	) {
		return null;
	}

	return (
		<div className="grid grid-cols-3 gap-5 my-4">
			<MetricBlock type="bids" value={totalBids} />
			<MetricBlock type="raised" value={totalRaised} />
			<MetricBlock
				type={auctionStatus === 'closed' ? 'winning-bid' : 'last-bid'}
				value={lastBid}
			/>
		</div>
	);
}
